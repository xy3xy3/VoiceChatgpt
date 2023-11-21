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
</head>

<body>
    <div id="app" class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3 col-sm-12">
                <div class="card">
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
                            <div>
                                音色选择：
                                <label for="alloy">Alloy</label>
                                <input type="radio" id="alloy" v-model="selectedSound" :value="'alloy'">
                                <label for="echo">Echo</label>
                                <input type="radio" id="echo" v-model="selectedSound" :value="'echo'">
                                <label for="fable">Fable</label>
                                <input type="radio" id="fable" v-model="selectedSound" :value="'fable'">
                                <label for="onyx">Onyx</label>
                                <input type="radio" id="onyx" v-model="selectedSound" :value="'onyx'">
                                <label for="nova">Nova</label>
                                <input type="radio" id="nova" v-model="selectedSound" :value="'nova'">
                                <label for="shimmer">Shimmer</label>
                                <input type="radio" id="shimmer" v-model="selectedSound" :value="'shimmer'">
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        let app = new Vue({
            el: '#app',
            data: {
                status: "finish",
                cur: "stop",
                msg: null,
                rec: null,
                chunks: [],
                socket: null,
                selectedSound: "echo" // 更改为单选框的绑定属性

            },
            methods: {
                startRecording() {
                    this.cur = 'start';
                    this.rec.start();
                },
                stopRecording() {
                    vm = this;
                    this.cur = 'stop';
                    this.rec.stop(function(blob, duration) {
                        vm.status = 'load';
                        let formData = new FormData();
                        formData.append('audio', blob, "recorder.mp3");
                        formData.append('voice', vm.selectedSound); // 提交用户选择的音色
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
                },
            },
            mounted() {
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
            }
        });
    </script>
</body>

</html>