<?php

namespace AppBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Query\Expr;
use Doctrine\Common\Collections\ArrayCollection;
use Lib;

/**
 * @ORM\Entity
 * @ORM\Table(name="teacher_pupils")
 */
class TeacherPupils extends Lib\Entity
{
    public function __construct($doctrine)
    {
        $this->pupils = new ArrayCollection();
        $this->_doctrine = $doctrine;
    }


    /**
     * @ORM\ManyToOne(targetEntity="Pupils")
     * @ORM\JoinColumn(name="pupil_id", referencedColumnName="id")
     */
    protected $pupils;

    private $_doctrine = null;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $teacher_id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $pupil_id;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $born_april;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set teacherId
     *
     * @param integer $teacherId
     *
     * @return TeacherPupils
     */
    public function setTeacherId($teacherId)
    {
        $this->teacher_id = $teacherId;

        return $this;
    }

    /**
     * Get teacherId
     *
     * @return integer
     */
    public function getTeacherId()
    {
        return $this->teacher_id;
    }

    /**
     * Set pupilId
     *
     * @param integer $pupilId
     *
     * @return TeacherPupils
     */
    public function setPupilId($pupilId)
    {
        $this->pupil_id = $pupilId;

        return $this;
    }

    /**
     * Get pupilId
     *
     * @return integer
     */
    public function getPupilId()
    {
        return $this->pupil_id;
    }

    /**
     * Set bornApril
     *
     * @param integer $bornApril
     *
     * @return TeacherPupils
     */
    public function setBornApril($bornApril)
    {
        $this->born_april = $bornApril;

        return $this;
    }

    /**
     * Get bornApril
     *
     * @return integer
     */
    public function getBornApril()
    {
        return $this->born_april;
    }

    public function getExists($teacherId, $pupilIds)
    {
        $select = $this->_doctrine->getManager()->createQueryBuilder();
        $select->select('tp')
            ->from('AppBundle:TeacherPupils', 'tp')
            ->andWhere('tp.teacher_id=:teacher')->setParameter('teacher', $teacherId)
            ->andWhere('tp.pupil_id in (:pupil)')->setParameter('pupil', $pupilIds);
        return $this->getKeyValueResult($select, 'pupil_id', 'id');
    }

    public function getStats($teacherId)
    {
        $select = $this->_doctrine->getManager()->createQueryBuilder();
        $select->select('tp')
            ->from('AppBundle:TeacherPupils', 'tp')
            ->andWhere('tp.teacher_id=:teacher_id')->setParameter('teacher_id', $teacherId);
        $select->add('select', new Expr\Select('count (tp.id) as cnt, sum (tp.born_april) as apr_cnt'));
        return $select->getQuery()->getArrayResult();
    }

    public function getMaxPair() {
        $sql = "Select  count(`tg`.pupil_id) as pupil_count , tg.teacher_id, tg.teacher_id2 from
            (Select p.teacher_id, p1.teacher_id as teacher_id2, p.pupil_id
            from
            teacher_pupils as p
            inner join teacher_pupils as p1 on p.pupil_id = p1.pupil_id and p.teacher_id != p1.`teacher_id`) as tg
            group by tg.teacher_id, tg.teacher_id2
            order by pupil_count desc
            limit 1";
        $query = $this->_doctrine->getEntityManager()->createNativeQuery($sql);

        return $query->getArrayResult();
    }

    public function getMaxPairPupils($teacher1, $teacher2) {
        $sql = "Select a.pupil_id, a.teacher_id, b.teacher_id as teacher_id2, p.name from teacher_pupils as a
            inner join teacher_pupils as b on b.pupil_id=a.pupil_id and b.`teacher_id` = $teacher1
            inner join pupils as p on a.`pupil_id` = p.id
            where a.`teacher_id` = $teacher2";
        $query = $this->_doctrine->getEntityManager()->createNativeQuery($sql);
        return $query->getArrayResult();
    }
}
