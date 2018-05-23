<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 12/03/2018
 * Time: 09:47
 */

namespace AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\User;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserEvent
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        // only act on some "User" entity
        if ($entity instanceof User)
        {
            $imagePath = $entity->getImagePath();
            if($imagePath != $this->container->getParameter('user_default_image'))
            {
                $fileSystem = new Filesystem();
                $fileSystem->remove($this->container->getParameter('users_directory') . '/' . $imagePath);
            }
        }
    }
}