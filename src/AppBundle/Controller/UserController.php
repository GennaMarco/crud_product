<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 08/03/2018
 * Time: 10:36
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use AppBundle\Service\FileUploader;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class UserController extends Controller
{
    public function indexAction(Request $request)
    {
        $user = $this->getUser();

        return $this->render(
            'user/index.html.twig',
            array('user' => $user)
        );
    }

    public function editAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, FileUploader $fileUploader)
    {
        $user = $this->getUser();
        $oldImagePath = $user->getImagePath();
        $deleteForm = $this->createDeleteForm($user);

        // build the form
        $editForm = $this->createForm(UserType::class, $user);

        // handle the submit (will only happen on POST)
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid())
        {
            // 3) save the User!
            $plainPassword = $editForm->get('plainPassword')->getData();
            if(!empty($plainPassword))
            {
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
            }

            $imagePath = $editForm->get('imagePath')->getData();
            if(!empty($imagePath))
            {
                $file = $user->getImagePath();
                $fileName = $fileUploader->upload($file);
                $user->setImagePath($fileName);
                if($oldImagePath != $this->container->getParameter('user_default_image'))
                {
                    $fileSystem = new Filesystem();
                    $fileSystem->remove($this->container->getParameter('users_directory') . '/' . $oldImagePath);
                }
            }
            else
            {
                $user->setImagePath($oldImagePath);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_user_index');
        }
        else
        {
            $user->setImagePath($oldImagePath);
        }

        return $this->render(
            'user/edit.html.twig',
            array(
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView()
            )
        );
    }

    public function deleteAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
            $this->get('security.token_storage')->setToken(null);
            $this->addFlash('success', 'Account deleted');
        }

        return $this->redirectToRoute('homepage');
    }

    public function deleteImgProfAction()
    {
        $user = $this->getUser();
        $imagePath = $user->getImagePath();

        if($imagePath != $this->container->getParameter('user_default_image'))
        {
            $fileSystem = new Filesystem();
            $fileSystem->remove($this->container->getParameter('users_directory') . '/' . $imagePath);
            $user->setImagePath($this->container->getParameter('user_default_image'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $this->redirectToRoute('app_user_edit');
    }

    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_user_delete', array('user_id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}