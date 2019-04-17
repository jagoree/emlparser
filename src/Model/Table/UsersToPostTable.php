<?php
namespace App\Model\Table;

use Cake\ORM\Table;

/**
 * UsersToPost Model
 *
 * @property \App\Model\Table\PostTable|\Cake\ORM\Association\BelongsTo $Post
 * @property \App\Model\Table\UserTable|\Cake\ORM\Association\BelongsTo $User
 *
 * @method \App\Model\Entity\UsersToPost get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsersToPost newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsersToPost[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsersToPost|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsersToPost saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsersToPost patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsersToPost[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsersToPost findOrCreate($search, callable $callback = null, $options = [])
 */
class UsersToPostTable extends Table
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

        $this->setTable('users_to_post');
        $this->setDisplayField('post_id');
        $this->setPrimaryKey(['post_id', 'user_id']);

        $this->belongsTo('Post', [
            'foreignKey' => 'post_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('User', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
    }
}
