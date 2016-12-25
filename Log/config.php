<?php
/**
 * Created by PhpStorm.
 * User: wanmin
 * Date: 16/12/25
 * Time: ÉÏÎç11:19
 */

return [
    "udpconfig" => [
            'worker_num' => 2,
            'task_worker_num' => 4,
            'daemonize' => false,
            'log_file' => '/tmp/swoole_udp_server.log',
         ],
    'mysql' => [
        'host'=>"127.0.0.1",
        'port' =>"3306",
        'username' => "root",
        "password"=>'123456'
    ],

];
