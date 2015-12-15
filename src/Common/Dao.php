<?php
/**
 * Created by PhpStorm.
 * User: nasibli
 * Date: 11.12.2015
 * Time: 18:41
 */

namespace Common;
use Doctrine\ORM\Query\Expr;

class Dao
{
    public function applyLimits($select, $limits) {
        if ($limits) {
            $select
                ->setFirstResult($limits['start'])
                ->setMaxResults($limits['limit']);
        }
    }

    public function getPagingResult ($select, $limits, $prefix)
    {
        $this->applyLimits($select, $limits);
        $items = [];
        $items['data'] = $select->getQuery()->getArrayResult();

        $select->add('select', new Expr\Select('count(' . $prefix . '.id)'), false);

        $select->setFirstResult(0);
        $select->setMaxResults(1);


        $items['total'] = (int)$select->getQuery()->getSingleScalarResult();
        return $items;
    }

    public function getKeyValueResult($select, $keyField, $valueField)
    {
        $res = [];
        $items = $select->getQuery()->getArrayResult();
        foreach ($items as $item) {
            $res[$item[$keyField]] = $item[$valueField];
        }
        return $res;
    }

    public function update($entity)
    {
        $em = $this->_doctrine->getManager();
        $em->persist($entity);
        $em->flush();
    }
}