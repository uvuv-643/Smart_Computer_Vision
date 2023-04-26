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
<video controls></video>

<script>

    const assetURL = 'test1.mp4';
    const mimeCodec = 'video/mp4; codecs="avc1.42E01E"';
    let video = document.querySelector('video');
    let mediaSource = new MediaSource;

    if ('MediaSource' in window && MediaSource.isTypeSupported(mimeCodec)) {
        video.src = URL.createObjectURL(mediaSource);
        mediaSource.addEventListener('sourceopen', sourceOpen);
    } else {
        console.error('Unsupported MIME type or codec: ', mimeCodec);
    }

    function sourceOpen (_) {
        let mediaSource = this;
        let sourceBuffer = mediaSource.addSourceBuffer(mimeCodec);
        fetchAB(assetURL, function (buf) {
            sourceBuffer.addEventListener('updateend', function (_) {
                mediaSource.endOfStream();
                video.play();
            });
            sourceBuffer.appendBuffer(buf);
        });
    }

    function fetchAB (url, cb) {
        console.log(url);
        let xhr = new XMLHttpRequest;
        xhr.open('get', url);
        xhr.responseType = 'arraybuffer';
        xhr.onload = function () {
            cb(xhr.response);
        };
        xhr.send();
    }

</script>

</body>
</html>