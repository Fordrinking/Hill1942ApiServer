<?php
/**
 * Created by kaidi
 * Date: 17-3-28
 */

class Server
{
    private $serv;

    public function __construct() {
        $this->serv = new swoole_http_server("127.0.0.1", 9501);
        $this->serv->set(array(
            'worker_num' => 8,
            'daemonize' => true,
        ));

        $this->serv->on('Start', array($this, 'onStart'));
        $this->serv->on('Connect', array($this, 'onConnect'));
        $this->serv->on('Receive', array($this, 'onReceive'));
        $this->serv->on('Close', array($this, 'onClose'));

        $this->serv->on('request', function ($request, $response) {
            $response->end("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>");
        });

        $this->serv->start();
    }

    public function onStart(swoole_server $serv ) {
        echo "Start\n";
    }

    public function onConnect(swoole_server $serv, $fd, $from_id ) {
        $serv->send($fd, "Hello {$fd}!" );
        //$serv->send()
    }

    public function onReceive( swoole_server $serv, $fd, $from_id, $data ) {
        echo "Get Message From Client {$fd}:{$data}\n";
        $serv->send($fd, $data);
        //$serv->send
    }

    public function onClose(swoole_server $serv, $fd, $from_id ) {
        echo "Client {$fd} close connection\n";
    }

    public function onWorkerStart(swoole_server $serv , $worker_id) {
        require_once "EntryCGI.php";

        (new EntryCGI($this->serv))->run();
    }
}
// 启动服务器
$server = new Server();