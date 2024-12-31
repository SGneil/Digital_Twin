import os
import traceback
import logging
import librosa
import ffmpeg
import soundfile as sf
import torch
import sys
from .mdxnet import MDXNetDereverb
from .vr import AudioPre, AudioPreDeEcho

logger = logging.getLogger(__name__)

weight_uvr5_root = "tools/uvr5/uvr5_weights"
uvr5_names = []
for name in os.listdir(weight_uvr5_root):
    if name.endswith(".pth") or "onnx" in name:
        uvr5_names.append(name.replace(".pth", ""))

device = 'cuda:0' if torch.cuda.is_available() else 'cpu'
is_half = False

def uvr(model_name, inp_root, save_root_vocal, paths, save_root_ins, agg, format0):
    infos = []
    try:
        inp_root = inp_root.strip(" ").strip('"').strip("\n").strip('"').strip(" ")
        save_root_vocal = (
            save_root_vocal.strip(" ").strip('"').strip("\n").strip('"').strip(" ")
        )
        save_root_ins = (
            save_root_ins.strip(" ").strip('"').strip("\n").strip('"').strip(" ")
        )
        is_hp3 = "HP3" in model_name
        if model_name == "vocals.onnx":
            pre_fun = MDXNetDereverb(f"{weight_uvr5_root}/{model_name}", 15)
        else:
            func = AudioPre if "DeEcho" not in model_name else AudioPreDeEcho
            pre_fun = func(
                agg=int(agg),
                model_path=os.path.join(weight_uvr5_root, model_name + ".pth"),
                device=device,
                is_half=is_half,
            )
        if inp_root != "":
            paths = [os.path.join(inp_root, name) for name in os.listdir(inp_root)]
        else:
            paths = [path.name for path in paths]
        for path in paths:
            inp_path = os.path.join(inp_root, path)
            if not os.path.isfile(inp_path):
                continue
            need_reformat = 1
            done = 0
            try:
                info = ffmpeg.probe(inp_path, cmd="ffprobe")
                if (
                    info["streams"][0]["channels"] == 2
                    and info["streams"][0]["sample_rate"] == "44100"
                ):
                    need_reformat = 0
                    pre_fun._path_audio_(
                        inp_path, save_root_ins, save_root_vocal, format0, is_hp3
                    )
                    done = 1
            except:
                need_reformat = 1
                traceback.print_exc()
            if need_reformat == 1:
                tmp_path = "%s/%s.reformatted.wav" % (
                    os.path.join(os.environ["TEMP"]),
                    os.path.basename(inp_path),
                )
                os.system(
                    "ffmpeg -i %s -vn -acodec pcm_s16le -ac 2 -ar 44100 %s -y"
                    % (inp_path, tmp_path)
                )
                inp_path = tmp_path
            try:
                if done == 0:
                    pre_fun._path_audio_(
                        inp_path, save_root_ins, save_root_vocal, format0, is_hp3
                    )
                infos.append("%s->Success" % (os.path.basename(inp_path)))
                print("\n".join(infos))
            except:
                infos.append(
                    "%s->%s" % (os.path.basename(inp_path), traceback.format_exc())
                )
                print("\n".join(infos))
    except:
        infos.append(traceback.format_exc())
        print("\n".join(infos))
    finally:
        try:
            if model_name == "vocals.onnx":
                del pre_fun.pred.model
                del pre_fun.pred.model_
            else:
                del pre_fun.model
                del pre_fun
        except:
            traceback.print_exc()
        print("clean_empty_cache")
        if torch.cuda.is_available():
            torch.cuda.empty_cache()
    print("\n".join(infos))

# if __name__ == "__main__":
# model_name = "HP5_only_main_vocal"  # 替換成你要使用的模型名稱
# inp_root = "/home/neil47111202/digital_twin/API_Server/GPT-SoVits/DATA/Test/resource"  # 替換成輸入音頻文件夾路徑
# save_root_vocal = "/home/neil47111202/digital_twin/API_Server/GPT-SoVits/DATA/Test/uvr5_vocals"  # 替換成主人聲輸出文件夾路徑
# save_root_ins = "/home/neil47111202/digital_twin/API_Server/GPT-SoVits/DATA/Test/uvr5_others"  # 替換成非主人聲輸出文件夾路徑
# agg = 10  # 人聲提取激進程度
# format0 = "flac"  # 導出文件格式
# paths = []  # 如果不使用文件夾，可以在這裡加入單獨的音頻文件路徑

# uvr(model_name, inp_root, save_root_vocal, paths, save_root_ins, agg, format0)