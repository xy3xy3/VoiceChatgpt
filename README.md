[English README](README.en.md)

# 项目简介

这个项目是一个简单的 demo，它使用了 TTS-1、Whisper 和 GPT-3.5-turbo 来进行对话交互。

## 技术栈

- 前端使用 `bootstarp`、`vue2` 和 `recorder` 进行构建。
- 前台支持选择浏览器语音转文字（低延迟）或者调用 OpenAI 的 Whisper（高精度）。
- 前台可以上传 `mp3` 格式文件到 PHP，通过调用 TTS 得到 `opus` 音频以保证效率。
- 访问 `clean.php` 可以清理缓存文件。

## 修改与更新

- 去除了 Composer 依赖，改为内置修改版的 [OpenAI 库](https://github.com/orhanerday/open-ai)，同时加入了与 TTS 相关的函数。
- 现在使用自定义简单函数获取 `.env` 变量。

# 部署教程

为了最佳效果，推荐使用 PHP 8.1。

1. `.env.example` 修改为 `.env`，并配置其中的 OpenAI Key 和 URL（如果需要使用代理，则修改 URL）。

2.必须使用https否则无法录音