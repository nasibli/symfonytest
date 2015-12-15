<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Common\AppController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;



class TeachersController extends AppController
{
    /**
     * @Route("/teachers/save")
     */
    public function saveAction(Request $request)
    {
        return $this->submitAjax('teacher','save',$request);
    }

    /**
     * @Route("/teachers/add-pupils")
     */
    public function addPupils(Request $request)
    {
        return $this->submitAjax('teacher','addPupils',$request);
    }

    /**
     * @Route("/teachers/list")
     */
    public function listAction(Request $request)
    {
        $req = $request->request;
        $filters = $this->getFiltersFromPost(array('search', 'only_april'),$req);
        $orders = $this->getOrdersFromPost('id', 'asc',$req);
        $limits = $this->getLimitsFromPost(0,50,$req);
        $res = $this->get('teacher')->getAllForPaging($filters, $orders, $limits);
        return new JsonResponse( array('res' => $res));
    }

}
