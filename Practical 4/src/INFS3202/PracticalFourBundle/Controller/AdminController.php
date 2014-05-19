<?php

namespace INFS3202\PracticalFourBundle\Controller;

use INFS3202\PracticalFourBundle\Entity\Deal;
use INFS3202\PracticalFourBundle\Entity\Form\CreateDealModel;
use INFS3202\PracticalFourBundle\Entity\Form\UpdateDealModel;
use INFS3202\PracticalFourBundle\Entity\Review;
use Proxies\__CG__\INFS3202\PracticalFourBundle\Entity\Category;
use Proxies\__CG__\INFS3202\PracticalFourBundle\Entity\Proprietor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Acl\Exception\Exception;

class AdminController extends Controller
{

    /**
     * @Route("/admin")
     * @Template()
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('infs3202_practicalfour_admin_alldeals'));
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
    public function createDealAction(Request $request)
    {
        $model = array();
        $model['category_exists'] = true;
        $model['proprietor_exists'] = true;

        $em = $this->getDoctrine()->getManager();

        $categoryRepository = $em->getRepository('INFS3202PracticalFourBundle:Category');
        $proprietorRepository = $em->getRepository('INFS3202PracticalFourBundle:Proprietor');

        $model['categories'] = $categoryRepository->findAll();
        $model['proprietors'] = $proprietorRepository->findAll();

        $createDealModel = new CreateDealModel();
        $createDealModel->setContainer($this->container);

        $formBuilder = $this->createFormBuilder($createDealModel);
        $formBuilder->add('categoryName');
        $formBuilder->add('categoryDescription');
        $formBuilder->add('proprietorName');
        $formBuilder->add('proprietorPhoneNumber');
        $formBuilder->add('proprietorAddress');
        $formBuilder->add('dealTitle');
        $formBuilder->add('dealDescription');
        $formBuilder->add('dealPrice');
        $formBuilder->add('dealBanner');
        $formBuilder->add('dealStartDate', 'date', array('widget' => 'single_text', 'format'=> 'dd-MM-yyyy'));
        $formBuilder->add('dealEndDate', 'date', array('widget' => 'single_text', 'format' => 'dd-MM-yyyy'));
        $formBuilder->add('reviews');

        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        if($request->getMethod() == 'POST'){

            if ($form->isValid()) {
                // Persist the proprietor
                $proprietor = $proprietorRepository->findOneBy(['name' => $createDealModel->getProprietorName()]);

                if($proprietor == null){
                    $proprietor = new Proprietor();
                    $proprietor->setName($createDealModel->getProprietorName());
                    $proprietor->setPhone($createDealModel->getProprietorPhoneNumber());
                    $proprietor->setAddresss($createDealModel->getProprietorAddress());
                    $em->persist($proprietor);
                }

                // Persist the category

                $category = $categoryRepository->findOneBy(['name' => $createDealModel->getCategoryName()]);

                if($category == null){
                    $category = new Category();
                    $category->setName($createDealModel->getCategoryName());
                    $category->setDescription($createDealModel->getCategoryDescription());
                    $em->persist($category);
                }

                $deal = new Deal();
                $deal->setCategory($category);
                $deal->setProprietor($proprietor);
                $deal->setTitle($createDealModel->getDealTitle());
                $deal->setPrice($createDealModel->getDealPrice());
                $deal->setDescription($createDealModel->getDealDescription());
                $deal->setBanner($createDealModel->getDealBanner());
                $deal->setTimestampStart($createDealModel->getDealStartDate());
                $deal->setTimestampEnd($createDealModel->getDealEndDate());

                $em->persist($deal);

                $reviews = explode("\n", $createDealModel->getReviews());

                for($i = 0; $i < count($reviews); $i++){
                    $line = $i+1;

                    $matches = array();

                    if (preg_match('/\A(.+?)\|(.+?)\|(.+?)\s*?\Z/', $reviews[$i], $matches)) {
                        $review = new Review();
                        $review->setDeal($deal);
                        $review->setAuthor($matches[1]);
                        $review->setTitle($matches[2]);
                        $review->setDescription($matches[3]);
                        $review->setTimestamp(new \DateTime());

                        $em->persist($review);
                    }
                }

                $em->flush();

                return $this->redirect($this->generateUrl('infs3202_practicalfour_admin_alldeals'));
            }
        }

        if($createDealModel->getCategoryName() != null && $categoryRepository->findOneBy(['name' => $createDealModel->getCategoryName()]) == null){
            $model['category_exists'] = false;
        }

        if($createDealModel->getProprietorName() != null && $proprietorRepository->findOneBy(['name' => $createDealModel->getProprietorName()]) == null){
            $model['proprietor_exists'] = false;
        }

        $model['form'] = $form->createView();

        return $model;
    }

    /**
     * @Route("/admin/deal/delete/{id}")
     * @Template()
     */
    public function deleteDealAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $reviewRepository = $em->getRepository('INFS3202PracticalFourBundle:Review');
        $dealRepository = $em->getRepository('INFS3202PracticalFourBundle:Deal');

        $deal = $dealRepository->find($id);

        if($deal != null){
            $reviews = $reviewRepository->findBy(['deal' => $deal]);

            foreach($reviews as $review){
                $em->remove($review);
            }

            $em->remove($deal);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('infs3202_practicalfour_admin_alldeals'));
    }

    /**
     * @Route("/admin/deal/update/{id}")
     * @Template()
     */
    public function updateDealAction(Request $request, $id)
    {
        $model = array();
        $model['category_exists'] = true;
        $model['proprietor_exists'] = true;

        $em = $this->getDoctrine()->getManager();

        $dealRepository = $em->getRepository('INFS3202PracticalFourBundle:Deal');
        $categoryRepository = $em->getRepository('INFS3202PracticalFourBundle:Category');
        $proprietorRepository = $em->getRepository('INFS3202PracticalFourBundle:Proprietor');
        $reviewRepository = $em->getRepository('INFS3202PracticalFourBundle:Review');

        $deal = $dealRepository->find($id);

        if($deal == null)
            throw new NotFoundHttpException();

        $updateDealModel = new UpdateDealModel();
        $updateDealModel->setContainer($this->container);
        $updateDealModel->setDeal($deal);

        $model['categories'] = $categoryRepository->findAll();
        $model['proprietors'] = $proprietorRepository->findAll();

        $formBuilder = $this->createFormBuilder($updateDealModel);
        $formBuilder->add('categoryName');
        $formBuilder->add('categoryDescription');
        $formBuilder->add('proprietorName');
        $formBuilder->add('proprietorPhoneNumber');
        $formBuilder->add('proprietorAddress');
        $formBuilder->add('dealId');
        $formBuilder->add('dealTitle');
        $formBuilder->add('dealDescription');
        $formBuilder->add('dealPrice');
        $formBuilder->add('dealBanner');
        $formBuilder->add('dealStartDate', 'date', array('widget' => 'single_text', 'format'=> 'dd-MM-yyyy'));
        $formBuilder->add('dealEndDate', 'date', array('widget' => 'single_text', 'format' => 'dd-MM-yyyy'));
        $formBuilder->add('reviews');

        $form = $formBuilder->getForm();
        $form->handleRequest($request);
        
        if($form->isSubmitted()){
            if ($form->isValid()) {
                // Persist the proprietor
                $proprietor = $proprietorRepository->findOneBy(['name' => $updateDealModel->getProprietorName()]);

                if($proprietor == null){
                    $proprietor = new Proprietor();
                    $proprietor->setName($updateDealModel->getProprietorName());
                    $proprietor->setPhone($updateDealModel->getProprietorPhoneNumber());
                    $proprietor->setAddresss($updateDealModel->getProprietorAddress());
                    $em->persist($proprietor);
                }

                // Persist the category

                $category = $categoryRepository->findOneBy(['name' => $updateDealModel->getCategoryName()]);

                if($category == null){
                    $category = new Category();
                    $category->setName($updateDealModel->getCategoryName());
                    $category->setDescription($updateDealModel->getCategoryDescription());
                    $em->persist($category);
                }

                $deal->setCategory($category);
                $deal->setProprietor($proprietor);
                $deal->setTitle($updateDealModel->getDealTitle());
                $deal->setPrice($updateDealModel->getDealPrice());
                $deal->setDescription($updateDealModel->getDealDescription());
                $deal->setBanner($updateDealModel->getDealBanner());
                $deal->setTimestampStart($updateDealModel->getDealStartDate());
                $deal->setTimestampEnd($updateDealModel->getDealEndDate());

                $em->persist($deal);

                foreach($reviewRepository->findBy(['deal' => $deal]) as $review){
                    $em->remove($review);
                }

                $reviews = explode("\n", $updateDealModel->getReviews());

                for($i = 0; $i < count($reviews); $i++){
                    $line = $i+1;

                    $matches = array();

                    if (preg_match('/\A(.+?)\|(.+?)\|(.+?)\s*?\Z/', $reviews[$i], $matches)) {
                        $review = new Review();
                        $review->setDeal($deal);
                        $review->setAuthor($matches[1]);
                        $review->setTitle($matches[2]);
                        $review->setDescription($matches[3]);
                        $review->setTimestamp(new \DateTime());

                        $em->persist($review);
                    }
                }

                $em->flush();

                return $this->redirect($this->generateUrl('infs3202_practicalfour_admin_alldeals'));
            }
        }

        if($updateDealModel->getCategoryName() != null && $categoryRepository->findOneBy(['name' => $updateDealModel->getCategoryName()]) == null){
            $model['category_exists'] = false;
        }

        if($updateDealModel->getProprietorName() != null && $proprietorRepository->findOneBy(['name' => $updateDealModel->getProprietorName()]) == null){
            $model['proprietor_exists'] = false;
        }

        $model['form'] = $form->createView();

        return $model;
    }
}
