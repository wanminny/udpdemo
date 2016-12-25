<?php
/**
 * Created by PhpStorm.
 * User: wanmin
 * Date: 16/12/25
 * Time: 上午11:16
 * UDP 日志服务器!
 */

class LogServer
{

    protected  $server;

    protected  $pdo;

    public function __construct($host = '127.0.0.1',$port = 9000)
    {

        date_default_timezone_set('Asia/Shanghai');

        $this->server = new \swoole_server($host,$port,SWOOLE_PROCESS,SWOOLE_SOCK_UDP);

        $config = include_once("./config.php");

        $this->server->set($config['udpconfig']);

        $this->server->on("start",array($this,"OnStart"));
        $this->server->on("close",array($this,"OnClose"));

        $this->server->on("connect",array($this,"OnConnect"));

        $this->server->on("packet",array($this,"OnPacket"));

        $this->server->on("WorkerStart",array($this,"OnWorkerStart"));

        $this->server->on("finish",array($this,"OnFinish"));

        $this->server->on("task",array($this,"Ontask"));

        $this->server->start();
    }


    public function OnStart($serv)
    {
        echo "onstart! \n";
    }


    public function OnClose($serv)
    {
        echo "onclose !\n";
    }

    public function onWorkerStart($serv,$worker_id)
    {
        echo "sever :onWorkerStart  \n";
        $istask = $serv->taskworker;
        if (!$istask) {
//            swoole_set_process_name("dora: worker {$worker_id}");
        } else {

            $dsn = 'mysql:dbname=wsd_log_test;host=127.0.0.1';
            $user = 'root';
            $password = '123456';

            try {
                $this->pdo = new PDO($dsn, $user, $password);
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
//            swoole_set_process_name("dora: task {$worker_id}");
        }
    }


    public function OnPacket(\swoole_server $server, $data, array $client_info)
    {
//        var_dump($data,$client_info);

        $rec = json_decode($data,true);
        $table = "ips_request_interface_log_".date("m");
        $sql = "insert into ".$table."(oper_type,oper_time,oper_user,oper_content,oper_detail) value(?,?,?,?,?)";
        $params = array_values($rec);
//        $fd = '';
        $arr['sql'] = $sql;
        $arr['params'] = $params;
//        $arr['fd'] = $fd;
        $server->task(json_encode($arr));

//        $server->send($client_info['server_socket'],$data);
    }


    public function OnTask(\swoole_server $serv, $task_id, $from_id, $data)
    {
//        var_dump($data);
        $data = json_decode($data,true);
        try{
            $stat = $this->pdo->prepare($data['sql']);
            $stat->execute($data['params']);
            return "ok";
        }
        catch (Exception $e)
        {
            $e->getMessage();
        }

    }

    public function OnFinish(\swoole_server $serv,$task_id, $data)
    {
        var_dump($data);
    }

    public function OnConnect($serv)
    {
        echo "on connect !\n";
    }
}

 $ser = new LogServer();
