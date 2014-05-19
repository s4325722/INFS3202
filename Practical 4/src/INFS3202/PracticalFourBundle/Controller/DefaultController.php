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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Validator\Constraints\DateTime;

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

            $priceCaptures = array();

            if (preg_match('/(<|>)?\s*?\$?([\d.]+)/m', $price, $priceCaptures)) {
                if(!empty($priceCaptures[1])){
                    switch($priceCaptures[1]){
                        case '<':
                            $criteria['price_min'] = 0;
                            $criteria['price_max'] = $priceCaptures[2];
                            break;
                        case '>':
                            $criteria['price_min'] = $priceCaptures[2];
                            $criteria['price_max'] = PHP_INT_MAX;
                            break;
                    }
                }else{
                    $criteria['price_min'] = $priceCaptures[2];
                    $criteria['price_max'] = $priceCaptures[2];
                }
            } else {
                $result = "";
            }
        }

        $excludedCriteria = array();
        $repository = $this->getDoctrine()->getRepository('INFS3202PracticalFourBundle:Deal');
        $deals = $repository->findByCustomCriteria($criteria, true, $excludedCriteria);

        $categoryRepository = $this->getDoctrine()->getRepository('INFS3202PracticalFourBundle:Category');
        $categories = $categoryRepository->findAll();

        return array('deals' => $deals, 'excluded' => $excludedCriteria, 'categories' => $categories);
    }

    /**
     * @Route("/deal/{id}")
     * @Template()
     */
    public function dealAction($id)
    {
        $em = $this->getDoctrine();

        $dealRepository = $em->getRepository('INFS3202PracticalFourBundle:Deal');
        $deal = $dealRepository->find($id);

        $categoryRepository = $em->getRepository('INFS3202PracticalFourBundle:Category');
        $categories = $categoryRepository->findAll();

        $reviewRepository = $em->getRepository('INFS3202PracticalFourBundle:Review');
        $reviews = $reviewRepository->findBy(['deal' => $deal]);

        $now = new \DateTime();
        $remaining = $deal->getTimestampEnd()->diff($now);

        $expires = sprintf("%d Days and %d Hours", $remaining->d, $remaining->h);

        if($deal == null)
            throw new NotFoundHttpException();

        return array('deal' => $deal, 'categories' => $categories, 'reviews' => $reviews, 'expires' => $expires);
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
