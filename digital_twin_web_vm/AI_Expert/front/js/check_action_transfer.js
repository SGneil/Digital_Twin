function updata(family_id) {
    $.ajax({
        url: '../end/select_transfer_video.php', // 替换为你的PHP文件路径
        type: 'POST',
        data: {
            family_id: family_id,
        },
        success: function(response) {
            console.log('成功:', response);
            alert('感謝你滿意這次結果');
            // window.location.reload();
            window.location.href = '../../AI_Expert/front/preview_selection.php';
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('错误:', textStatus, errorThrown);
            alert('選擇失敗');
        }
    });
}

function delete_picture(family_id) {
    $.ajax({
        url: '../end/delete_picture.php', // 替换为你的PHP文件路径
        type: 'POST',
        data: {
            family_id: family_id,
        },
        success: function(response) {
            // console.log('成功:', response);
            alert('請您重新上傳照片');
            window.location.reload();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // console.log('错误:', textStatus, errorThrown);
            alert('刪除失敗');
        }
    });
}