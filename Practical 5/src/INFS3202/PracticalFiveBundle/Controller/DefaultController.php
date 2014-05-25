<?php

namespace INFS3202\PracticalFiveBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use INFS3202\PracticalFiveBundle\Entity\Comment;
use INFS3202\PracticalFiveBundle\Entity\Deal;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $criteria = array();

        if($request->getMethod() == 'POST'){
            $name = $request->request->get('search_name');
            $location = $request->request->get('search_location');
            $category = $request->request->get('search_category');
            $price = $request->request->get('search_price');

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
            }
        }

        $excludedCriteria = array();
        $repository = $this->getDoctrine()->getRepository('INFS3202PracticalFiveBundle:Deal');
        $deals = $repository->findByCustomCriteria($criteria, true, $excludedCriteria);

        $categoryRepository = $this->getDoctrine()->getRepository('INFS3202PracticalFiveBundle:Category');
        $categories = $categoryRepository->findAll();

        return array('deals' => $deals, 'excluded' => $excludedCriteria, 'categories' => $categories);
    }

    /**
     * @Route("/search")
     * @Route("/search?{format}")
     */
    public function searchAction(Request $request, $format = 'json'){
        $criteria = array();

        $name = $request->get('search_name');

        if(!empty($name)){
            $criteria['title'] = $name;
        }

        $location = $request->get('search_location');

        if(!empty($location)){
            $criteria['location'] = $location;
        }

        $category = $request->get('search_category');

        if(!empty($category) && $category != 'Any'){
            $criteria['category'] = $category;
        }

        $price = $request->get('search_price');

        if(!empty($price)){
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
            }
        }

        $excludedCriteria = array();
        $repository = $this->getDoctrine()->getRepository('INFS3202PracticalFiveBundle:Deal');
        $deals = $repository->findByCustomCriteria($criteria, true, $excludedCriteria);

        $data = array();
        $data['results'] = array();
        $data['excluded'] = $excludedCriteria;

        foreach($deals as $deal){
            $data['results'][] = [
                'id' => $deal->getId(),
                'link' => $this->generateUrl('infs3202_practicalfive_default_deal', ['id' => $deal->getId()]),
                'name' => $deal->getTitle(),
                'price' => $deal->getPrice(),
                'banner' => $deal->getBanner(),
                'lat' => $deal->getProprietor()->getLatitude(),
                'lng' => $deal->getProprietor()->getLongitude()
            ];
        }

        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/deal/{id}")
     * @Template()
     */
    public function dealAction($id)
    {
        $em = $this->getDoctrine();

        $dealRepository = $em->getRepository('INFS3202PracticalFiveBundle:Deal');
        $deal = $dealRepository->find($id);

        $categoryRepository = $em->getRepository('INFS3202PracticalFiveBundle:Category');
        $categories = $categoryRepository->findAll();

        $reviewRepository = $em->getRepository('INFS3202PracticalFiveBundle:Review');
        $reviews = $reviewRepository->findBy(['deal' => $deal]);

        $commentRepository = $em->getRepository('INFS3202PracticalFiveBundle:Comment');
        $comments = $commentRepository->findBy(['deal' => $deal]);

        $now = new \DateTime();
        $remaining = $deal->getTimestampEnd()->diff($now);

        $expires = sprintf("%d Days and %d Hours", $remaining->d, $remaining->h);

        if($deal == null)
            throw new NotFoundHttpException();

        return array(
            'deal' => $deal,
            'categories' => $categories,
            'reviews' => $reviews,
            'comments' => $comments,
            'expires' => $expires
        );
    }

    /**
     * @Route("/deal/{id}/comment", name="infs3202_practicalfive_default_deal_comment")
     */
    public function commentAction(Request $request, $id)
    {
        $data = array();

        if($request->getMethod() == 'POST'){
            $em = $this->getDoctrine()->getManager();

            $dealRepository = $em->getRepository('INFS3202PracticalFiveBundle:Deal');
            $deal = $dealRepository->find($id);

            if($deal == null)
                throw new NotFoundHttpException();

            $text = $request->request->get('deal_comment_text');

            if(!empty($text)){
                $comment = new Comment();
                $comment->setDeal($deal);
                $comment->setText($text);

                $em->persist($comment);
                $em->flush();
            }
        }else{
            throw new \HttpRequestMethodException();
        }

        $commentRepository = $em->getRepository('INFS3202PracticalFiveBundle:Comment');
        $comments = $commentRepository->findBy(['deal' => $deal]);

        foreach($comments as $comment){
            $data['comments'][] = $comment->getText();
        }

        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
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
