<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 01/03/2018
 * Time: 14:17
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    public function indexAction()
    {
        //get repository for CRUD's operations on database
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('admin/category/index.html.twig', array('categories' => $categories));
    }

    public function createAction(Request $request)
    {
        // 1) build the form
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // 3) save the Category!
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            // ... do any other work
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render(
            'admin/category/create.html.twig',
            array('form' => $form->createView())
        );
    }

    public function editAction(Request $request, $category_id)
    {
        //get repository for CRUD's operations on database
        $category = $this->getDoctrine()->getRepository(Category::class)->find($category_id);

        if (!$category)
        {
            throw $this->createNotFoundException(
                'No category found for id '.$category_id
            );
        }

        $deleteForm = $this->createDeleteForm($category);

        // build the form
        $editForm = $this->createForm(CategoryType::class, $category);

        // handle the submit (will only happen on POST)
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid())
        {
            // 3) save the Product!
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render(
            'admin/category/edit.html.twig',
            array(
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView()
            )
        );
    }

    public function deleteAction(Request $request, $category_id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($category_id);
        $form = $this->createDeleteForm($category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();

            $this->addFlash('success', 'Category deleted');
        }

        return $this->redirectToRoute('admin_category_index');
    }

    private function createDeleteForm(Category $category)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_category_delete', array('category_id' => $category->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}