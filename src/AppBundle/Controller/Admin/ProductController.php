<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 02/03/2018
 * Time: 14:51
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{
    public function indexAction()
    {
        //get repository for CRUD's operations on database
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->render('admin/product/index.html.twig', array('products' => $products));
    }

    public function createAction(Request $request)
    {
        // 1) build the form
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // 3) save the Category!
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            // ... do any other work
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('admin_product_index');
        }

        return $this->render(
            'admin/product/create.html.twig',
            array('form' => $form->createView())
        );
    }

    public function editAction(Request $request, $product_id)
    {
        //get repository for CRUD's operations on database
        /** @var Product $product */
        $product = $this->getDoctrine()->getRepository(Product::class)->find($product_id);
        
        if (!$product)
        {
            throw $this->createNotFoundException(
                'No product found for id '.$product_id
            );
        }

        $deleteForm = $this->createDeleteForm($product);

        // build the form
        $editForm = $this->createForm(ProductType::class, $product);

        // handle the submit (will only happen on POST)
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid())
        {
            // 3) save the Product!
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('admin_product_index');
        }

        return $this->render(
            'admin/product/edit.html.twig',
            array(
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView()
            )
        );
    }

    public function deleteAction(Request $request, $product_id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($product_id);
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();

            $this->addFlash('success', 'Product deleted');
        }

        return $this->redirectToRoute('admin_product_index');
    }

    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_product_delete', array('product_id' => $product->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}