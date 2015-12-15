<?php
/**
 * Created by PhpStorm.
 * User: nasibli
 * Date: 15.12.2015
 * Time: 11:17
 */

namespace AppBundle\Model;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="Pupils")
 */
class PupilsEntity
{
    private $_doctrine = null;
    public function __construct($doctrine)
    {
        $this->_doctrine = $doctrine;
        $this->teacher_pupils = new ArrayCollection();
    }

    /**
     * @ORM\OneToMany(targetEntity="TeacherPupilsEntity", mappedBy="pupils")
     */
    protected $teacher_pupils;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(
     *     message = "Имя обязательно для ввода"
     * )
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(
     *     message = "Почта обязательна для ввода"
     * )
     * @Assert\Email(
     *     message = "Введите корректно электронную почту"
     * )
     */
    protected $email;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThan(
     *     value = 0,
     *     message = "Выеберите корректно дату рождения"
     * )
     */
    protected $date_birth;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Choice(
     *     value = {1,2,3,4,5,6},
     *     message = "Выеберите корректно уровень"
     * )
     */
    protected $level_id;


    /*
     * Assert\IsTrue(message = "Данная почта уже используется, введите другое") - не работает (переделал)
     * */
    public function isUsedEmail($email)
    {
        return (bool)$this->_doctrine->getRepository('AppBundle:PupilsEntity')->findOneByEmail($email);
    }


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
     * Set name
     *
     * @param string $name
     *
     * @return Pupils
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Pupils
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set dateBirth
     *
     * @param integer $dateBirth
     *
     * @return Pupils
     */
    public function setDateBirth($dateBirth)
    {
        $this->date_birth = $dateBirth;

        return $this;
    }

    /**
     * Get dateBirth
     *
     * @return integer
     */
    public function getDateBirth()
    {
        return $this->date_birth;
    }

    /**
     * Set levelId
     *
     * @param integer $levelId
     *
     * @return Pupils
     */
    public function setLevelId($levelId)
    {
        $this->level_id = $levelId;

        return $this;
    }

    /**
     * Get levelId
     *
     * @return integer
     */
    public function getLevelId()
    {
        return $this->level_id;
    }
}