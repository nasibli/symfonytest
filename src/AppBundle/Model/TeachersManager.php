<?php
/**
 * Created by PhpStorm.
 * User: nasibli
 * Date: 11.12.2015
 * Time: 14:25
 */

namespace AppBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Model\TeachersEntity;
use Common\Manager;

class TeachersManager extends Manager
{
    private $_doctrine         = null;
    private $_teachersDao      = null;
    private $_teacherPupilsDao = null;

    public function __construct ($doctrine, $teachersDao, $teacherPupilsDao)
    {
        $this->_doctrine         = $doctrine;
        $this->_teachersDao      = $teachersDao;
        $this->_teacherPupilsDao = $teacherPupilsDao;
    }

    public function save ($post, $validator) {
        $res = ['success' => true, 'errors' => []];

        $teacher = $this->_teachersDao->setValues(new TeachersEntity(), $post);

        $errors = $this->validate($teacher, $validator);
        if (count($errors)>0) {
            $res['success'] = false;
            $res['errors']  = $errors;
            return $res;
        }

        $this->_teachersDao->update($teacher);
        return $res;
    }

    private function validate($teacher, $validator)
    {
        $res = [];
        $errors = $validator->validate($teacher);
        if (count ($errors) > 0) {
            $res = $this->getErrors($errors);
            return $res;
        }
        if ($this->_teachersDao->isUsedName($teacher->getName())) {
            $res['name'] = 'Имя уже используется, введите другое имя';
            return $res;
        }
        return $res;
    }

    public function getAllForPaging($filters, $orders, $limits)
    {
        return $this->_teachersDao->getAllForPaging($filters, $orders, $limits);
    }

    public function addPupils($post, $validator)
    {
        $res = ['success' => true, 'errors' => []];

        $post = json_decode($post['data_'], true);
        $post = $post['data'];

        $errors = $this->validatePupils($post, $validator);
        if (count($errors) > 0) {
            $res['success'] = false;
            $res['errors'] = $errors;
        }

        $pupilIds = [];
        foreach ($post['pupils'] as $pupil=>$value) {
            $pupilIds[] = $pupil;
        }
        $teacherId = $post['id'];
        $exPupils = $this->_teacherPupilsDao->getExists($teacherId, $pupilIds);

        $sqlInsert = $this->generatePupilsInsertSql($post['pupils'], $exPupils,  $teacherId);

        if (!empty($sqlInsert)) {
            $this->_doctrine->getEntityManager()->getConnection()->executeUpdate($sqlInsert);
        }

        $stat = $this->_teacherPupilsDao->getStats($teacherId);
        if ($stat) {
            $this->_teachersDao->updateStat($stat[0]['cnt'], $stat[0]['cnt']==$stat[0]['apr_cnt'] ? 1 : 0, $teacherId);
        }

        return $res;
    }

    private function validatePupils ($post, $validator)
    {
        $res = [];
        $intAssert = new Assert\Type(['type'=>'integer']);
        $errors = $validator->validate($post['id'], $intAssert);
        if (count($errors) > 0) {
            $res['id'] = 'Некорректно указан преподаватель';
        }
        $pupilIds = [];
        foreach ($post['pupils'] as $pupil=>$value) {
            $errors = $validator->validate($pupil, $intAssert);
            if (count($errors) > 0) {
                $res['pupils'] = 'Некорректно указан ученик';
                break;
            }
            $pupilIds[] = $pupil;
        }
        return $res;
    }

    private function generatePupilsInsertSql($pupils, $exPupils, $teacherId)
    {
        $sqlInsert = "Insert into teacher_pupils (teacher_id, pupil_id, born_april) VALUES ";
        $values = '';
        foreach ($pupils as $pupilId => $bornApril) {
            if (isset($exPupils[$pupilId])) {
                continue;
            }
            $values .= $values ? ", \n($teacherId, $pupilId, $bornApril)" : "\n($teacherId, $pupilId, $bornApril)";
        }
        return empty($values) ? '' : $sqlInsert . $values;
    }

}