<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property \App\Model\Entity\AuthAssignment[] $auth_assignment
 * @property \App\Model\Entity\Profile[] $profile
 * @property \App\Model\Entity\ProjectToUser[] $project_to_user
 * @property \App\Model\Entity\UsersToPost[] $users_to_post
 */
class User extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'username' => true,
        'auth_key' => true,
        'password_hash' => true,
        'password_reset_token' => true,
        'email' => true,
        'status' => true,
        'created_at' => true,
        'updated_at' => true,
        'auth_assignment' => true,
        'profile' => true,
        'project_to_user' => true,
        'users_to_post' => true
    ];
}
