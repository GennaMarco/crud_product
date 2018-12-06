<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 07/03/2018
 * Time: 09:45
 */

namespace AppBundle\Controller;

use AppBundle\AppEvents;
use AppBundle\EventListener\UserEvent;
use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends Controller
{
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer, EventDispatcherInterface $eventDispatcher)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setEnabled(true);
            $user->setImagePath($this->container->getParameter('user_default_image'));
            $user->setRole('ROLE_ADMIN');

            $session = $this->get('session');
            $session->set('username', $user->getUsername());
            $session->set('password', $user->getPlainPassword());

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $userEvent = new UserEvent($user);
            $eventDispatcher->dispatch(UserEvent::REGISTRATION_SUCCESS, $userEvent);


            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('security_login');
        }

        return $this->render(
            'registration/register.html.twig',
            array('form' => $form->createView())
        );
    }
}