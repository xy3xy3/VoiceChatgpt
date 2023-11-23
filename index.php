<?php
include __DIR__ . '/system/inc.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI语音对话</title>
    <link href="https://cdn.bootcdn.net/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.bootcdn.net/ajax/libs/vue/2.6.14/vue.min.js"></script>
    <script src="./recorder.mp3.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/axios/0.21.1/axios.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/layer/3.1.1/layer.js"></script>

    <style>
        body {
            background-image: url(https://imgapi.cn/api.php?fl=dongman);
        }

        /* 移动设备样式 */
        @media only screen and (max-width: 600px) {
            body {
                background-image: url(https://imgapi.cn/api.php?zd=mobile&fl=dongman);
                background-size: cover;
            }
        }
    </style>
</head>

<body>
    <div id="app" class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3 col-sm-12">
                <div class="card opacity-75">
                    <div class="card-body">
                        <h5 class="card-title">语音对话chatgpt</h5>
                        <div v-if="status=='load'">
                            <div class="spinner-grow" role="status">
                                <span class="visually-hidden">加载中...</span>
                            </div>
                            需要一些时间进行思考
                        </div>

                        <div v-else-if="status=='play'">
                            <div class="spinner-grow" role="status">
                                <span class="visually-hidden">加载中...</span>
                            </div>
                            说话中
                        </div>
                        <div v-else-if="status=='finish'">
                            <div class="row">
                                <div class="d-flex justify-content-center">
                                    <button v-if="cur=='stop'" class="btn btn-primary" @click="startRecording">开始说话</button>
                                    <button v-else class="btn btn-danger" @click="stopRecording">结束说话</button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="type">语音识别</label>
                                <select class="form-select" id="type" v-model="type">
                                    <option value="1">浏览器识别——低延迟</option>
                                    <option value="2">whisper识别——高精度</option>
                                </select>
                            </div>
                            <div class="form-group" v-if="type==1">
                                <label for="language">语言类型</label>
                                <select class="form-select" id="language" v-model="language">
                                    <option value="zh-CN">中文普通话</option>
                                    <option value="zh-HK">中文白话</option>
                                    <option value="en-US">英文</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="voice">音色选择</label>
                                <select class="form-control" id="voice" v-model="voice">
                                    <option value="alloy">Alloy</option>
                                    <option value="echo">Echo</option>
                                    <option value="fable">Fable</option>
                                    <option value="onyx">Onyx</option>
                                    <option value="nova">Nova</option>
                                    <option value="shimmer">Shimmer</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="fixed-bottom">
        <div class="text-center text-light p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            &copy; 2023 xy3 |
            <a class="text-light" href="https://github.com/xy3xy3/VoiceChatgpt" target="_blank">VoiceChatgpt</a>
        </div>
    </footer>


    <script>
        let app = new Vue({
            el: '#app',
            data: {
                status: "finish",
                cur: "stop",
                msg: null,
                rec: null, //mp3录制
                language: "zh-CN",
                recognition: null, //浏览器识别
                recognitionText: "", // 存储语音识别的文本结果
                chunks: [],
                socket: null,
                type: 2,
                voice: "echo" // 更改为单选框的绑定属性

            },
            watch: {
                type(newType) {
                    this.saveUserConfig();
                },
                language(newLanguage) {
                    this.recognition.lang = newLanguage; // 设置语言，可以根据需要更改
                    this.saveUserConfig();
                },
                voice(newVoice) {
                    this.saveUserConfig();
                }
            },
            methods: {
                saveUserConfig() {
                    const userConfig = {
                        type: this.type,
                        language: this.language,
                        voice: this.voice
                    };
                    localStorage.setItem('userConfig', JSON.stringify(userConfig));
                },
                startRecording() {
                    this.cur = 'start';
                    if (this.type == 1) {
                        this.recognitionText = "";
                        this.recognition.start();
                    } else {
                        this.rec.start();
                    }
                },
                stopRecording() {
                    vm = this;
                    this.cur = 'stop';
                    if (this.type == 1) {
                        this.recognition.stop();
                        console.log("语音识别结果：" + vm.recognitionText);
                        if (vm.recognitionText == "") {
                            vm.status = 'finish';
                            return layer.alert("语音识别失败");
                        }
                        vm.status = 'load';
                        let formData = new FormData();
                        formData.append('text', vm.recognitionText); // 提交用户选择的音色
                        formData.append('voice', vm.voice); // 提交用户选择的音色
                        $.ajax({
                            url: '/chat.php',
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                vm.loading = false;
                                if (response.code === 0) {
                                    // Play audio if code is 0
                                    vm.status = 'play';
                                    let audio = new Audio(response.msg);
                                    audio.play();
                                    // 在音频播放结束时触发相应的操作
                                    audio.addEventListener('ended', function() {
                                        vm.status = 'finish';
                                        // 在这里可以执行其他你想要的操作
                                    });
                                } else {
                                    vm.status = 'finish';
                                    // Show popup if code is not 0
                                    layer.alert(response.msg);
                                }
                            },
                            error: function(error) {
                                vm.loading = false;
                                console.error('Error sending audio to server:', error);
                            },
                            dataType: 'json' // Specify the expected data type as JSON
                        });
                        vm.recognitionText = "";
                    } else {
                        this.rec.stop(function(blob, duration) {
                            vm.status = 'load';
                            let formData = new FormData();
                            formData.append('audio', blob, "recorder.mp3");
                            formData.append('voice', vm.voice); // 提交用户选择的音色
                            $.ajax({
                                url: '/chat.php',
                                type: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    vm.loading = false;
                                    if (response.code === 0) {
                                        // Play audio if code is 0
                                        vm.status = 'play';
                                        let audio = new Audio(response.msg);
                                        audio.play();
                                        // 在音频播放结束时触发相应的操作
                                        audio.addEventListener('ended', function() {
                                            vm.status = 'finish';
                                            // 在这里可以执行其他你想要的操作
                                        });
                                    } else {
                                        vm.status = 'finish';
                                        // Show popup if code is not 0
                                        layer.alert(response.msg);
                                    }
                                },
                                error: function(error) {
                                    vm.loading = false;
                                    console.error('Error sending audio to server:', error);
                                },
                                dataType: 'json' // Specify the expected data type as JSON
                            });
                        });
                    }
                },
            },
            mounted() {
                if (localStorage.getItem('userConfig')) {
                    const userConfig = JSON.parse(localStorage.getItem('userConfig'));
                    this.type = userConfig.type || this.type;
                    this.language = userConfig.language || this.language;
                    this.voice = userConfig.voice || this.voice;
                }
                set = {
                    type: "mp3", //输出类型：mp3,wav等，使用一个类型前需要先引入对应的编码引擎
                    bitRate: 16, //比特率，必须是数字 wav(位):16、8，MP3(单位kbps)：8kbps时文件大小1k/s，16kbps 2k/s，录音文件很小
                    sampleRate: 48000 //采样率，必须是数字，wav格式（8位）文件大小=sampleRate*时间；mp3此项对低比特率文件大小有影响，高比特率几乎无影响。
                    //wav任意值，mp3取值范围：48000, 44100, 32000, 24000, 22050, 16000, 12000, 11025, 8000
                }
                this.rec = Recorder(set);
                this.rec.open(function() { //打开麦克风授权获得相关资源
                    layer.msg('麦克风权限已获取');
                }, function(msg, isUserNotAllow) { //用户拒绝未授权或不支持

                    layer.msg((isUserNotAllow ? "UserNotAllow，" : "") + "无法录音:" + msg);
                    console.log((isUserNotAllow ? "UserNotAllow，" : "") + "无法录音:" + msg);
                });


                if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
                    this.recognition = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
                    this.recognition.continuous = true;
                    this.recognition.interimResults = true;
                    this.recognition.lang = this.language; // 设置语言，可以根据需要更改

                    // 在识别到结果时触发
                    this.recognition.onresult = function(event) {
                        let interimTranscript = '';
                        for (let i = event.resultIndex; i < event.results.length; i++) {
                            if (event.results[i].isFinal) {
                                // 如果是最终结果，将其添加到 recognitionText 中
                                vm.recognitionText += event.results[i][0].transcript;
                            } else {
                                // 如果是中间结果，将其添加到 interimTranscript 中
                                interimTranscript += event.results[i][0].transcript;
                            }
                        }
                        // 如果你需要处理中间结果，可以在这里对 interimTranscript 进行操作
                    };
                } else {
                    alert('浏览器不支持语音识别功能');
                }
            }
        });
    </script>
</body>

</html>