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
       id="my-video"
       style="width: 617px; height: 347px; left: 0; top: 0;"
>
    <source src="test2.mp4" type="video/mp4" />
</video>

<script>

    const videoTag = document.getElementById("my-video");
    const myMediaSource = new MediaSource();
    const url = URL.createObjectURL(myMediaSource);

    videoTag.src = url;

    setTimeout(function () {

        console.log(myMediaSource.readyState)

        const videoSourceBuffer = myMediaSource
            .addSourceBuffer('video/mp4; codecs="avc1.64001e"');

        fetch("./test1.mp4").then(function(response) {
            console.log('buff', response)
            return response.arrayBuffer();
        }).then(function(videoData) {
            console.log('data', videoData)
            videoSourceBuffer.appendBuffer(videoData);
        });

    }, 1000)



</script>
</body>
</html>