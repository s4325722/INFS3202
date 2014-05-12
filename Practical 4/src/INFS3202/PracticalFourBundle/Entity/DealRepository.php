<?php
/**
 * Created by PhpStorm.
 * User: Blake
 * Date: 12/05/2014
 * Time: 10:38 AM
 */

namespace INFS3202\PracticalFourBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;


class DealRepository extends EntityRepository {

    public function findByCustomCriteria(array $customCriteria, $expand = true, array &$excluded){
        $criteria = array('title', 'location', 'category', 'price_min', 'price_max');
        $criteriaDescription = array(
            'title' => 'Name',
            'location' => 'Location',
            'category' => 'Category',
            'price_min' => 'Price',
            'price_max' => 'Price'
        );

        $resultCount = -1;

        do {

            if($resultCount == 0){
                $exclude = null;

                while($exclude = array_pop($criteria)){
                    if(array_key_exists($exclude, $customCriteria)){
                        $excluded[$criteriaDescription[$exclude]] = $customCriteria[$exclude];
                        unset($customCriteria[$exclude]);
                        break;
                    }
                }
            }

            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb = $qb->select('d')->from('INFS3202PracticalFourBundle:Deal', 'd');
            //$qb = $qb->innerJoin('d.proprietor', 'p', Join::ON, 'd.proprietor_id = p.id');
            //$qb = $qb->innerJoin('p.location', 'l', Join::ON, 'p.proprietor_id = p.id');

            if(array_key_exists('title', $customCriteria) && !empty($customCriteria['title'])){
                $qb = $qb->andWhere($qb->expr()->like('d.title', ':title'));
                $qb->setParameter('title', '%'.$customCriteria['title'].'%');
            }

            if(array_key_exists('location', $customCriteria) && !empty($customCriteria['location'])){
                $qb = $qb->leftJoin('d.proprietor', 'p');
                $qb = $qb->leftJoin('p.location', 'l');
                $qb = $qb->andWhere($qb->expr()->like('l.address', ':location'));
                $qb->setParameter('location', '%'.$customCriteria['location'].'%');
            }

            if(array_key_exists('category', $customCriteria) && !empty($customCriteria['category'])){
                $qb = $qb->leftJoin('d.category', 'c');
                $qb = $qb->andWhere($qb->expr()->like('c.name', ':category'));
                $qb->setParameter('category', '%'.$customCriteria['category'].'%');
            }

            if(array_key_exists('price_min', $customCriteria) && !empty($customCriteria['price_min'])){
                $qb = $qb->andWhere($qb->expr()->gt('d.price', ':price_min'));
                $qb->setParameter('price_min', $customCriteria['price_min']);
            }

            if(array_key_exists('price_max', $customCriteria) && !empty($customCriteria['price_max'])){
                $qb = $qb->andWhere($qb->expr()->lt('d.price', ':price_max'));
                $qb->setParameter('price_min', $customCriteria['price_min']);
            }



        }while(($resultCount = count($qb->getQuery()->getResult())) == 0);

        return $qb->getQuery()->execute();
    }
} 