<?php 

    require 'pdo.class.php';
    require 'synchro.server.class.php';

    $pdo = new PDOClass("asasas", "nicolas", "asmonaco");
    $server = new Server($pdo, 1, "/etc/apache2/sites-available", "/var/log/apache2/error.log");

    $server->sendApps();
    $server->sendVirtualHost();    
    $server->sendApacheErrorsLogs();   

 ?>