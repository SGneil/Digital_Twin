from moviepy.editor import VideoFileClip, ImageClip, CompositeVideoClip
from PIL import Image
import numpy as np

# 定義浮水印的移動動畫
def moving_logo(t, video, logo):
    # 設定浮水印位置 (x, y)
    x = int((video.size[0] - logo.w) * t / video.duration)  # 從左到右移動
    y = 0  # 始終保持在頂部
    return x, y

def run(video_path, logo_path, result_path):
    # 載入影片
    # video_path = "your_video.mp4"  # 替換成你的影片檔案路徑
    video = VideoFileClip(video_path)

    # 載入浮水印圖片並用 Pillow 調整大小
    # logo_path = "./logo/logo.png"  # 替換成你的浮水印圖片檔案路徑
    logo_image = Image.open(logo_path)

    # 調整浮水印的大小（寬度擴大到影片寬度的20%）
    new_width = int(video.size[0] * 0.2)  # 浮水印寬度為影片寬度的20%
    new_height = int(logo_image.height * (new_width / logo_image.width))
    logo_image_resized = logo_image.resize((new_width, new_height), Image.LANCZOS)

    # 將調整大小的圖像轉換為 numpy 陣列
    logo_np = np.array(logo_image_resized)

    # 調整浮水印的透明度 (設置為 80%)
    logo_np = logo_np.astype(float)  # 轉換為 float 型別以便進行透明度調整
    if logo_np.shape[-1] == 4:  # 確認是否有 alpha 通道
        logo_np[..., 3] = logo_np[..., 3] * 0.5  # 調整透明度，假設圖片有 alpha 通道

    # 將 numpy 陣列轉換為 moviepy 的 ImageClip
    logo = ImageClip(logo_np, ismask=False).set_duration(video.duration)

    # 使用 lambda 函數傳遞 video 和 logo 參數給 moving_logo
    logo = logo.set_position(lambda t: moving_logo(t, video, logo))

    # 合成新影片
    final_video = CompositeVideoClip([video, logo])

    # 輸出影片
    output_path = result_path  # 輸出檔案的路徑
    final_video.write_videofile(output_path, codec="libx264", fps=video.fps)
