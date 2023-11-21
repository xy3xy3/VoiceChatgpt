[English README](README.en.md)

一个简单的demo，使用tts-1，whisper，与gpt3.5-turbo聊天！

修改模型可以在`inc.php`的`chat`函数修改

前端用`bootstarp`,`vue2`，`recorder`构建

前台上传`mp3`格式文件到php，调用`tts`得到`opus`音频保证效率

访问`clean.php`清理缓存文件

需要解除禁用函数`putenv`

# 部署教程
推荐php8.1
修改`.env.example`为`.env`，配置内部的openaikey和url（需要使用代理则修改url）