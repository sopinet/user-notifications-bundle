<?php
namespace Sopinet\Bundle\UserNotificationsBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Created by PhpStorm.
 * User: hud
 * Date: 5/05/15
 * Time: 12:54
 */
trait HasNotificationsTrait
{

    public function addNotification(\Sopinet\Bundle\UserNotificationsBundle\Entity\Notification$notification)
    {
        if ($this->notifications==null) {
           $this->notifications = new ArrayCollection();
        }
        $this->notifications->add($notification);

        return $this;
    }

    public function removeNotification(\Sopinet\Bundle\UserNotificationsBundle\Entity\Notification$notification)
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