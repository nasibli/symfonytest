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

    public function __construct ($doctrine, $container)
    {
        $this->_doctrine = $doctrine;
        $this->_container = $container;
    }

    public function save($post, $validator)
    {
        $res = ['success' => true, 'errors' => []];

        $post['date_birth'] = strtotime($post['date_birth']);

        $pupil = $this->_container->get('pupilsDao')->setValues(new PupilsEntity($this->_doctrine), $post);
        $errors = $this->validate($pupil, $validator);
        if (count($errors) > 0) {
            $res['success'] = false;
            $res['errors'] = $errors;
            return $res;
        }

        $this->_container->get('pupilsDao')->update($pupil);
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
        if ($pupil->isUsedEmail($pupil->getEmail())) {
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

        $items = $this->_container->get('pupilsDao')->getAllForPaging($filters, $orders, $limits);
        foreach ($items['data'] as &$item) {
            $item['date_birth'] = DateTime::unixToString($item['date_birth'], DateTime::formatDDMMYYYYdot);
        }
        return $items;
    }
}