import torch
from tqdm import tqdm
import pandas as pd
import numpy as np
from matplotlib import pyplot as plt

# file = open('answers/answer1.txt', 'r')
# total = eval(file.read())
#
# n = 10
# target_iterator = sorted(np.concatenate([
#     np.array(range(0, len(total), n)),
#     np.array(range(n // 2, len(total) - n // 2, n))
# ]))
# means = [np.array(total[i:i + n]).mean() for i in target_iterator]
# plt.bar(list(range(len(means))), means)
# plt.show()
#
# print(np.array(means).mean())


# Model

file = open('answer3.txt', 'w')
model = torch.hub.load('ultralytics/yolov5', 'yolov5x')

total = []

for i in tqdm(range(1)):
    im = 'image.webp'
    results = model(im)
    actual_count = results.pandas().xyxy[0]
    total.append(actual_count[actual_count['name'] == 'person'].shape[0])

print(total, file=file)
print(total)
