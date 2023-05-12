import threading
import time
import cv2
import requests
import torch
import pandas as pd
from datetime import datetime, timedelta

import ffmpeg
import base64
import json
import subprocess
import numpy as np
import os
import tqdm
import yaml
import PIL
import psutil
import torchvision
import matplotlib as plt
import seaborn as sns


def peoples_from_frame(_frame):
    results = model(_frame)
    actual_count = results.pandas().xyxy[0]
    peoples = actual_count[actual_count['name'] == 'person']
    return peoples


def count_people_and_send_response(_frame):
    global globalFigures
    peoples = peoples_from_frame(_frame)
    people_count = peoples.shape[0]
    globalFigures = peoples
    requests.post(server_stats_store_route, headers=headers, data={'count': people_count})


def createNewVideo(_cap, _fps):
    fourcc = 'mp4v'
    w = int(_cap.get(cv2.CAP_PROP_FRAME_WIDTH))
    h = int(_cap.get(cv2.CAP_PROP_FRAME_HEIGHT))
    video_name = f"videos/stream_{ datetime.now().strftime('%Y-%m-%d_%H-%M-%S') }.mp4"
    return {
        'video': cv2.VideoWriter(video_name, cv2.VideoWriter_fourcc(*fourcc), min(_fps, 30), (w, h)),
        'path': video_name
    }


api_key = '9|pBUN7kDsKKtyFKLrsQWDc01HIuMxSII1NMPz7auo'
server_stats_store_route = 'https://uvuv643.ru/api/people-data/'
server_video_store_route = 'https://uvuv643.ru/api/videos/'
sending_video_interval = 3
camera_fps_rate = 5
model = torch.hub.load('ultralytics/yolov5', 'yolov5s')
model.conf = 0.1  # confidence threshold
model.iou = 0.2  # NMS IoU threshold
headers = {
    'Accept': 'application/json',
    'Authorization': f'Bearer {api_key}'
}
globalFigures = pd.DataFrame()

# cap = cv2.VideoCapture('cam1.mp4')
cap = cv2.VideoCapture('event.avi')
# cap = cv2.VideoCapture(0)

frame_number = 0
while True:
    ret, frame = cap.read()
    if ret:

        # upload video to client server every 15 seconds
        if (frame_number / camera_fps_rate) % sending_video_interval == 0:
            try:
                video.release()
                with open(video_path, 'rb') as f:
                    r = requests.post(server_video_store_route, headers=headers, files={'file': f})
                    print(r.content)
            except NameError:
                pass

            created_video_object = createNewVideo(cap, camera_fps_rate)
            video = created_video_object['video']
            video_path = created_video_object['path']

        # add rectangles to current frame
        for index, person in globalFigures.iterrows():
            start_point = (int(person.xmin), int(person.ymin))
            end_point = (int(person.xmax), int(person.ymax))
            color = (0, 255, 0)
            thickness = 2
            frame = cv2.rectangle(frame, start_point, end_point, color, thickness)

        # write current frame to video
        video.write(frame)

        # show video on screen
        cv2.imshow('Camera', frame)
        time.sleep(1 / camera_fps_rate)

        # with given interval attempting to count persons and send it to server
        if frame_number % camera_fps_rate % 10 == 0:
            t = threading.Thread(target=count_people_and_send_response, args=(frame,))
            t.start()

    frame_number += 1

    # if user wants to exit
    if cv2.waitKey(1) == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()
