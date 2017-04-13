<?php

namespace app\http\controllers;

use blink\core\Object;
use blink\http\Request;
use app\dao\Database;

class ApiController extends Object
{
    public function upload(Request $request)
    {
        $longtitude = $request->params->get('longitude');
        $latitude = $request->params->get('latitude');


        $db = Database::get();

        $db->insert("tbTest", array(
            "sOpenId" => md5("user" . time()),
            "sLongtitude" => $longtitude,
            "sLatitude" => $latitude
        ));

        return [
            "code" => 0,
            "msg" => "ok"
        ];
    }
}
