<?php
/**
 * Created by PhpStorm.
 * User: nasibli
 * Date: 14.12.2015
 * Time: 10:22
 */

namespace AppBundle\Model;

use AppBundle\Model\PupilsEntity;
use Common\Manager;
use Common\DateTime;

class PupilsManager extends Manager
{

    private $_doctrine       = null;
    private $_pupilsDao      = null;

    public function __construct ($doctrine, $pupilsDao)
    {
        $this->_doctrine = $doctrine;
        $this->_pupilsDao = $pupilsDao;
    }

    public function save($post, $validator)
    {
        $res = ['success' => true, 'errors' => []];

        $post['date_birth'] = strtotime($post['date_birth']);

        $pupil = $this->_pupilsDao->setValues(new PupilsEntity(), $post);
        $errors = $this->validate($pupil, $validator);
        if (count($errors) > 0) {
            $res['success'] = false;
            $res['errors'] = $errors;
            return $res;
        }

        $this->_pupilsDao->update($pupil);
        return $res;
    }

    private function validate(PupilsEntity $pupil, $validator)
    {
        $res = [];

        $errors = $validator->validate($pupil);
        if (count ($errors) > 0) {
            $res = $this->getErrors($errors);
            return $res;
        }
        if ($this->_pupilsDao->isUsedEmail($pupil->getEmail())) {
            $res['email'] = 'Данная электронная почта уже используется, введите другую почту';
            return $res;
        }
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

        $items = $this->_pupilsDao->getAllForPaging($filters, $orders, $limits);
        $levels = [1=>'A1', 2=>'A2', 3=>'B1', 4=>'B2', 5=>'A1', 6=>'A2'];
        foreach ($items['data'] as &$item) {
            $item['date_birth'] = DateTime::unixToString($item['date_birth'], DateTime::formatDDMMYYYYdot);
            $item['level_id'] = $levels[$item['level_id']];
        }
        return $items;
    }
}