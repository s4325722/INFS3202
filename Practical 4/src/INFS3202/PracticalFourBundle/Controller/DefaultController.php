<?php

namespace INFS3202\PracticalFourBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\SecurityContextInterface;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $criteria = array();
        $request = $this->get('request');

        if($request->getMethod() == 'POST'){
            $name = $request->get('search_name', 'POST');
            $location = $request->get('search_location', 'POST');
            $category = $request->get('search_category', 'POST');
            $price = $request->get('search_price', 'POST');

            if($name != null && !empty($name))
                $criteria['title'] = $name;

            if($location != null && !empty($location))
                $criteria['location'] = $location;

            if($category != null && !empty($category) && $category != 'Any')
                $criteria['category'] = $category;
        }

        $excludedCriteria = array();
        $repository = $this->getDoctrine()->getRepository('INFS3202PracticalFourBundle:Deal');
        $deals = $repository->findByCustomCriteria($criteria, true, $excludedCriteria);

        $categoryRepository = $this->getDoctrine()->getRepository('INFS3202PracticalFourBundle:Category');
        $categories = $categoryRepository->findAll();

        return array('deals' => $deals, 'excluded' => $excludedCriteria, 'categories' => $categories);
    }

//    /**
//     * @Route("/login")
//     * @Template()
//     */
//    public function loginAction()
//    {
//        $request = $this->get('request');
//
//        $error = 'none';
//
//        if($request->getMethod() == 'POST'){
//
//
//        }
//
//        $response = new Response(json_encode(array('error' => $error)));
//        $response->headers->set('Content-Type', 'application/json');
//
//        return $response;
//    }
}
