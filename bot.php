#!/usr/bin/php -q
<?php
if(!isset($argc))
    die();

// change the following paths if necessary
$yii=dirname(__FILE__).'/protected/vendor/yiisoft/yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/bot.php';

// remove the following lines when in production mode
define('YII_ENABLE_ERROR_HANDLER', false);
define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
require_once($yii);

$loader = require(__DIR__ . '/protected/vendor/autoload.php');
Yii::$classMap = $loader->getClassMap();

error_reporting(E_ALL);
ini_set('display_errors', 'on');
    function onShutdownHandler()
    {
        // 1. error_get_last() returns NULL if error handled via set_error_handler
        // 2. error_get_last() returns error even if error_reporting level less then error
        $error = error_get_last();

        // Fatal errors
        $errorsToHandle = E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING;

        if (!is_null($error))# && ($error['type'] & $errorsToHandle))
        {
            $message = 'FATAL ERROR: ' . $error['message'];
            if (!empty($error['file'])) $message .= ' (' . $error['file'] . ' :' . $error['line']. ')';
            echo $message.PHP_EOL;

        }
    }
        register_shutdown_function('onShutdownHandler');

// creating and running console application
Yii::createConsoleApplication($config)->run();
