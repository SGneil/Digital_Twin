from openai import OpenAI

api_key = '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'

client = OpenAI(
    api_key=api_key
)

stream = client.chat.completions.create(
    model="gpt-4o-mini-2024-07-18",
    messages=[
        {"role": "system", "content": "你是一個專業的AI專員"},
        {"role": "user", "content": "你好嗎!"}],
    stream=True,
)

for chunk in stream:
    if chunk.choices[0].delta.content is not None:
        print(chunk.choices[0].delta.content, end="")
