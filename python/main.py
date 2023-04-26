import base64
import json

import cv2
import subprocess
import requests
import zmq
import numpy as np

server = 'http://5.101.51.12/php/'
image = open('image.jpeg', 'rb')

s = requests.Session()

# open webcam
# cap = cv2.VideoCapture(0)

# read image
# array = np.asarray(bytearray(image.read()), dtype=np.uint8)
# image = cv2.imdecode(array, cv2.IMREAD_COLOR)
# cv2.imshow('image', image)

response = s.post(server, files={'file': image})
print(response.content)

# # Loop to capture and encode video frames
# while True:
#     ret, frame = cap.read()
#     if not ret:
#         break
#     print(frame.shape)
#
# cap.release()