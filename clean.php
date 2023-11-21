<?php
//用来清空缓存文件
include __DIR__ . '/inc.php'; // remove this line if you use a PHP Framework.

deleteDirectory("./upload_tmp");
deleteDirectory("./audio_result");