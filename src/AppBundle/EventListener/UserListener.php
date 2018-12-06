<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use AppBundle\Service\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\ORM\Events;

class UserListener implements EventSubscriberInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * UserListener constructor.
     * @param \Swift_Mailer $mailer
     * @param RouterInterface $router
     * @param TokenGeneratorInterface $tokenGenerator
     * @param ContainerInterface $container
     * @param EngineInterface $template
     */
    public function __construct(Mailer $mailer, RouterInterface $router, TokenGeneratorInterface $tokenGenerator, ContainerInterface $container)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->tokenGenerator = $tokenGenerator;
        $this->container = $container;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            UserEvent::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
            Events::preRemove
        ];
    }

    /**
     * @param UserEvent $userEvent
     */
    public function onRegistrationSuccess(UserEvent $userEvent)
    {
        $this->mailer->sendEmailRegistrationSuccess($userEvent->getUser());
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
