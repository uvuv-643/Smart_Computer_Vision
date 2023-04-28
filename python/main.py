import base64
import json
import threading
import time

import cv2
import subprocess
import requests
import numpy as np
import torch

api_key = '9|pBUN7kDsKKtyFKLrsQWDc01HIuMxSII1NMPz7auo'
server_store_route = 'https://uvuv643.ru/api/people-data/'
model = torch.hub.load('ultralytics/yolov5', 'yolov5m')
headers = {
    'Accept': 'application/json',
    'Authorization': f'Bearer {api_key}'
}

def count_people_by_frame(_frame):
    results = model(_frame)
    actual_count = results.pandas().xyxy[0]
    return actual_count[actual_count['name'] == 'person'].shape[0]

def count_people_and_send_response(frame):
    print('start')
    people_count = count_people_by_frame(frame)
    print(people_count)
    requests.post(server_store_route, headers=headers, data={'count': people_count})

# cap = cv2.VideoCapture('event.avi')
# cap = cv2.VideoCapture(0)

fps = 5
frame_number = 0

while True:
    ret, frame = cap.read()
    frame_number += 1
    if ret:
        cv2.imshow('Camera', frame)
        time.sleep(1 / fps)
        if frame_number % fps == 0:
            # Create a new thread for the task of counting people and sending the response
            t = threading.Thread(target=count_people_and_send_response, args=(frame,))
            t.start()

    if cv2.waitKey(1) == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()

