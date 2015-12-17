<?php
/**
 * Created by PhpStorm.
 * User: nasibli
 * Date: 14.12.2015
 * Time: 10:16
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Common\AppController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class PupilsController extends AppController
{
    /**
     * @Route("/pupils/save")
     */
    public function saveAction(Request $request)
    {
        return $this->submitAjax('pupil','save',$request);
    }

    /**
     * @Route("/pupils/list")
     */
    public function listAction(Request $request)
    {
        $req = $request->request;
        //$filters = $this->getFiltersFromPost(['search', 'date_birth_from', 'date_birth_to', 'id'],$req);
        /*$orders = $this->getOrdersFromPost('id', 'DESC',$req);
        $limits = $this->getLimitsFromPost(0,50,$req);*/
        $filters = $this->getFiltersFromContent(['search', 'date_birth_from', 'date_birth_to', 'id'], $request);
        $orders = $this->getParamsFromContent($request, ['sort','dir']);
        $limits = $this->getLimitsFromContent($request);
        $res = $this->get('pupil')->getAllForPaging($filters, $orders, $limits);
        return new JsonResponse( array('res' => $res));
    }
}