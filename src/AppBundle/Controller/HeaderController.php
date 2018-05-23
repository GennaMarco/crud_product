<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 16/03/2018
 * Time: 16:22
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;

class HeaderController extends Controller
{
    public function getCategoriesAction()
    {
        //get repository for CRUD's operations on database
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render(
            'header/menubar_categories.html.twig',
            array('categories' => $categories));
    }

    public function getSearchBarWithCatsAction()
    {
        //get repository for CRUD's operations on database
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        $searchForm = $this->createSearchForm($categories);

        return $this->render(
            'header/searchbar_categories.html.twig',
            array('categories' => $categories,
                  'search_form' => $searchForm->createView()
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