<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{
    /**
     *
     */
    public function hardToTest()
    {
        $this->container->get('router');
    }
}
