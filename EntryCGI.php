<?php
/**
 * Created by kaidi
 * Date: 17-3-31
 * Desc:
 */

class EntryCGI
{

    private $server;

    public function __construct($server) {
        $this->server = $server;
    }

    public function run() {
        echo "hello";
    }
}
