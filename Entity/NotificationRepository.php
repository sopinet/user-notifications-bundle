<?php
namespace Sopinet\Bundle\UserNotificationsBundle\Entity;

use Sopinet\Bundle\UserNotificationsBundle\Entity\Notification;
use Doctrine\ORM\EntityRepository;

class NotificationRepository extends EntityRepository
{
    /**
     * Add notification for user logged (or user by parameter)
     *
     * @param String $action
     * @param String $objects (optional)
     * @param Integer $objects_id (optional)
     * @param String $link (optional)
     * @param User $user (optional)
     * @param null $image
     *
     * @return Notification
     */
    function addNotification($action, $objects = null, $objects_id = null, $link = null, $user = null, $image = null)
    {
        $em = $this->getEntityManager();

        $notification = new Notification();
        $notification->setAction($action);
        if ($objects != null) {
            $notification->setObjects($objects);
        }
        if ($objects_id != null) {
            $notification->setObjectsId($objects_id);
        }
        if ($link != null) {
            $notification->setLink($link);
        }
        if ($image != null) {
            $notification->setImage($image);
        }
        $notification->setUser($user);
        $notification->setEmail(0);
        $notification->setView(0);

        $em->persist($notification);
        $em->flush();

        return $notification;
    }
}