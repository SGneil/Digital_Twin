import traceback
import json
from openai import OpenAI


api_key = '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'

client = OpenAI(
    api_key=api_key
)

def run(message):
    ai_msg = ''
    try:
        chat_completion = client.chat.completions.create(
            model="gpt-4o-mini",
            messages=message,
            max_tokens=2048,
            temperature=0.7,
        )
        ai_msg = chat_completion.choices[0].message.content.strip()  # 使用strip()代替replace
    except Exception as e:
        print("Error occurred:", str(e))
        traceback.print_exc()  # 打印詳細的錯誤追蹤
    return ai_msg

def gpt_result(message):
    ai_msg = ''
    try:
        response = client.chat.completions.create(
            model="gpt-4o-mini",
            messages=[
                {
                    "role": "system",
                    "content": [
                        {
                            "type": "text",
                            "text": "總結下面的對話並修正可能的錯字，像是南請替換成男，生日請按照這個格式2002-01-01，並用json回傳，找不到的項不要回傳，務必要整理所有的對話。以下是範例\nname: 姓名\ngender: 性別\nblood: 血型\nphone: 電話\nbirthday: 生日\naddress: 住址\nbucket_list: [夢想1,夢想2]\nlife_review: [人生回顧1,人生回顧2]\nlife_milestones: [人生大事記1,人生大事記2]\nunfinished_business: [未完成的事1,未完成的事2]\nletter: [{recipient:收件人1, content:信件內容1}, {recipient:收件人2, content:信件內容2}]"
                        }
                    ]
                },
                {
                    "role": "user",
                    "content": message
                }
            ],
            temperature=1,
            max_tokens=2048,
            top_p=1,
            frequency_penalty=0,
            presence_penalty=0,
            response_format={
                "type": "json_object"
            }
        )
        ai_msg = response.choices[0].message.content.strip()  
    except Exception as e:
        print("Error occurred:", str(e))
        traceback.print_exc()
    return ai_msg

def generate_prompt(data):
    # 將 JSON 字串轉換為 Python 字典
    data = json.loads(data)
    print(data)
    
    # 生成提示的基本結構
    prompt = """以下資訊是爺爺或奶奶的個人資訊，請根據這些資訊，扮演子孫的爺爺或奶奶。請根據性別決定你自己是爺爺或奶奶，並在用戶說出自己是你的誰的時候，如果子孫的信件有提到他的話，就按照信件的內容來回答。每句話盡可能不要超過50個字，盡可能不要說出重複的話。\n\n"""
    
    # 解析個人資訊
    if data['personal_info']:
        personal_info = json.loads(data['personal_info'])
        prompt += """個人資料：
- 姓名：{name}
- 性別：{gender}
- 生日：{birthday}
- 血型：{blood}
- 電話：{phone}
- 地址：{address}
""".format(**personal_info)

    # 只有當資料不為 None 時才添加相應的部分
    if data['life_milestones']:
        prompt += f"\n人生大事記：\n{', '.join(json.loads(data['life_milestones']))}"

    if data['life_review']:
        prompt += f"\n\n人生回顧：\n{', '.join(json.loads(data['life_review']))}"

    if data['bucket_list']:
        prompt += f"\n\n願望清單：\n{', '.join(json.loads(data['bucket_list']))}"

    if data['unfinished_business']:
        prompt += f"\n\n未完成的事：\n{', '.join(json.loads(data['unfinished_business']))}"

    if data['letter']:
        prompt += "\n\n子孫的信件：\n"
        letters = json.loads(data['letter'])
        for letter in letters:
            prompt += f"給{letter['recipient']}的信：{letter['content']}\n"

    prompt += f"\n在與用戶交談時，請考慮上述資訊，提供個人化和體貼的回應。"

    try:
        response = client.chat.completions.create(
            model="gpt-4o-mini",
            messages=[
                {
                    "role": "system",
                    "content": [
                        {
                            "type": "text",
                            "text": "請使用繁體中文，生成對話的第一句話。"
                        }
                    ]
                },
                {
                    "role": "user",
                    "content": prompt
                }
            ],
        )
        ai_msg = response.choices[0].message.content.strip()  
    except Exception as e:
        print("Error occurred:", str(e))
        traceback.print_exc()
    return prompt, ai_msg

# 親人說話
def family_run(msg):
    ai_msg = ''
    # 將 JSON 字串轉換為 Python 列表
    message = json.loads(msg)
    try:
        chat_completion = client.chat.completions.create(
            model="gpt-4o-mini",
            messages=message,
            max_tokens=300,
            temperature=0.7,
        )
        ai_msg = chat_completion.choices[0].message.content.replace('\n','')  # 移除回應裡的換行符
    except Exception as e:
        print("Error occurred:", str(e))
        traceback.print_exc()
    return ai_msg
