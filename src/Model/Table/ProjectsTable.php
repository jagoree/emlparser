<?php
namespace App\Model\Table;

use Cake\ORM\Table;

/**
 * Projects Model
 *
 * @property \App\Model\Table\PostTable|\Cake\ORM\Association\HasMany $Post
 * @property \App\Model\Table\ProjectNotificationTable|\Cake\ORM\Association\HasMany $ProjectNotification
 * @property \App\Model\Table\ProjectToUserTable|\Cake\ORM\Association\HasMany $ProjectToUser
 *
 * @method \App\Model\Entity\Project get($primaryKey, $options = [])
 * @method \App\Model\Entity\Project newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Project[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Project|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Project saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Project patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Project[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Project findOrCreate($search, callable $callback = null, $options = [])
 */
class ProjectsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('project');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Post', [
            'foreignKey' => 'project_id'
        ]);
        $this->hasMany('ProjectNotification', [
            'foreignKey' => 'project_id'
        ]);
        $this->hasMany('ProjectToUser', [
            'foreignKey' => 'project_id'
        ]);
    }
    
    public function getList()
    {
        return $this->find()
                ->select(['id', 'Projects__name' => 'LOWER(name)'])
                ->contain([
                    'ProjectToUser'
                ])
                ->enableHydration(false)
                ->combine('name', function ($item) {
                    $users = array_filter($item['project_to_user'], function ($item) {
                        return $item['subscribed'] === true;
                    });
                    return ['id' => $item['id'], 'users' => $users];
                })
                ->toArray();
    }
    
    public function add($data)
    {
        return $this->save($this->newEntity($data));
    }
}
