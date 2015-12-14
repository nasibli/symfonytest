<?php

namespace AppBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Query\Expr;
use Doctrine\Common\Collections\ArrayCollection;
use Lib;
/**
 * @ORM\Entity
 * @ORM\Table(name="Pupils")
 */
class Pupils extends Lib\Entity
{
    private $_doctrine = null;
    public function __construct($doctrine)
    {
        $this->_doctrine = $doctrine;
        $this->teacher_pupils = new ArrayCollection();
    }

    /**
     * @ORM\OneToMany(targetEntity="TeacherPupils", mappedBy="pupils")
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
        return (bool)$this->_doctrine->getRepository('AppBundle:Pupils')->findOneByEmail($email);
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

    public function getAllForPaging ($filters, $orders, $limits) {
        $select = $this->_doctrine->getManager()->createQueryBuilder();
        $select->select('p')
            ->from('AppBundle:Pupils', 'p');

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
}
