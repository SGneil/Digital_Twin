import requests

url = 'http://localhost:5001/run_action_transfer'
data = {
    'image': '/var/www/html/digital_twin/API_Server/LivePortrait/assets/examples/source/1.jpg',
    'driving': '/var/www/html/digital_twin/API_Server/LivePortrait/assets/examples/driving/d0.mp4',
    'result': '/var/www/html/digital_twin/API_Server/LivePortrait/assets/docs'
}

response = requests.post(url, json=data)

print(response.json())