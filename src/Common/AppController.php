<?php
/**
 * Created by PhpStorm.
 * User: nasibli
 * Date: 14.12.2015
 * Time: 9:34
 */

namespace Common;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
class AppController extends Controller
{
    public function submitAjax($manager, $method, $request)
    {
        return new JsonResponse($this->get($manager)->{$method}($request->request->all(), $this->get('validator')));
    }

    public function getFiltersFromPost($filterNames, $request)
    {
        $res = array();
        foreach ($filterNames as $filterName) {
            $res[$filterName] = $request->get($filterName, null);
        }
        return $res;

    }

    public function getOrdersFromPost($defaultSort, $defaultDir, $request)
    {
        $res = array();
        $res['sort'] = $request->get('sort', $defaultSort);
        $res['dir']  = $request->get('dir',  $defaultDir);
        return $res;
    }

    public function getLimitsFromPost($defaultStart, $defaultLimit, $request)
    {
        $res = array();
        $res['start'] = $request->get('start', $defaultStart);
        $res['limit'] = $request->get('limit',  $defaultLimit);
        return $res;
    }
}