<?php

namespace app\http\controllers;

use blink\core\Object;
use blink\http\Request;
use app\dao\Database;
use \PDO;

class ApiController extends Object
{
    public function upload(Request $request)
    {
        $openid    = $request->params->get('openid');
        $longitude = $request->params->get('longitude');
        $latitude  = $request->params->get('latitude');

        $gHash = \GeoHash::encode($longitude, $latitude);

        if ($openid && strlen($openid) == 32) {
            $db = Database::get();

            $stmt = $db->prepare('INSERT INTO tbTest(sOpenId, sLongitude, sLatitude, sGHash) ' .
                'VALUES(:openid, :longitude, :latitude, :ghash)' .
                'ON DUPLICATE KEY UPDATE sLongitude=:lnt, sLatitude=:lat, sGHash=:ghs');

            $stmt->bindParam(':openid',     $openid,    PDO::PARAM_STR);
            $stmt->bindParam(':longitude',  $longitude, PDO::PARAM_STR);
            $stmt->bindParam(':latitude',   $latitude,  PDO::PARAM_STR);
            $stmt->bindParam(':ghash',      $gHash,     PDO::PARAM_STR);
            $stmt->bindParam(':lnt',        $longitude, PDO::PARAM_STR);
            $stmt->bindParam(':lat',        $latitude,  PDO::PARAM_STR);
            $stmt->bindParam(':ghs',        $gHash,     PDO::PARAM_STR);
            $stmt->execute();

            return [
                "code" => 0,
                "msg" => "ok"
            ];
        } else {
            return [
                "code" => -1,
                "msg" => "openid is wrong"
            ];
        }



    }
}
