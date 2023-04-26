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
<video
       class="video-stream html5-main-video"
       id="my-video"
       style="width: 617px; height: 347px; left: 0; top: 0;"
>

</video>

<script>

    const videoTag = document.getElementById("my-video");
    const myMediaSource = new MediaSource();
    const url = URL.createObjectURL(myMediaSource);

    videoTag.src = url;

    setTimeout(function () {

        const mimeCodec = 'video/mp4; codecs="avc1.42E01E"';
        const videoSourceBuffer = myMediaSource.addSourceBuffer(mimeCodec);
        console.log(videoSourceBuffer, myMediaSource)

        fetch("./test1.mp4").then(function(response) {
            return response.arrayBuffer();
        }).then(function(videoData) {
            videoSourceBuffer.appendBuffer(videoData);
        }).then(function () {
            console.log(videoSourceBuffer)
            console.log(myMediaSource)
        });

    }, 1000)



</script>
</body>
</html>