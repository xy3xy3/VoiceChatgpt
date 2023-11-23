[English README](README.en.md)

一个简单的demo，使用tts-1，whisper，与gpt3.5-turbo聊天！

前端用`bootstarp`,`vue2`，`recorder`构建

前台支持选择浏览器语音转文字（低延迟）或者调用openai的whisper（高精度）

前台上传`mp3`格式文件到php，调用`tts`得到`opus`音频保证效率

访问`clean.php`清理缓存文件

修改模型可以在`/system/inc.php`的`chat`函数修改

# 更新内容
去除composer依赖，改为内置Openai库，修改自https://github.com/orhanerday/open-ai，加入了tts相关函数

改为自定义简单函数获取`.env`变量

# 部署教程

推荐php8.1

修改`.env.example`为`.env`，配置内部的openaikey和url（需要使用代理则修改url）