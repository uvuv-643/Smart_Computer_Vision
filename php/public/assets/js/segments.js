(async () => {

    const videoElement = document.querySelector('.home video');
    const serverCheckUrl = 'https://uvuv643.ru/last-video'

    let lastVideoUpdateTime = new Date(Date.parse('1900-01-01'))

    const tryToGetNewVideo = async () => {
        const response = await (await fetch(serverCheckUrl)).json()
        let currentDate = new Date(Date.parse(response.created_at))
        if (currentDate > lastVideoUpdateTime) {
            console.log()
            return {
                url: response.url,
                created_at: currentDate
            }
        }
        return null
    }

    const uploadNewVideoToStream = async () => {
        return new Promise((resolve, reject) => {
            let iterationNumber = 0
            const interval = setInterval(async () => {
                let videoData = await tryToGetNewVideo(lastVideoUpdateTime)
                if (videoData) {
                    clearInterval(interval)
                    lastVideoUpdateTime = videoData.created_at
                    const blob = await (await fetch(videoData.url)).blob();
                    const duration = await getDuration(blob);
                    const buff = await blob.arrayBuffer();
                    resolve({
                        url: videoData.url,
                        created_at: videoData.created_at,
                        duration,
                        buff
                    })
                }
                if (iterationNumber > 60) {
                    reject()
                }
                iterationNumber++
            }, 1000)
        })
    }

    const mediaSource = new MediaSource();
    videoElement.src = URL.createObjectURL(mediaSource);
    const sourceBuffer = await addSourceBufferWhenOpen(mediaSource, `video/mp4; codecs="mp4v.20.8,mp4a.40.2"`, 'segments');

    let clipIndex = 0;
    sourceBuffer.onupdateend = () => {
        uploadNewVideoToStream().then(result => {
            sourceBuffer.timestampOffset += result.duration;
            sourceBuffer.appendBuffer(result.buff);
        })
    };

    uploadNewVideoToStream().then(result => {
        sourceBuffer.appendBuffer(result.buff);
    })

    console.log({
        sourceBuffer,
        mediaSource,
        videoElement
    });

})();