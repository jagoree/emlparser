<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Http\Exception\InternalErrorException;
use Phemail\MessageParser;
use Cake\ORM\TableRegistry;
use Cake\Filesystem\File;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class ParserController extends AppController
{
    private $__counts = ['projects' => 0, 'users' => 0, 'posts' => 0];

    public function index()
    {
        $path = $this->checkDir();
        if ($this->getRequest()->is('post')) {
            if (!$filename = $this->unzip($path)) {
                return $this->redirect('/');
            }
            return $this->process($path, $filename);
        }
    }

    private function process($path, $filename)
    {
        $dom = new \DOMDocument();
        $data = [];
        $this->loadModel('Projects');
        $this->loadModel('Users');
        $PostsTable = TableRegistry::getTableLocator()->get('Posts');
        $projects = $this->Projects->getList();
        $users = $this->Users->getList();
        $posts = $PostsTable->getList();
        foreach ($this->getMessages($path, $posts) as $value) {
            @$dom->loadHTML($value['body']);
            $xpath = new \DOMXPath($dom);
            $nodes = $xpath->query('/html/body/table/tr[not(td[@height])]');
            $project = $_project = null;
            foreach ($nodes as $node) {
                $td = $node->getElementsByTagName('td')->item(0);
                if ($project = $this->checkProject($td, $project, $_project, $projects)) {
                    continue;
                }
                $data[$value['ts']][] = $this->getPosts($dom, $xpath, $node, $projects, $_project, $users, $value['ts']);
            }
        }
        unlink($filename);
        $this->__counts['posts'] = $PostsTable->add($data);
        $this->Flash->set('Импорт завершен', ['params' => $this->__counts]);
        return $this->redirect('/');
    }
    
    private function getMessages($path, $posts)
    {
        $parser = new MessageParser();
        $messages = [];
        foreach (array_diff(scandir($path), ['.', '..']) as $file) {
            $email = $parser->parse($path . $file);
            unlink($path . $file);
            $dt = (new \DateTime($email->getHeaderValue('date')));
            $j = 1;
            if ($dt->format('N') == 7) {
                $j = 2;
            }
            $ts = mktime($dt->format('H'), $dt->format('i'), $dt->format('s'), $dt->format('n'), $dt->format('j') - $j, $dt->format('Y'));
            if (isset($posts[$ts])) {
                continue;
            }
            $parts = $email->getParts();
            if (!isset($parts[1])) {
                continue;
            }
            $body = preg_replace("'<!doctype.+(<!doctype)'is", '$1', quoted_printable_decode($parts[1]->getContents()));
            $messages[] = compact('body', 'ts');
        }
        return $messages;
    }

    private function checkProject($node, $project, &$_project, &$projects)
    {
        if ($node->getAttribute('class') == 'project-name') {
            $project = trim($node->nodeValue);
            $_project = mb_strtolower($project);
            if (!isset($projects[$_project])) {
                $entity = $this->Projects->add(['name' => $project]);
                $projects[$_project] = ['id' => $entity->id, 'users' => []];
                $this->__counts['projects'] ++;
            }
            return $project;
        }
        return false;
    }

    private function checkUser($node, $dom, $xpath, &$users)
    {
        $name = trim($xpath->query('td/table/tr/td/a', $node)->item(0)->nodeValue);
        $_name = mb_strtolower($name);
        if (!$name) {
            echo $dom->saveHTML($node);
            die();
        }
        if (!isset($users[$_name])) {
            list($lastname, $firstname) = explode(' ', $name . ' ');
            if ($entity = $this->Users->add([
                'firstname' => $firstname,
                'lastname' => $lastname
                    ])) {
                $users[$_name] = $entity->id;
                $this->__counts['users'] ++;
            } else {
                throw new InternalErrorException('Can\'t save new user');
            }
        }
        return $_name;
    }

    private function getPosts($dom, $xpath, $node, $projects, $_project, &$users, $ts)
    {
        $_name = $this->checkUser($node, $dom, $xpath, $users);
        $post = [];
        foreach ($xpath->query('td/table/tr/td[@class="post-text"]', $node)->item(0)->childNodes as $post_node) {
            $post[] = trim($dom->saveHTML($post_node));
        }
        return ['author_id' => $users[$_name], 'project_id' => $projects[$_project]['id'], 'created_at' => $ts, 'updated_at' => $ts, 'body' => implode('', $post), 'users_to_post' => $projects[$_project]['users']];
    }

    private function checkDir()
    {
        $path = TMP . 'emls' . DS;
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        return $path;
    }

    private function unzip($dst_dir)
    {
        
        if (!$this->checkFile()) {
            return false;
        }
        $zip = new \ZipArchive();
        $filename = null;
        $path = Configure::read('App.emlPath');
        foreach (array_diff(scandir(Configure::read('App.emlPath')), ['.', '..']) as $file) {
            $filename = $path . $file;
            break;
        }
        if (!$filename or ! $zip->open($filename)) {
            $this->Flash->set('ZIP-файл не найден!', ['element' => 'error']);
            return false;
        }
        $zip->extractTo($dst_dir);
        $zip->close();
        return $filename;
    }

    private function checkFile()
    {
        if (!$tmp_file = $this->getRequest()->getData('file.tmp_name')) {
            $this->Flash->set('Файл не загружен!', ['element' => 'error']);
            return false;
        }
        $file = new File($tmp_file);
        if ($file->exists() && $file->mime() == 'application/zip') {
            foreach (glob(Configure::read('App.emlPath') . '/*') as $_file) {
                unlink($_file);
            }
            move_uploaded_file($tmp_file, Configure::read('App.emlPath') . $this->getRequest()->getData('file.name'));
            return true;
        }
        $this->Flash->set('Файл не загружен!', ['element' => 'error']);
        return false;
    }

}
