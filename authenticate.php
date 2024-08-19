<?php
/*******w******** 
    
    Name: Hang Xu   
    Date: 2024-08-12
    Description: Web Development 2---- PHP CRUD-based Content Management System (CMS)

****************/
if (!defined('ADMIN_LOGIN')) {
  define('ADMIN_LOGIN', 'admin');
}

if (!defined('ADMIN_PASSWORD')) {
  define('ADMIN_PASSWORD', 'admin');
}

if (
  !isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])
  || ($_SERVER['PHP_AUTH_USER'] != ADMIN_LOGIN)
  || ($_SERVER['PHP_AUTH_PW'] != ADMIN_PASSWORD)
) {

  header('HTTP/1.1 401 Unauthorized');
  header('WWW-Authenticate: Basic realm="Our Blog"');
  exit("Access Denied: Username and password required.");
}
?>