<?php
/**
 * Created by PhpStorm.
 * User: nasibli
 * Date: 15.12.2015
 * Time: 14:04
 */

namespace AppBundle\Model;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Query\Expr;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="teacher_pupils")
 */
class TeacherPupilsEntity
{
    public function __construct($doctrine)
    {
        $this->pupils = new ArrayCollection();
        $this->_doctrine = $doctrine;
    }


    /**
     * @ORM\ManyToOne(targetEntity="PupilsEntity")
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

}