import os
import argparse
from .get_phonemes import get_phonemes
from .get_ssl_features import get_ssl_features
from .get_semantic import get_semantic

def main(input_txt_path, save_path, input_wav_path):
    params = {
        # 標記檔位置 .list
        "input_txt_path": input_txt_path,
        # 存檔位置log
        "save_path": save_path,
        # 音頻檔位置
        "input_wav_path": input_wav_path
    }
    get_phonemes(**params)
    get_ssl_features(**params)
    get_semantic(**params)

# input_txt_path = "/home/neil47111202/digital_twin/API_Server/GPT-SoVits/DATA/Test/asr_opt/slicer.list"
# save_path = "/home/neil47111202/digital_twin/API_Server/GPT-SoVits/logs/Test"
# input_wav_path = "/home/neil47111202/digital_twin/API_Server/GPT-SoVits/DATA/Test/slicer"

# main(input_txt_path, save_path, input_wav_path)