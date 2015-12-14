<?php
/**
 * Created by PhpStorm.
 * User: nasibli
 * Date: 14.12.2015
 * Time: 10:22
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Pupils;
use Lib\Manager;
use Lib\DateTime;

class PupilsManager extends Manager
{
    private $_pupilsEntity = null;
    private $_doctrine       = null;

    public function __construct ($doctrine)
    {
        $this->_pupilsEntity = new Pupils($doctrine);
        $this->_doctrine = $doctrine;
    }

    public function save($post, $validator)
    {
        $res = ['success' => true, 'errors' => []];

        $pupil = new Pupils($this->_doctrine);

        $pupil->setName($post['name']);
        $pupil->setEmail($post['email']);
        $pupil->setDateBirth(strtotime($post['date_birth']));
        $pupil->setLevelId($post['level_id']);

        $errors = $validator->validate($pupil);
        if (count ($errors) > 0) {
            $res['success'] = false;
            $res['errors'] = $this->getErrors($errors);
            return $res;
        }
        if ($pupil->isUsedEmail($post['email'])) {
            $res['success'] = false;
            $res['errors']['email'] = 'Данная электронная почта уже используется, введите другую почту';
            return $res;
        }
        $em = $this->_doctrine->getManager();
        $em->persist($pupil);
        $em->flush();
        return $res;
    }

    public function getAllForPaging($filters, $orders, $limits)
    {
        if (isset($filters['date_birth_from']) && !empty($filters['date_birth_from'])) {
            $filters['date_birth_from'] = strtotime(DateTime::dateRangeBegToMySql($filters['date_birth_from']));
        }
        if (isset($filters['date_birth_to']) && !empty($filters['date_birth_to'])) {
            $filters['date_birth_to'] =  strtotime(DateTime::dateRangeEndToMySql($filters['date_birth_to']));
        }

        $items = $this->_pupilsEntity->getAllForPaging($filters, $orders, $limits);
        foreach ($items['data'] as &$item) {
            $item['date_birth'] = DateTime::unixToString($item['date_birth'], DateTime::formatDDMMYYYYdot);
        }
        return $items;
    }
}