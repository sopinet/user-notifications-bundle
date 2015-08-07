<?php
namespace Sopinet\Bundle\UserNotificationsBundle\Entity;

use Application\Sopinet\UserBundle\Entity\User;
use Sopinet\Bundle\UserNotificationsBundle\Entity\Notification;
use Doctrine\ORM\EntityRepository;
use Sopinet\UserPreferencesBundle\Entity\UserSetting;
use Sopinet\UserPreferencesBundle\Entity\UserValue;

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
     * @param bool $flush if you want notification to be flushed(set false for preUpdate, and prePersist doctrine listeners)
     *
     * @return Notification
     */
    function addNotification($action, $objects = null, $objects_id = null, $link = null, User $user = null, $image = null, $flush=true)
    {
        $em = $this->getEntityManager();
        /** @var UserSetting $setting */
        $setting=$em->getRepository('SopinetUserPreferencesBundle:UserSetting')->findOneByName('notification_web');
        /** @var UserValue $userValue */
        $userValue=$em->getRepository('SopinetUserPreferencesBundle:UserValue')->findOneBy(array(
            'user'=>$user,
            'setting'=>$setting->getId(),
        ));
        $value=$userValue==null?$setting->getDefaultOption():strpos($userValue->getValue(),$action);
        if(!$value){
            return null;
        }
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
        if ($flush) {
            $em->flush();
        }

        return $notification;
    }

    /**
     * Get all notifications for an entity
     * @param string $className
     * @param $id
     * @return Notification[]
     */
    public function getFromEntity($className="", $id)
    {
        /** @var Notification[] $notifications */
        $notifications =$this->createQueryBuilder('n')
            ->where('n.objects = :className')
            ->andWhere('n.objects_id = :id')
            ->setParameter('className', $className)
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();

        return $notifications;
    }

    /**
     * Get all notifications for an entity and a user
     * @param string $className
     * @param $id
     * @param User $user
     * @return Notification[]
     */
    public function getFromEntityAndUser($className="", $id, User $user)
    {
        /** @var Notification[] $notifications */
        $notifications =$this->createQueryBuilder('n')
            ->where('n.objects = :className')
            ->andWhere('n.user = :user')
            ->andWhere('n.objects_id = :id')
            ->setParameter('className', $className)
            ->setParameter('user', $user)
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();

        return $notifications;
    }


    /**
     * Get all notifications for an entity and a user
     * @param string $className
     * @param $id
     * @param User $user
     * @return Notification[]
     */
    public function getUnreadFromEntityAndUser($className="", $id, User $user)
    {
        /** @var Notification[] $notifications */
        $notifications =$this->createQueryBuilder('n')
            ->where('n.objects = :className')
            ->andWhere('n.user = :user')
            ->andWhere('n.objects_id = :id')
            ->andWhere('n.view = 0')
            ->setParameter('className', $className)
            ->setParameter('user', $user)
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();

        return $notifications;
    }
}