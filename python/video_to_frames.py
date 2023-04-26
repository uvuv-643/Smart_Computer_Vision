import cv2

# Open video file
cap = cv2.VideoCapture('video1.mp4')

# Define the number of frames to extract per second
fps = 5
total_frames = int(cap.get(cv2.CAP_PROP_FPS))
frames_per_second = total_frames // fps
frames_to_extract = frames_per_second * fps

# Iterate through the frames and save them
for i in range(570):
    frame_position = int((i / fps) * 1000)
    cap.set(cv2.CAP_PROP_POS_MSEC, frame_position)
    success, frame = cap.read()
    if success:
        filename = f'fr1/frame_{i}.jpg'
        cv2.imwrite(filename, frame)