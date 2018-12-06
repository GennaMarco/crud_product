<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 06/12/2018
 * Time: 15:47
 */

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var \Twig_Environment
     */
    private $templating;


    public function __construct(\Swift_Mailer $mailer, ContainerInterface $container, RouterInterface $router, \Twig_Environment $templating)
    {
        $this->mailer = $mailer;
        $this->container = $container;
        $this->router = $router;
        $this->templating = $templating;
    }

    public function sendEmailRegistrationSuccess(User $user)
    {
        $emailFrom = $this->container->getParameter('user_default_from_email_address');
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom($emailFrom)
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render('Emails/registration.html.twig', array('name' => $user->getUsername())),
                'text/html'
            )
        ;

        $this->mailer->send($message);
    }
}