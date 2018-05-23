<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 13/03/2018
 * Time: 15:04
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{
    public function indexAction(Request $request)
    {
        $categoryName = $request->query->get('category');
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        if(empty($categoryName))
        {
            $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        }
        else
        {
            $products = $this->getDoctrine()->getRepository(Product::class)->findProductsByCategory($categoryName);
        }

        return $this->render('product/index.html.twig',
            array(
                'products' => $products,
                'categories' => $categories,
            )
        );
    }

    public function searchAction(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        $searchForm = $this->createSearchForm($categories);
        $searchForm->handleRequest($request);

        $products = array();
        $textSearched = '';
        $categorySelected = '';

        if ($searchForm->isSubmitted() && $searchForm->isValid())
        {
            $textSearched = $request->query->get('form')['search'];
            $categorySelected = $request->query->get('form')['category'];

            // value 0 for All categories
            if(is_numeric($categorySelected))
            {
                $products = $this->getDoctrine()->getRepository(Product::class)->findProductsByName($textSearched);
            }
            else
            {
                $products = $this->getDoctrine()->getRepository(Product::class)->findProductsByNameAndCategory($textSearched, $categorySelected);
            }
        }

        return $this->render('product/search.html.twig',
            array(
                'products' => $products,
                'categories' => $categories,
                'text_searched' => $textSearched,
                'category_selected' => $categorySelected
            )
        );
    }

    public function viewAction($product_name)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->findOneByName($product_name);

        return $this->render('product/view.html.twig',
            array(
                'product' => $product
            )
        );
    }

    private function createSearchForm($categories)
    {
        $catsFormatForChoice = array('All Categories' => 0);
        foreach($categories as $key => $cat)
        {
            $catsFormatForChoice[$cat->getName()] = $cat->getName();
        }

        return $this->createFormBuilder()
            ->add('search', SearchType::class)
            ->add('category', ChoiceType::class, array('choices' => $catsFormatForChoice))
            ->setAction($this->generateUrl('app_product_search'))
            ->setMethod('GET')
            ->getForm()
            ;
    }
}