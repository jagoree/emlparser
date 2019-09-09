<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Utility\Text;

/**
 * Users Model
 *
 * @property \App\Model\Table\AuthAssignmentTable|\Cake\ORM\Association\HasMany $AuthAssignment
 * @property \App\Model\Table\ProfileTable|\Cake\ORM\Association\HasMany $Profile
 * @property \App\Model\Table\ProjectToUserTable|\Cake\ORM\Association\HasMany $ProjectToUser
 * @property \App\Model\Table\UsersToPostTable|\Cake\ORM\Association\HasMany $UsersToPost
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 */
class UsersTable extends Table
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

        $this->setTable('user');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('AuthAssignment', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasOne('Profile', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('ProjectToUser', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('UsersToPost', [
            'foreignKey' => 'user_id'
        ]);
        Text::setTransliteratorId('Russian-Latin/BGN; [\u0080-\u7fff] remove; Any-Latin; Latin-ASCII; Lower');
    }
    
    public function getList()
    {
        return $this->find()
                ->select(['id'])
                ->contain([
                    'Profile' => ['fields' => ['user_id', 'lastname', 'firstname']]
                ])
                ->enableHydration(false)
                ->combine(function ($item) {
                    return mb_strtolower(implode(' ', [$item['profile']['lastname'], $item['profile']['firstname']]));
                }, 'id')
                ->toArray();
    }
    
    public function add($data)
    {
        return $this->save($this->newEntity([
            'username' => Text::slug($data['lastname']),
            'profile' => [
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname']]
            ], ['associated' => 'Profile']));
    }
}
