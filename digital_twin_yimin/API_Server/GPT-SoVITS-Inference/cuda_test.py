import torch

cuda = torch.cuda.is_available()

if (cuda == True):
    print("CUDA可使用")
else:
    print("CUDA不可使用")