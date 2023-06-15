(async () => {

    const videoElement = document.querySelector('.home video');
    const serverCheckUrl = 'https://uvuv643.ru/last-video'

    let lastVideoUpdateTime = new Date(Date.parse('1900-01-01'))

    const tryToGetNewVideo = async () => {
        const response = await (await fetch(serverCheckUrl)).json()
        let currentDate = new Date(Date.parse(response.created_at))
        if (currentDate > lastVideoUpdateTime) {
            return {
                url: response.url,
                created_at: currentDate
            }
        }
        return null
    }

    let interval = null

    const getVideo = async (resolve, reject, iterationNumber) => {
        let videoData = await tryToGetNewVideo(lastVideoUpdateTime)
        if (videoData) {
            if (interval) {
                clearInterval(interval)
            }
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
        } else {
            if (iterationNumber > 60) {
                reject()
            }
            iterationNumber++
        }
        return iterationNumber
    }

    const uploadNewVideoToStream = async () => {
        let iterationNumber = 0
        return new Promise((resolve, reject) => {
            interval = setInterval(async () => {
                iterationNumber = await getVideo(resolve, reject, iterationNumber)
            }, 5000)
        })
    }

    const mediaSource = new MediaSource();
    videoElement.src = URL.createObjectURL(mediaSource);
    const sourceBuffer = await addSourceBufferWhenOpen(mediaSource, 'video/webm; codecs="vp8"', 'segments');

    sourceBuffer.onupdateend = () => {
        uploadNewVideoToStream().then(result => {
            sourceBuffer.timestampOffset += result.duration;
            sourceBuffer.appendBuffer(result.buff);
        })
    };

    uploadNewVideoToStream().then(result => {
        sourceBuffer.appendBuffer(result.buff);
    })

    new Promise((resolve, reject) => {
        getVideo(resolve, reject, 0)
    }).then(result => {
        sourceBuffer.appendBuffer(result.buff);
    })

    console.log({
        sourceBuffer,
        mediaSource,
        videoElement
    });

})();