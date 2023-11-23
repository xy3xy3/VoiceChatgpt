<?php
//用来清空缓存文件
include __DIR__ . '/system/inc.php'; 

deleteDirectory("./upload_tmp");
deleteDirectory("./audio_result");