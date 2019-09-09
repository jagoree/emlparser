<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Project Entity
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int|null $project_manager
 * @property int|null $tech_lead
 * @property int|null $process_manager
 * @property int|null $tech_dir
 * @property int|null $reviewer
 * @property int|null $warning_problem
 * @property int|null $warning_alarm
 * @property string|null $slack_name
 * @property string|null $emoji
 *
 * @property \App\Model\Entity\Post[] $post
 * @property \App\Model\Entity\ProjectNotification[] $project_notification
 * @property \App\Model\Entity\ProjectToUser[] $project_to_user
 */
class Project extends Entity
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
        'name' => true,
        'status' => true,
        'project_manager' => true,
        'tech_lead' => true,
        'process_manager' => true,
        'tech_dir' => true,
        'reviewer' => true,
        'warning_problem' => true,
        'warning_alarm' => true,
        'slack_name' => true,
        'emoji' => true,
        'post' => true,
        'project_notification' => true,
        'project_to_user' => true
    ];
}
