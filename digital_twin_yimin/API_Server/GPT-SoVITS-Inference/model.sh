#!/bin/bash

git clone https://huggingface.co/lj1995/GPT-SoVITS GPT_SoVITS/pretrained_models/

git clone https://www.modelscope.cn/iic/speech_paraformer-large_asr_nat-zh-cn-16k-common-vocab8404-pytorch.git tools/asr/models/speech_paraformer-large_asr_nat-zh-cn-16k-common-vocab8404-pytorch/

git clone https://www.modelscope.cn/iic/speech_fsmn_vad_zh-cn-16k-common-pytorch.git tools/asr/models/speech_fsmn_vad_zh-cn-16k-common-pytorch/

git clone https://www.modelscope.cn/iic/punc_ct-transformer_zh-cn-common-vocab272727-pytorch.git tools/asr/models/punc_ct-transformer_zh-cn-common-vocab272727-pytorch/

git clone https://huggingface.co/Systran/faster-whisper-large-v3 tools/asr/models/faster-whisper-large-v3/