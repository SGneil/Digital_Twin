import os
from pydub import AudioSegment

def split_audio(audio, max_duration):
    # 將音檔分割成多個段，每個段的最大長度為 max_duration 毫秒
    segments = []
    for i in range(0, len(audio), max_duration):
        segments.append(audio[i:i + max_duration])
    return segments

def process_audio_files(folder_path):
    max_duration = 19000  # 20秒等於20000毫秒

    # 遍歷資料夾中的所有音檔
    for filename in os.listdir(folder_path):
        if filename.endswith(".mp3") or filename.endswith(".wav"):
            file_path = os.path.join(folder_path, filename)
            audio = AudioSegment.from_file(file_path)
            segments = split_audio(audio, max_duration)

            # 保存處理後的音檔段
            base_filename, file_extension = os.path.splitext(filename)
            for i, segment in enumerate(segments):
                segment_filename = f"{base_filename}_part{i+1}{file_extension}"
                segment_path = os.path.join(folder_path, segment_filename)
                segment.export(segment_path, format="mp3")  # 你可以根據需要更改輸出格式
            
            # 刪除原本超過時間的音檔
            os.remove(file_path)

# folder_path = "E:\\Python model\\123\\GPT-SoVits\\DATA\\Test\\raw"
# process_audio_files(folder_path)
