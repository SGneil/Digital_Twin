#!/bin/bash


# 下載模型 (pretrained_models)

rm -rf pretrained_models/

git clone https://huggingface.co/lj1995/GPT-SoVITS pretrained_models/

mkdir pretrained_models/gpt_weights/
mkdir pretrained_models/sovits_weights/

mkdir pretrained_models/gpt_weights/pretrained
mkdir pretrained_models/sovits_weights/pretrained

mv pretrained_models/s1bert25hz-2kh-longer-epoch=68e-step=50232.ckpt pretrained_models/gpt_weights/pretrained

mv pretrained_models/s2D488k.pth pretrained_models/sovits_weights/pretrained

mv pretrained_models/s2G488k.pth pretrained_models/sovits_weights/pretrained

# 下載模型 (asr)

git clone https://www.modelscope.cn/iic/speech_paraformer-large_asr_nat-zh-cn-16k-common-vocab8404-pytorch.git tools/asr/models/speech_paraformer-large_asr_nat-zh-cn-16k-common-vocab8404-pytorch/

git clone https://www.modelscope.cn/iic/speech_fsmn_vad_zh-cn-16k-common-pytorch.git tools/asr/models/speech_fsmn_vad_zh-cn-16k-common-pytorch/

git clone https://www.modelscope.cn/iic/punc_ct-transformer_zh-cn-common-vocab272727-pytorch.git tools/asr/models/punc_ct-transformer_zh-cn-common-vocab272727-pytorch/

git clone https://huggingface.co/Systran/faster-whisper-large-v3 tools/asr/models/faster-whisper-large-v3/

# 下載模型(uvr5)

rm -rf tools/uvr5/uvr5_weights/

git clone https://huggingface.co/YiMin0130/GPT-soVits-uvr5 tools/uvr5/uvr5_weights/