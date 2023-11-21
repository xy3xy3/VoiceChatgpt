<?php
include __DIR__ . '/inc.php'; // remove this line if you use a PHP Framework.

// $act = isset($_GET['act']) ? $_GET['act'] : '';
// 处理接收的音频文件
// 接收 POST 请求的原始数据
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['text']) && !empty($_POST['text'])) {
    // 获取chat对话
    $ai_msg =  chat($_POST['text']);
    if (empty($ai_msg)) {
        json("ai回答失败");
    }
    $audioData = speech($ai_msg, $_POST['voice']);
    if (empty($audioData)) {
        json("ai回答失败");
    }
    //保存.wav到save
    $dir = '/audio_result/' . md5($ai_msg) . ".opus";
    $saveDirectory = __DIR__ . $dir;
    file_put_contents($saveDirectory, $audioData);
    json($dir, 0, ['ai_msg' => $ai_msg]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['audio'])) {
    $audioFile = $_FILES['audio'];
    $tempFilePath = $audioFile['tmp_name'];
    $tmpfile = __DIR__ . "/upload_tmp/" . md5($tempFilePath) . ".mp3";
    copy($tempFilePath, $tmpfile);
    // 语音转文字
    $text = audioTotext($tmpfile);
    if (empty($text)) {
        json("语音转文字失败");
    }
    // 获取chat对话
    $ai_msg =  chat($text);
    if (empty($ai_msg)) {
        json("ai回答失败");
    }
    $audioData = speech($ai_msg, $_POST['voice']);
    if (empty($audioData)) {
        json("ai回答失败");
    }
    //保存.wav到save
    $dir = '/audio_result/' . md5($ai_msg) . ".opus";
    $saveDirectory = __DIR__ . $dir;
    file_put_contents($saveDirectory, $audioData);
    json($dir, 0, ['ai_msg' => $ai_msg]);
} else {
    json("没收到文件且没有文本");
}
