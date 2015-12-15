<?php
/**
 * Created by PhpStorm.
 * User: nasibli
 * Date: 15.12.2015
 * Time: 12:39
 */

namespace AppBundle\Model;
use Common\Dao;
use AppBundle\Model\TeachersEntity;

class TeachersDao extends Dao
{
    public function __construct($doctrine)
    {
        $this->_doctrine = $doctrine;
    }

    protected $_doctrine;

    public function setValues(TeachersEntity $teacher, $data)
    {
        $teacher->setName($data['name']);
        $teacher->setPhone($data['phone']);
        $teacher->setGender($data['genderId']);
        $teacher->setPupilCount(0);
        $teacher->setOnlyApril(0);
        return $teacher;
    }

    public function updateStat($cnt, $onlyApril, $teacherId)
    {
        $em = $this->_doctrine->getManager();
        $teacher = $em->getRepository('AppBundle:TeachersEntity')->find($teacherId);
        $teacher->setPupilCount($cnt);
        $teacher->setOnlyApril($onlyApril);
        $em->flush();
    }

    public function getAllForPaging ($filters, $orders, $limits) {
        $select = $this->_doctrine->getManager()->createQueryBuilder();
        $select->select('t')
            ->from('AppBundle:TeachersEntity', 't');

        if (isset($filters['search']) && !empty($filters['search'])) {
            $select->andWhere('t.name like :search')->setParameter('search', $filters['search'].'%');
        }
        if (isset($filters['only_april']) && $filters['only_april'] == 1) {
            $select->andWhere( 't.only_april=1' );
        } else {
            $select->andWhere('t.only_april in (1,0)');
        }
        if ($orders) {
            $select->orderBy('t.'.$orders['sort'], $orders['dir']);
        }

        return $this->getPagingResult($select, $limits, 't');
    }
}