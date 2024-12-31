#!/bin/bash

# 安裝套件
python -m pip install --upgrade pip
python -m pip install requests
pip3 install torch torchvision torchaudio
pip install ffmpeg-python mediapipe
pip install -r requirements.txt