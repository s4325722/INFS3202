<?php

namespace INFS3202\PracticalFourBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdminController extends Controller
{

    /**
     * @Route("/admin")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/admin/deal/all")
     * @Template()
     */
    public function allDealsAction()
    {
        $deals = $this->getDoctrine()->getRepository('INFS3202PracticalFourBundle:Deal')->findAll();
        return array('deals' => $deals);
    }

    /**
     * @Route("/admin/deal/create")
     * @Template()
     */
    public function createDealAction()
    {
        return array();
    }

    /**
     * @Route("/admin/deal/delete")
     * @Template()
     */
    public function deleteDealAction()
    {
        return array();
    }

    /**
     * @Route("/admin/deal/update")
     * @Template()
     */
    public function updateDealAction()
    {
        return array();
    }
}
