<?php
/**
 * Created by PhpStorm.
 * User: nasibli
 * Date: 15.12.2015
 * Time: 12:42
 */

namespace AppBundle\Model;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Teachers")
 */
class TeachersEntity
{

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
     * @ORM\Column(type="smallint")
     * @Assert\Choice(
     *    choices = {1,2},
     *    message = "Укажите корректно пол"
     * )
     */
    protected $gender;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank(
     *     message = "Телефон обязателен для ввода"
     * )
     */
    protected $phone;

    /**
     * @ORM\Column(type="integer")
     */
    protected $pupil_count;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $only_april;

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
     * @return Teachers
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
     * Set gender
     *
     * @param integer $gender
     *
     * @return Teachers
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return integer
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Teachers
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set pupilCount
     *
     * @param integer $pupilCount
     *
     * @return Teachers
     */
    public function setPupilCount($pupilCount)
    {
        $this->pupil_count = $pupilCount;

        return $this;
    }

    /**
     * Get pupilCount
     *
     * @return integer
     */
    public function getPupilCount()
    {
        return $this->pupil_count;
    }

    /**
     * Set onlyApril
     *
     * @param integer $onlyApril
     *
     * @return Teachers
     */
    public function setOnlyApril($onlyApril)
    {
        $this->only_april = $onlyApril;

        return $this;
    }

    /**
     * Get onlyApril
     *
     * @return integer
     */
    public function getOnlyApril()
    {
        return $this->only_april;
    }
}