<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 15/03/2018
 * Time: 10:21
 */

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PagesController extends Controller
{
    public function dashboardAction()
    {
        return
            $this->render(
                'admin/pages/dashboard.html.twig'
            );
    }
}