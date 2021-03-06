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
        //return new JsonResponse($this->get($manager)->{$method}($request->request->all(), $this->get('validator')));
        $post = json_decode($request->getContent(), true);
        return new JsonResponse($this->get($manager)->{$method}($post, $this->get('validator')));
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

    public function getParamsFromContent(Request $request, $keys)
    {
        $res = json_decode($request->getContent(), true);
        $pars = [];
        foreach ($keys as $key) {
            $pars[$key] = $res[$key];
        }
        return $res;
    }

    public function getFiltersFromContent ($filters, Request $request)
    {
        $pars = json_decode($request->getContent(), true);
        $res = [];
        foreach ($filters as $filter)  {
            if (isset($pars[$filter])) {
                $res[$filter] = $pars[$filter];
            }
        }
        return $res;
    }

    public function getLimitsFromContent($request)
    {
        $res = array();
        /*$res['start'] = $request->get('start', $defaultStart);
        $res['limit'] = $request->get('limit',  $defaultLimit);*/
        $res = json_decode($request->getContent(), true);
        $res['start'] = ($res['start']-1) * $res['limit'];
        return $res;
    }
}