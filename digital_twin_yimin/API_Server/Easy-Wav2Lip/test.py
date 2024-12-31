import requests

# url = 'http://localhost:5005/get_video'
# data1 = {
#     'user_name': 'leong20241101134016',
#     'video_path': '/var/www/html/digital_twin/WEB_Server/family/leong20241101134016/input/video/leong20241101134016.mp4',
#     'audio_path': '/var/www/html/digital_twin/WEB_Server/family/leong20241101134016/input/audio/YiMin.wav'
# }

# response = requests.post(url, json=data1)

# print(response.json())

url = 'http://localhost:5005/get_video'
data2 = {
    'user_name': 'leong20241101134015',
    'video_path': '/var/www/html/digital_twin/WEB_Server/family/leong20241101134015/input/video/leong20241101134016.mp4',
    'audio_path': '/var/www/html/digital_twin/WEB_Server/family/leong20241101134015/input/audio/YiMin.wav'
}

response = requests.post(url, json=data2)

print(response.json())