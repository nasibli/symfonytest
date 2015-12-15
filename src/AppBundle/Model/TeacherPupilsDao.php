<?php
/**
 * Created by PhpStorm.
 * User: nasibli
 * Date: 15.12.2015
 * Time: 14:26
 */

namespace AppBundle\Model;
use Common\Dao;
use Doctrine\ORM\Query\Expr;
class TeacherPupilsDao extends Dao
{
    public function __construct($doctrine)
    {
        $this->_doctrine = $doctrine;
    }

    protected $_doctrine;

    public function getExists($teacherId, $pupilIds)
    {
        $select = $this->_doctrine->getManager()->createQueryBuilder();
        $select->select('tp')
            ->from('AppBundle:TeacherPupilsEntity', 'tp')
            ->andWhere('tp.teacher_id=:teacher')->setParameter('teacher', $teacherId)
            ->andWhere('tp.pupil_id in (:pupil)')->setParameter('pupil', $pupilIds);
        return $this->getKeyValueResult($select, 'pupil_id', 'id');
    }

    public function getStats($teacherId)
    {
        $select = $this->_doctrine->getManager()->createQueryBuilder();
        $select->select('tp')
            ->from('AppBundle:TeacherPupilsEntity', 'tp')
            ->andWhere('tp.teacher_id=:teacher_id')->setParameter('teacher_id', $teacherId);
        $select->add('select', new Expr\Select('count (tp.id) as cnt, sum (tp.born_april) as apr_cnt'));
        return $select->getQuery()->getArrayResult();
    }
}