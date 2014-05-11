<?php

namespace INFS3202\PracticalFourBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * @Route("/login")
     * @Template()
     */
    public function loginAction()
    {
        $request = $this->get('request');

        if($request->getMethod() == 'POST'){

        }
        
        $deals = $this->getDoctrine()->getRepository('INFS3202PracticalFourBundle:Deal')->findAll();

        return array('deals' => $deals);
    }
}
