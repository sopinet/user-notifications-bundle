<?php
namespace Sopinet\Bundle\UserNotificationsBundle\Entity;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints\Type;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_notification")
 * @DoctrineAssert\UniqueEntity("id")
 * @ORM\HasLifecycleCallbacks
 */
class Notification
{
    use ORMBehaviors\Timestampable\Timestampable;
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="action", type="string", length=100)
     */
    protected $action;

    /**
     * @ORM\Column(name="objects", type="string", length=500, nullable=true)
     */
    protected $objects;

    /**
     * @ORM\Column(name="objects_id", type="string", length=100, nullable=true)
     */
    protected $objects_id;

    /**
     * @ORM\Column(name="view", type="boolean")
     */
    protected $view;

    /**
     * @ORM\Column(name="view_complete", type="boolean", nullable=true)
     */
    protected $view_complete;

    /**
     * @ORM\Column(name="email", type="boolean")
     */
    protected $email;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sopinet\UserBundle\Entity\User", inversedBy="notifications")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $user;


    /**
     * @ORM\Column(name="link", type="string", length=255, nullable=true)
     */
    protected $link;


    /**
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    protected $image;

    /**
     * @var objectsData
     *
     * @ORM\Column(name="objectsData", type="object")
     * @Type("stdClass")
     */
    protected $objectsData;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set action
     *
     * @param string $action
     * @return Notification
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set object
     *
     * @param integer $object
     * @return Notification
     */
    public function setObjects($objects)
    {
        $this->objects = $objects;

        return $this;
    }

    /**
     * Get object
     *
     * @return integer
     */
    public function getObjects()
    {
        return $this->objects;
    }

    /**
     * Set object
     *
     * @param integer $object
     * @return Notification
     */
    public function setObjectsId($objects_id)
    {
        $this->objects_id = $objects_id;

        return $this;
    }

    /**
     * Get object
     *
     * @return integer
     */
    public function getObjectsId()
    {
        return $this->objects_id;
    }

    /**
     * Set view
     *
     * @param boolean $view
     * @return Notification
     */
    public function setView($view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Get view
     *
     * @return boolean
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Set email
     *
     * @param boolean $email
     * @return Notification
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return boolean
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set user
     *
     * @param
     * @return Notification
     */
    public function setUser(\Application\Sopinet\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\Sopinet\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * Set view_complete
     *
     * @param boolean $viewComplete
     *
     * @return Notification
     */
    public function setViewComplete($viewComplete)
    {
        $this->view_complete = $viewComplete;

        return $this;
    }

    /**
     * Get view_complete
     *
     * @return boolean
     */
    public function getViewComplete()
    {
        return $this->view_complete;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Notification
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @ORM\PostPersist
     * @param LifecycleEventArgs $event
     */
    public function postPersist(LifecycleEventArgs $event)
    {
        $this->getUser()->addNotification($this);
        if(count(explode(',',$this->getObjects()))>1){
            $objectsClassName=explode(',',$this->getObjects());
            $objectsId=explode(',',$this->getObjectsId());
            for($i=0;$i<count($objectsClassName);$i++){
                $this->addObjectsData($event->getObjectManager()->getRepository($objectsClassName[$i])->findOneById($objectsId[$i]));
            }
        } else {
            $this->addObjectsData($event->getObjectManager()->getRepository($this->getObjects())->findOneById($this->getObjectsId()));
        }

        $event->getObjectManager()->persist($this->getUser());
        $event->getObjectManager()->persist($this);
        $event->getObjectManager()->flush();
    }


    /**
     * Set objectsData
     *
     * @param \stdClass $objectsData
     *
     * @return Notification
     */
    public function setObjectsData($objectsData)
    {
        $this->objectsData = $objectsData;

        return $this;
    }


    /**
     * Get objectsData
     *
     * @return \stdClass
     */
    public function getObjectsData()
    {
        return $this->objectsData;
    }

    /**
     * Get objectsData
     *
     * @return \stdClass
     */
    private function addObjectsData($object)
    {
        $this->objectsData[]=$object;
        return $this;
    }
}
