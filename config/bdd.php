<?php

define('DB_URL', "localhost");
define('DB_USER', "userGsb");
define('DB_PWD', "secret");
define('DB_NAME', "gsb_frais");
define('DB_DSN', "mysql:host=" . DB_URL . ";dbname=" . DB_NAME . ";charset=UTF8");

/**
 * Version Master/Slave
 *
define('DB_URL', "10.20.3.35");
define('DB_URL_SECOURS', "10.20.3.47");
define('DB_USER', "userGsb");
define('DB_USER_SECOURS', "userDegrade");
define('DB_PWD', "secret");
define('DB_NAME', "gsb_frais");
define('DB_DSN', "mysql:host=" . DB_URL . ";dbname=" . DB_NAME . ";charset=UTF8");
define('DB_DSN_SECOURS', "mysql:host=" . DB_URL_SECOURS . ";dbname=" . DB_NAME . ";charset=UTF8");
 */
/**
 * Version Master/Master
 *
define('DB_URL', ["10.20.3.35","10.20.3.47"]);
define('DB_USER', "userGsb");
define('DB_PWD', "secret");
define('DB_NAME', "gsb_frais");
 */
