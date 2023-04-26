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

    const mimeCodec = 'video/mp4; codecs="avc1.42E01E"';
    const assetURL = 'https://nickdesaulniers.github.io/netfix/demo/frag_bunny.mp4'
    const video = document.getElementById("my-video");
    const mediaSource = new MediaSource();
    const url = URL.createObjectURL(mediaSource);
    video.src = url;

    mediaSource.addEventListener("sourceopen", function () {
        const sourceBuffer = mediaSource.addSourceBuffer(mimeCodec);
        fetch(assetURL)
            .then(response => response.arrayBuffer())
            .then((buf) => {
                sourceBuffer.addEventListener("updateend", () => {
                    if (!sourceBuffer.updating && mediaSource.readyState === 'open') {
                        mediaSource.endOfStream();
                    }
                    video.play();
                    console.log(mediaSource.readyState); // ended
                });
                sourceBuffer.appendBuffer(buf);
        });
    })

</script>
</body>
</html>