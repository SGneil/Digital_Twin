from pydub import AudioSegment
import os
from concurrent.futures import ThreadPoolExecutor, as_completed

def get_audio_length(file_path):
    try:
        audio = AudioSegment.from_file(file_path)
        duration_in_seconds = len(audio) / 1000
        return duration_in_seconds
    except Exception as e:
        print(f"Error loading {file_path}: {e}")
        return 0

def process_file(file_path):
    filename = os.path.basename(file_path)
    duration = get_audio_length(file_path)
    if duration:
        print(f"file: {filename}, duration: {duration} seconds")
        return duration
    return 0

def main(directory):
    total_duration = 0
    files = [os.path.join(directory, f) for f in os.listdir(directory) 
             if f.endswith((".mp3", ".wav", ".ogg", ".flac"))]
    
    with ThreadPoolExecutor() as executor:
        future_to_file = {executor.submit(process_file, file): file for file in files}
        for future in as_completed(future_to_file):
            total_duration += future.result()
    
    print(f"total duration: {total_duration} seconds")


audio_directory = "/home/neil47111202/digital_twin/family/leong/input/audio"
main(audio_directory)
