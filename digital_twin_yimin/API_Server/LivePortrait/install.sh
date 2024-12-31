#!/bin/bash

# 建立虛擬環境
# conda create -n LivePortrait python==3.9.18 -y
# conda activate LivePortrait

# 安裝 torch 及相關套件
# for CUDA 12.1
pip install torch==2.3.0 torchvision==0.18.0 torchaudio==2.3.0 --index-url https://download.pytorch.org/whl/cu121
# pip3 install torch torchvision torchaudio
pip install -r requirements.txt
pip install Flask

# 下載預訓練檔案
git clone https://huggingface.co/camenduru/LivePortrait pretrained_weights/
cd pretrained_weights/
rm -rf .git
rm -rf .gitattributes 
cd ../