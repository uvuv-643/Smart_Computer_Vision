
(async () => {
    const videoElement = document.querySelector('.home video');
    const vidClips = [
        'https://upload.wikimedia.org/wikipedia/commons/transcoded/f/f5/STB_Stuttgart_F%C3%B6hrich_U6_Line_Entering_Station_VIDEO.webm/STB_Stuttgart_F%C3%B6hrich_U6_Line_Entering_Station_VIDEO.webm.160p.webm',
        'https://upload.wikimedia.org/wikipedia/commons/transcoded/8/87/Schlossbergbahn.webm/Schlossbergbahn.webm.160p.webm',
        'https://mdn.github.io/learning-area/html/multimedia-and-embedding/video-and-audio-content/rabbit320.webm'
    ];
    // Shuffle clips. If you reload the page, you will get a random order of videos!
    shuffleArr(vidClips);

    // For `sourceBuffer.mode = 'segments'`, we have to be careful with multiple
    // videos. Segments means that browser will try to sniff timestamps from
    // the files. In our case, we are using completely separate files, without
    // timeline info. So, we need to compute the duration of each, and then use
    // that later to manually offset each chunk from the previous

    /**
     * @typedef {{url: string, duration: number, buff: ArrayBuffer}} ClipToAppend
     */
    /** @type {ClipToAppend[]} */
    const clipsToAppend = await Promise.all(
        vidClips.map(async (vidUrl) => {
            const blob = await (await fetch(vidUrl)).blob();
            const duration = await getDuration(blob);
            const buff = await blob.arrayBuffer();
            return {
                url: vidUrl,
                duration,
                buff
            };
        })
    );

    // Normal setup, with MediaSource, Object URL, and prepped SourceBuffer
    const mediaSource = new MediaSource();
    videoElement.src = URL.createObjectURL(mediaSource);
    // mode = segments
    const sourceBuffer = await addSourceBufferWhenOpen(mediaSource, `video/webm; codecs="vp8,vorbis"`, 'segments');

    /**
     * Pointer to last vid appended out of source list
     */
    let clipIndex = 0;
    sourceBuffer.onupdateend = () => {
        if (clipIndex < clipsToAppend.length - 1) {
            sourceBuffer.timestampOffset += clipsToAppend[clipIndex].duration;
            clipIndex++;
            sourceBuffer.appendBuffer(clipsToAppend[clipIndex].buff);
        } else {
            mediaSource.endOfStream();
            videoElement.play();
        }
    };

    // This will kick off event listener chain above
    sourceBuffer.appendBuffer(clipsToAppend[clipIndex].buff);

    // Debug Info
    console.log({
        sourceBuffer,
        mediaSource,
        videoElement
    });

})();