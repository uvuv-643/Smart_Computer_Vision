import os
import random
import threading
import time
from math import floor

import cv2
import requests
import torch
import pandas as pd
from datetime import datetime
from moviepy.editor import VideoFileClip
import moviepy.video.fx.all as vfx

import ffmpeg
import base64
import json
import subprocess
import ultralytics
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
    global global_figures, last_sent_stats, last_executed_count
    if floor(time.time() * 3) != last_executed_count:
        last_executed_count = floor(time.time() * 3)
        peoples = peoples_from_frame(_frame)
        people_count = peoples.shape[0]
        global_figures = peoples
        if floor(time.time() / 5) != last_sent_stats:
            last_sent_stats = floor(time.time() / 5)
            requests.post(server_stats_store_route, headers=headers, data={'count': people_count})


def create_new_video(_cap, _fps):
    fourcc = 'VP80'
    w = 640
    h = 480
    video_name = f"videos/stream_{datetime.now().strftime('%Y-%m-%d_%H-%M-%S')}.webm"
    return {
        'video': cv2.VideoWriter(video_name, cv2.VideoWriter_fourcc(*fourcc), _fps, (w, h)),
        'path': video_name
    }


def prepare_video_and_upload(_video_path):
    global last_video_executed_time
    target_path = _video_path.replace('.webm', 'm.webm')
    target_time = (time.time() - last_video_executed_time)
    last_video_executed_time = time.time()
    clip = VideoFileClip(_video_path)
    new_fps = clip.fps * clip.duration / target_time
    new_clip = clip.fx(vfx.speedx, factor=clip.duration / target_time).set_fps(new_fps)
    new_clip.write_videofile(target_path, bitrate="10000k")
    os.remove(_video_path)
    with open(target_path, 'rb') as f:
        tim = time.time()
        r = requests.post(server_video_store_route, headers=headers, files={'file': f})
        print(time.time() - tim)


api_key = '9|pBUN7kDsKKtyFKLrsQWDc01HIuMxSII1NMPz7auo'
server_stats_store_route = 'https://uvuv643.ru/api/people-data/'
server_video_store_route = 'https://uvuv643.ru/api/videos/'
sending_video_interval = 10
last_sent_stats = -1
last_executed_count = -1
camera_fps_rate = 10
model = torch.hub.load('ultralytics/yolov5', 'yolov5l')
model.conf = 0.2  # confidence threshold
model.iou = 0.3  # NMS IoU threshold
headers = {
    'Accept': 'application/json',
    'Authorization': f'Bearer {api_key}'
}
global_figures = pd.DataFrame()

# cap = cv2.VideoCapture('event.avi')
cap = cv2.VideoCapture(0)

frame_number = 0
last_video_executed_time = time.time()
while True:
    ttt = time.time()
    ret, frame = cap.read()
    if ret:

        if (frame_number / camera_fps_rate) % sending_video_interval == 0:
            try:
                video.release()
                t1 = threading.Thread(target=prepare_video_and_upload, args=(video_path,))
                t1.start()
            except NameError:
                pass

            created_video_object = create_new_video(cap, camera_fps_rate)
            video = created_video_object['video']
            video_path = created_video_object['path']

        frame = cv2.resize(frame, (1200, 720))
        # add rectangles to current frame
        for index, person in global_figures.iterrows():
            start_point = (int(person.xmin), int(person.ymin))
            end_point = (int(person.xmax), int(person.ymax))
            color = (0, 255, 0)
            thickness = 2
            frame = cv2.rectangle(frame, start_point, end_point, color, thickness)

        # write current frame to video
        video.write(cv2.resize(frame, (640, 480)))

        # with given interval attempting to count persons and send it to server
        if frame_number % camera_fps_rate % (camera_fps_rate // 10) == 0:
            t = threading.Thread(target=count_people_and_send_response, args=(frame,))
            t.start()

    frame_number += 1

    # if user wants to exit
    if cv2.waitKey(1) == ord('q'):
        break

    if time.time() - ttt <= 1 / camera_fps_rate:
        time.sleep(1 / camera_fps_rate - (time.time() - ttt))

try:
    video.release()
    t1 = threading.Thread(target=prepare_video_and_upload, args=(video_path,))
    t1.start()
except NameError:
    pass

cap.release()
cv2.destroyAllWindows()
