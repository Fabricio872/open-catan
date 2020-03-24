<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ExtendedAbstractController extends AbstractController
{
    protected function em()
    {
        return $this->getDoctrine()->getManager();
    }
}
