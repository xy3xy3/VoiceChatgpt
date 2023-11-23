<?php
define("ROOT", dirname(__DIR__));
define("SYSTEM", __DIR__);
include(SYSTEM . "/function.php");
include(SYSTEM . "/OpenAi.php");
include(SYSTEM . "/Url.php");
$conf = loadEnvVariables(ROOT . "/.env");
if (empty($conf['API_KEY'])||empty($conf['API_URL'])) {
    exit(".env配置不完整");
}
session_start();
$open_ai = new OpenAi($conf['API_KEY']);
$open_ai->setBaseURL($conf['API_URL']);
