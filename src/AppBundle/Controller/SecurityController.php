<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 07/03/2018
 * Time: 10:41
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        $session = $this->get('session');
        $lastUsername = ($session->get('username')) ? $session->get('username')  : "";
        $lastPassword = ($session->get('password')) ? $session->get('password')  : "";
        $session->clear();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'last_password' => $lastPassword,
            'error'         => $error,
        ));
    }

    public function loginCheckAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }
}