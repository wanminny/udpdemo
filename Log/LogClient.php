<?php
/**
 * Created by PhpStorm.
 * User: wanmin
 * Date: 16/12/25
 * Time: 下午12:49
 * UDP 日志客户端接口
 */

 class LogClient
 {
     public $client;

     public static $instance = null;


     public static function instance($host = '127.0.0.1',$port = 9000)
     {
         if(!self::$instance)
         {
             self::$instance = new \swoole_client(SWOOLE_UDP);

             self::$instance->connect($host, $port);
         }
         return self::$instance;
     }

     public static function send($msg = '')
     {
         $msg = ['oper_type' => 1,
             'oper_time' => date("Y-m-d H:i:s",time()),
             'oper_user' => "zhangsan",
             'oper_content' => 'content!!!!!!',
             'oper_datail' => "kkkkkkkkkkkkkkkkkkkkkkkkkkkkk"];
//         var_dump($msg);
        self::$instance->send(json_encode($msg));
     }

     public static function rec()
     {
         $data = self::$instance->recv();
//         var_dump($data);
     }
 }

// $instance = new LogClient();
// $instance->send("test");
    $i = 0;
    while($i<10) {
        $ins = LogClient::instance();
        LogClient::send();
        $i++;
    }
//    LogClient::rec();


