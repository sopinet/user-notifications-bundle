<?php
/**
 * Created by PhpStorm.
 * User: hud
 * Date: 5/05/15
 * Time: 13:00
 */
class DynamicRelationSubscriber implements EventSubscriber
{
    /**
     * {@inheritDoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::loadClassMetadata,
        );
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        // the $metadata is the whole mapping info for this class
        $metadata = $eventArgs->getClassMetadata();

        if ($metadata->getName() != 'Application\Sopinet\UserBundle\Entity\User') {
            return;
        }

        $namingStrategy = $eventArgs
            ->getEntityManager()
            ->getConfiguration()
            ->getNamingStrategy()
        ;

        $metadata->mapManyToMany(array(
            'targetEntity'  => \Sopinet\Bundle\UserNotificationsBundle\Entity\Notification::CLASS,
            'fieldName'     => 'notifications',
            'cascade'       => array('persist'),
            'joinTable'     => array(
                'name'        => strtolower($namingStrategy->classToTableName($metadata->getName())) . '_notification',
                'joinColumns' => array(
                    array(
                        'name'                  => $namingStrategy->joinKeyColumnName($metadata->getName()),
                        'referencedColumnName'  => $namingStrategy->referenceColumnName(),
                        'onDelete'  => 'CASCADE',
                        'onUpdate'  => 'CASCADE',
                    ),
                ),
                'inverseJoinColumns'    => array(
                    array(
                        'name'                  => 'notification_id',
                        'referencedColumnName'  => $namingStrategy->referenceColumnName(),
                        'onDelete'  => 'CASCADE',
                        'onUpdate'  => 'CASCADE',
                    ),
                )
            )
        ));
    }
}