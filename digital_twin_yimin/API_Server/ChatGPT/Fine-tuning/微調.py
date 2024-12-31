from openai import OpenAI

api_key = "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"
client = OpenAI(api_key=api_key)

# #第一步
# result = client.files.create(
#   file=open("test.jsonl", "rb"),
#   purpose="fine-tune"
# )
# print(result)

#第二步
client.fine_tuning.jobs.create(
  training_file="file-PG3LzKeUVoMhDtwknNEk2NrN", 
  suffix="123qwe",
  model="gpt-4o-mini-2024-07-18"  # 暫時不能用有錯誤
)

#觀看訓練進度
# 列出10個微調
# result = client.fine_tuning.jobs.list(limit=10)
# print(result)

# 檢視微調狀態
# result = client.fine_tuning.jobs.retrieve("file-PG3LzKeUVoMhDtwknNEk2NrN")
# print(result)

#取消微調
# client.fine_tuning.jobs.cancel("file-PG3LzKeUVoMhDtwknNEk2NrN")

# 列出微調作業中最多的10個
# client.fine_tuning.jobs.list_events(fine_tuning_job_id="ftjob-abc123", limit=10)

# 刪除微調模型
# client.models.delete("ft:gpt-3.5-turbo-0125:personal::9w59o39j")

#model
#ft:gpt-3.5-turbo-0125:personal::9wJjEl3f