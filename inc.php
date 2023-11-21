<?php

require __DIR__ . '/vendor/autoload.php'; // remove this line if you use a PHP Framework.
use Orhanerday\OpenAi\OpenAi;
use MillionMile\GetEnv\Env;
try {
    Env::loadFile('.env');
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}
session_start();
$open_ai = new OpenAi(Env::get('API_KEY', ''));
$open_ai->setBaseURL(Env::get('API_URL', ''));
function json($msg, $code = 1, $extra = [])
{
    $data = ['code' => $code, 'msg' => $msg];
    //合并extra和data
    $data = array_merge($data, $extra);
    echo json_encode($data);
    exit();
}
function audioTotext($c_file)
{
    global $open_ai;
    $c_file = curl_file_create($c_file);
    $result = $open_ai->transcribe([
        "model" => "whisper-1",
        "file" => $c_file,
    ]);
    $d = json_decode($result, true);
    // if (!isset($d["text"])) {
    //     print_r($result);
    // }
    return isset($d["text"]) ? $d["text"] : null;
}
function speech($text, $voice)
{
    global $open_ai;
    if (empty($voice)) $voice = "echo";
    $result = $open_ai->speech([
        "model" => "tts-1",
        "input" => $text,
        "voice" => $voice,
        "response_format" => "opus"
    ]);
    return $result;
}
function chat($text)
{
    global $open_ai;
    $memory_entry = [
        "role" => "user",
        "content" => $text
    ];
    // 加入记忆
    if (isset($_SESSION['memory'])) {
        $tmp = $_SESSION['memory'];
    } else {
        $tmp = [];
    }
    $tmp[] = $memory_entry;
    $arr = [
        'model' => 'gpt-3.5-turbo-1106',
        'messages' => $tmp,
        'temperature' => 0.7,
        'max_tokens' => 1000,
    ];
    $complete = $open_ai->chat($arr);
    $d = json_decode($complete, true);
    if (isset($d['choices'][0]['message']['content'])) {
        $ai_msg = $d['choices'][0]['message']['content'];
        memory($text, $ai_msg);
        return $ai_msg;
    } else {
        // print_r($complete);
        return null;
    }
}
function memory($u, $a)
{
    if (empty($u) || empty($a)) return;
    // 初始化或检查是否已经存在 $_SESSION['memory']
    if (isset($_SESSION['memory'])) {
        // 只取最后的6对（如果有），然后加入新的
        $_SESSION['memory'][] = [
            "role" => "user",
            "content" => $u
        ];
        $_SESSION['memory'][] = [
            "role" => "assistant",
            "content" => $a
        ];
        // 截取最后的6对
        $_SESSION['memory'] = array_slice($_SESSION['memory'], -6);
    } else {
        // 如果 $_SESSION['memory'] 不存在，创建并添加第一对对话
        $_SESSION['memory'] = [
            [
                "role" => "user",
                "content" => $u
            ],
            [
                "role" => "assistant",
                "content" => $a
            ]
        ];
    }
}
function deleteDirectory($dir)
{
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }
}
