<?php
/**
 * Created by PhpStorm.
 * User: nasibli
 * Date: 15.12.2015
 * Time: 10:43
 */

namespace AppBundle\Model;
use Common\Dao;

class PupilsDao extends Dao
{
    public function __construct($doctrine)
    {
        $this->_doctrine = $doctrine;
    }

    protected $_doctrine;

    public function setValues(PupilsEntity $pupil, $data)
    {
        $pupil->setName($data['name']);
        $pupil->setEmail($data['email']);
        $pupil->setDateBirth($data['date_birth']);
        $pupil->setLevelId($data['level_id']);
        return $pupil;
    }

    public function getAllForPaging ($filters, $orders, $limits) {
        $select = $this->_doctrine->getManager()->createQueryBuilder();
        $select->select('p')
            ->from('AppBundle:PupilsEntity', 'p');

        if (isset($filters['search']) && !empty($filters['search'])) {
            $select->andWhere('p.name like :search')->setParameter('search', $filters['search'].'%');
        }

        if (isset($filters['date_birth_from']) && $filters['date_birth_from']) {
            $select->andWhere('p.date_birth >= :date_birth_from')->setParameter('date_birth_from', $filters['date_birth_from']);
        }

        if (isset($filters['date_birth_to']) && $filters['date_birth_to']) {
            $select->andWhere('p.date_birth <= :date_birth_to')->setParameter('date_birth_to', $filters['date_birth_to']);
        }
        if ($orders) {
            $select->orderBy('p.'.$orders['sort'], $orders['dir']);
        }
        if (isset($filters['id']) && intval($filters['id']) != 0) {
            $select->innerJoin('p.teacher_pupils', 'tp');
            $select->andWhere('tp.teacher_id=:id')->setParameter('id',$filters['id']);
        }
        return $this->getPagingResult($select, $limits, 'p');
    }

    public function isUsedEmail($email)
    {
        return (bool)$this->_doctrine->getRepository('AppBundle:PupilsEntity')->findOneByEmail($email);
    }
}