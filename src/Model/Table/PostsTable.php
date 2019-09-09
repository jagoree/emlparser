<?php
namespace App\Model\Table;

use Cake\ORM\Table;
/**
 * Posts Model
 *
 * @property \App\Model\Table\UserTable|\Cake\ORM\Association\BelongsTo $User
 * @property \App\Model\Table\ProjectTable|\Cake\ORM\Association\BelongsTo $Project
 * @property \App\Model\Table\UsersToPostTable|\Cake\ORM\Association\HasMany $UsersToPost
 *
 * @method \App\Model\Entity\Post get($primaryKey, $options = [])
 * @method \App\Model\Entity\Post newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Post[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Post|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Post saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Post patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Post[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Post findOrCreate($search, callable $callback = null, $options = [])
 */
class PostsTable extends Table
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

        $this->setTable('post');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('User', [
            'foreignKey' => 'author_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Project', [
            'foreignKey' => 'project_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('UsersToPost', [
            'foreignKey' => 'post_id'
        ]);
    }
    
    public function getList()
    {
        return $this->find()
                ->select(['id', 'created_at'])
                ->enableHydration(false)
                ->combine('created_at', 'id')
                ->toArray();
    }
    
    public function add($data)
    {
        ksort($data);
        $i = 0;
        foreach ($data as $posts) {
            $entities = $this->newEntities($posts, ['associated' => 'UsersToPost']);
            $i += count($this->saveMany($entities));
        }
        return $i;
    }
}
