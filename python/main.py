import cv2
import subprocess
import requests
import ffmpeg

# Set up video capture from webcam
cap = cv2.VideoCapture(0)

# Set up video encoder using FFmpeg
cmd = ['ffmpeg',
       '-f', 'rawvideo',
       '-pix_fmt', 'bgr24',
       '-s', '640x480',
       '-i', '-',
       '-c:v', 'libx264',
       '-preset', 'ultrafast',
       '-f', 'mp4',
       '-']
encoder = subprocess.Popen(cmd, stdin=subprocess.PIPE, stdout=subprocess.PIPE)

# Loop to capture and encode video frames
while True:
    ret, frame = cap.read()
    if not ret:
        break
    encoder.stdin.write(frame.tobytes())
    response = requests.post("localhost:80/scv", data=encoder.stdout.readline())

# Release resources
encoder.stdin.close()
encoder.wait()
cap.release()