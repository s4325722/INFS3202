<?php

namespace INFS3202\PracticalFourBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $deals = $this->getDoctrine()->getRepository('INFS3202PracticalFourBundle:Deal')->findAll();

        return array('deals' => $deals);
    }
}
