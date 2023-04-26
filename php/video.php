<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<video tabindex="-1"
       class="video-stream html5-main-video"
       controlslist="nodownload"
       id="my-video"
       style="width: 617px; height: 347px; left: 0px; top: 0px;"
>
    <source src="test2.mp4" type="video/mp4" />
</video>

<script>

    const videoTag = document.getElementById("my-video");
    const myMediaSource = new MediaSource();
    const url = URL.createObjectURL(myMediaSource);
    videoTag.src = url;

    console.log(myMediaSource.readyState)

    const audioSourceBuffer = myMediaSource
        .addSourceBuffer('audio/mp4; codecs="mp4a.40.2"');
    const videoSourceBuffer = myMediaSource
        .addSourceBuffer('video/mp4; codecs="avc1.64001e"');

    fetch("./test1.mp4").then(function(response) {
        return response.arrayBuffer();
    }).then(function(audioData) {
        audioSourceBuffer.appendBuffer(audioData);
    });

    fetch("./test1.mp4").then(function(response) {
        return response.arrayBuffer();
    }).then(function(videoData) {
        videoSourceBuffer.appendBuffer(videoData);
    });

</script>
</body>
</html>