<?php
namespace Common;

class Manager
{
    public function getErrors ($errors)
    {
        $res = [];
        foreach ($errors as $error) {
            $res[$error->getPropertyPath()] = $error->getMessage();
        }
        return $res;
    }
}