<?php
namespace Sopinet\Bundle\UserNotificationsBundle\Entity;
/**
 * Created by PhpStorm.
 * User: hud
 * Date: 5/05/15
 * Time: 12:54
 */
trait HasNotificationsTrait
{
    /**
     * @var ArrayCollection
     */
    protected $notifications;

    public function addNotification(\Sopinet\Bundle\UserNotificationsBundle\Entity\Notification $notification)
    {
        $this->notifications->add($notification);

        return $this;
    }

    public function removeNotification(\Sopinet\Bundle\UserNotificationsBundle\Entity\Notification $notification)
    {
        $this->notifications->removeElement($notification);
    }

    public function getNotifications()
    {
        return $this->notifications;
    }

    public function setNotifications($notifications)
    {
        $this->notifications = $notifications;

        return $this;
    }
}