<?php

namespace app\http\controllers;

use blink\core\Object;
use blink\http\Request;

class ApiController extends Object
{
    public function upload(Request $request)
    {
        $type = $request->params->get('type'); // 获取 Query 参数 type
        $params = $request->params->all(); // 获取所有 Query 参数

        $name = $request->body->get('name'); // 获取 Request Body 的 name 参数
        $body = $request->body->all(); // 获取整个 Request Body

        return [
            "type" => $type,
            "params" => $params,
            "name" => $name,
            "body" => $body
        ];
    }
}
