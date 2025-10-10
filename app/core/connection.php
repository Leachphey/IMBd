<?php

if ($_SERVER['SERVER_NAME'] == "localhost") {
    define('DBUSER', "root");
    define('DBPASS', "");
    define('DBNAME', "imbd_db_local");
    define('DBHOST', "localhost");
} else {
    define('DBUSER', "root");
    define('DBPASS', "");
    define('DBNAME', "imbd_db");
    define('DBHOST', "localhost");
}
