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
       controls
       style="width: 617px; height: 347px; left: 0; top: 0;"
>

</video>

<script>

    var video = document.querySelector('video');
    var assetURL = 'https://donntukhomichukpi20b.space/php/test1.mp4';
    assetURL = 'https://storage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4'
    assetURL = 'https://nickdesaulniers.github.io/netfix/demo/frag_bunny.mp4';

    // Need to be specific for Blink regarding codecs
    // ./mp4info frag_bunny.mp4 | grep Codec
    var mimeCodec = 'video/mp4; codecs="avc1.42E01E, mp4a.40.2"';
    var mediaSource = new MediaSource;
    var sourceBuffer;

    if ('MediaSource' in window && MediaSource.isTypeSupported(mimeCodec)) {
        video.src = URL.createObjectURL(mediaSource);
        mediaSource.addEventListener('sourceopen', function() {
            sourceBuffer = mediaSource.addSourceBuffer(mimeCodec);
            sourceOpen(assetURL)
        });
    } else {
        console.error('Unsupported MIME type or codec: ', mimeCodec);
    }

    function sourceOpen (asset) {
        fetchAB(asset, function (buf) {
            sourceBuffer.appendBuffer(buf);
        });
    }

    function fetchAB (url, cb) {
        console.log(url);
        var xhr = new XMLHttpRequest;
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