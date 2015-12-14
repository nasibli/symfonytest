<?php
/**
 * Created by PhpStorm.
 * User: nasibli
 * Date: 11.12.2015
 * Time: 14:25
 */

namespace AppBundle\Manager;

use AppBundle\Entity\TeacherPupils;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Teachers;
use Lib\Manager;

class TeachersManager extends Manager
{

    private $_teachersEntity = null;
    private $_doctrine       = null;

    public function __construct ($doctrine)
    {
        $this->_teachersEntity = new Teachers($doctrine);
        $this->_doctrine = $doctrine;
    }

    public function save ($post, $validator) {
        $res = ['success' => true, 'errors' => []];

        $teacher = new Teachers($this->_doctrine);
        $teacher->setName($post['name']);
        $teacher->setPhone($post['phone']);
        $teacher->setGender($post['genderId']);
        $teacher->setPupilCount(0);
        $teacher->setOnlyApril(0);

        $errors = $validator->validate($teacher);
        if (count ($errors) > 0) {
            $res['success'] = false;
            $res['errors'] = $this->getErrors($errors);
            return $res;
        }
        if ($teacher->isUsedName($post['name'])) {
            $res['success'] = false;
            $res['errors']['name'] = 'Имя уже используется, введите другое имя';
            return $res;
        }
        $em = $this->_doctrine->getManager();
        $em->persist($teacher);
        $em->flush();
        return $res;
    }

    public function getAllForPaging($filters, $orders, $limits)
    {
        return $this->_teachersEntity->getAllForPaging($filters, $orders, $limits);
    }

    public function addPupils($post, $validator)
    {
        $res = ['success' => true, 'errors' => []];

        $post = json_decode($post['data_'], true);
        $post = $post['data'];

        $intAssert = new Assert\Type(['type'=>'integer']);
        $errors = $validator->validate($post['id'], $intAssert);
        if (count($errors) > 0) {
            $res['success'] = false;
            $res['errors']['id'] = 'Некорректно указан преподаватель';
        }
        $pupilIds = [];
        foreach ($post['pupils'] as $pupil=>$value) {
            $errors = $validator->validate($pupil, $intAssert);
            if (count($errors) > 0) {
                $res['success'] = false;
                $res['errors']['pupils'] = 'Некорректно указан ученик';
                break;
            }
            $pupilIds[] = $pupil;
        }
        if (!$res['success']) {
            return $res;
        }

        $teacherId = $post['id'];
        $teacherPupils = new TeacherPupils($this->_doctrine);
        $exPupils = $teacherPupils->getExists($teacherId, $pupilIds);
        $res['pupils'] = $exPupils;
        $sqlInsert = "Insert into teacher_pupils (teacher_id, pupil_id, born_april) VALUES ";
        $values = '';
        foreach ($post['pupils'] as $pupilId => $bornApril) {
            if (isset($exPupils[$pupilId])) {
                continue;
            }
            $values .= $values ? ", \n($teacherId, $pupilId, $bornApril)" : "\n($teacherId, $pupilId, $bornApril)";
        }

        if ($values) {
            $this->_doctrine->getEntityManager()->getConnection()->executeUpdate($sqlInsert . $values);
        }

        $stat = $teacherPupils->getStats($teacherId);

        if ($stat) {
            $stat = $stat[0];
            $em = $this->_doctrine->getManager();
            $teacher = $em->getRepository('AppBundle:Teachers')->find($teacherId);
            $teacher->setPupilCount($stat['cnt']);
            $teacher->setOnlyApril($stat['cnt']==$stat['apr_cnt'] ? 1 : 0);
            $em->flush();
        }

        return $res;
    }

}