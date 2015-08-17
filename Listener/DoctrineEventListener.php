<?php

namespace Sopinet\Bundle\UserNotificationsBundle\Listener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\DiscriminatorMap;

class DoctrineEventListener
{
    /**
     * @var array
     */
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $event)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $event->getClassMetadata();
        $class = $metadata->getReflectionClass();

        if ($class === null) {
            $class = new \ReflectionClass($metadata->getName());
        }

        if ($class->getName() != "Sopinet\Bundle\UserNotificationsBundle\Entity\Notification") {
            return;
        }

        $metadata->setCustomRepositoryClass($this->config['repositoryClass']);
    }
}