<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Post Entity
 *
 * @property int $id
 * @property int $author_id
 * @property int $project_id
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string|null $type
 * @property string|null $body
 * @property bool|null $is_deleted
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Project $project
 * @property \App\Model\Entity\UsersToPost[] $users_to_post
 */
class Post extends Entity
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
        'author_id' => true,
        'project_id' => true,
        'created_at' => true,
        'updated_at' => true,
        'type' => true,
        'body' => true,
        'is_deleted' => true,
        'user' => true,
        'project' => true,
        'users_to_post' => true
    ];
}
