<?php

$config = array(
    //path to time daemon
    'time_daemon_path' => '/path/to/daemon/d.py',
    //paths to daemon log & pid files
    'projects_log_file' => '/path/to/simtrd-p.log',
    'projects_pid_file' => '/path/to/simtrd-p/.simtrd-p.pid',
    'finished_log_file' => '/path/to/simtrd-pf.log',
    'finished_pid_file' => '/path/to/simtrd-pf/.simtrd-pf.pid',
    //connection with redis server
    'database_dsn' => 'redis://localhost:6379',
    //parameters to connect with mysql server
    'db_server' => 'localhost:3306',
    'db_username' => 'USER',
    'db_password' => 'PASS',
    //gid & uid for process
    'gid' => 60009,
    'uid'=> 60009,
);

?>
