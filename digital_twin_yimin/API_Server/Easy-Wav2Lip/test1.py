import os

def find_exact_path(root_dir, target_fragment):
    for dirpath, dirnames, filenames in os.walk(root_dir):
        if dirpath.endswith(target_fragment):
            return dirpath
    return None

def set_information(username, video_path, audio_path):
    
    # 使用者名稱
    user = username
    
    # 輸入影片路徑
    input_video = video_path
    
    # 輸入音檔路徑
    input_audio = audio_path
    
    # 資料夾路徑
    
    root_directory = '/var/www/html/'
    target_path_fragment = 'WEB_Server/family'

    full_path = find_exact_path(root_directory, target_path_fragment)
    
    print(full_path)
    
    # 輸出影片資料夾路徑
    output_video_folder = full_path + '/'+ user +'/output/results/'
    # 輸出影片名稱
    output_video_name = user
    # 暫存資料夾工作路徑
    temp_working_directory = full_path + '/' + user + '/output/'
    # 暫存資料夾位置
    temp_directory = full_path + '/' + user + '/output/temp/'
    # 標記檔案的資料夾路徑
    detected_face_folder = full_path + '/' + user + '/output/detected_face/'
    
    
    
    
username = "test20241001070557"
video_path = ""
audio_path = ""
set_information(username, video_path, audio_path)