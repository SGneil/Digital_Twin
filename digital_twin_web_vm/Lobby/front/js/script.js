const btn = document.querySelector('#btn');
const txt = document.querySelector('#txt');
const loading_ani = document.getElementById('loading');
let stop = 0; // 增加全局變量 stop

document.addEventListener('DOMContentLoaded', setupMediaSwitcher);

const showText = (user, img, text) => {
    if(user !== 'user1'){
        user = 'user2'
    }else{
        user = 'user1'
    };
    const chatHtml = 
    `<div class="chat-user ${user}">
        <div class="user-img">
            <img src="${img}" alt="" />
        </div>
        <div class="user-msg">
            <p class="typing"></p>
        </div>
    </div>`;

    const chats = document.querySelector('.chats');
    const chatPage = document.querySelector('.chat-page');

    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = chatHtml;
    const newMessage = tempDiv.firstChild;

    chats.appendChild(newMessage);

    // Auto Scroll to Page Bottom
    chatPage.scrollTop = chatPage.scrollHeight - chatPage.clientHeight;

    // Add typing effect
    const typingElement = newMessage.querySelector('.typing');
    typeEffect(typingElement, text);
};

const typeEffect = (element, text, delay = 100) => {
    let i = 0;
    const interval = setInterval(() => {
        element.textContent += text.charAt(i);
        i++;
        if (i >= text.length) {
            clearInterval(interval);
            element.classList.remove('typing'); // Remove typing class after complete
        }
    }, delay);
};

function save_text(text, family_picture, user) {
    const formData = new FormData();
    formData.append('msg', text);
    formData.append('family_picture', family_picture);
    formData.append('user', user);

    $.ajax({
        url: '../end/upload_text.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            get_audio(text, family_picture, user);
        },
        error: function(xhr, status, error) {
            console.log('Upload failed');
            showText(user, user === 'user2' ? './img/user2.jpg' : `./family/${family_picture}`, '上傳錯誤請檢查內容');
        }
    });    
}

function get_gpt_text(text, family_picture) {
    const formData = new FormData();
    formData.append('msg', text);
    formData.append('family_picture', family_picture);

    $.ajax({
        url: '../end/get_gpt_text.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            save_text(response, family_picture, 'user1');
            txt.value = '';
            txt.disabled = false;
            txt.focus();
            showLoading();
        },
        error: function(xhr, status, error) {
            console.log('Upload failed');
            showText('user1', `./family/${family_picture}`, '上傳錯誤請檢查內容');
        }
    });    
}

function get_audio(text, family_picture, user) {
    const formData = new FormData();
    formData.append('msg', text);
    formData.append('family_picture', family_picture);
    formData.append('user', user);

    $.ajax({
        url: '../end/get_audio.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: async function(response) {
            if (user === 'user2') {
                showText(user, './img/user2.jpg', text);
                await playAudio(response); // 等待user2音频播放完毕
            } else {
                const video_path = document.getElementById('video-path').dataset.value;
                const audio_path = response;
                const img = `./family/${family_picture}`;
                await get_video(video_path, audio_path, text, user, img);
            }
        },
        error: function(xhr, status, error) {
            console.log('Upload failed');
        }
    });
}

function playAudio(filepath) {
    return new Promise((resolve) => {
        const audio = new Audio(filepath);
        audio.play();
        audio.onended = function() {
            stop = 1; // 音频播放结束时设置 stop 为 1
            resolve();
        };
    });
}

function get_video(imagepath, audiopath, text, user, img) {
    const formData = new FormData();
    formData.append('imagepath', imagepath);
    formData.append('audiopath', audiopath);

    return new Promise((resolve, reject) => {
        $.ajax({
            url: '../end/get_video.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: async function(response) {
                await waitForUser2Audio(); // 等待user2音频播放完毕
                hideLoading();
                loadAndPlayVideo(response);
                showText(user, img, text);
                resolve();
            },
            error: function(xhr, status, error) {
                console.log('Upload failed');
                reject(error);
            }
        });
    });
}

function waitForUser2Audio() {
    return new Promise((resolve) => {
        const audioCheckInterval = setInterval(() => {
            if (stop === 1) { // 检查 stop 变量
                clearInterval(audioCheckInterval);
                resolve();
            }
        }, 200); // 每秒检查一次
    });
}

function showLoading() {
    loading_ani.style.display = 'block';
}

function hideLoading() {
    loading_ani.style.display = 'none';
}

function setupMediaSwitcher() {
    const videoElement = document.getElementById('userVideo');
    const imageElement = document.getElementById('userImage');

    function loadAndPlayVideo(videoSrc) {
        videoElement.src = videoSrc;
        imageElement.style.display = 'none';
        videoElement.style.display = 'block';
        videoElement.load();
        videoElement.play();
    }

    videoElement.addEventListener('ended', () => {
        videoElement.style.display = 'none';
        imageElement.style.display = 'block';
    });

    window.loadAndPlayVideo = loadAndPlayVideo;
}

btn.addEventListener('click', () => {
    const value = txt.value;
    
    if (value !== '' && value !== '上傳中~') {
        txt.value = '上傳中~';
        txt.disabled = true;
        const img2_path = document.getElementById('img1-path').dataset.value;
        save_text(value, img2_path, 'user2');
        get_gpt_text(value, img2_path);
    }
});

txt.addEventListener('keypress', (event) => {
    if (event.key === 'Enter') {
        const value = txt.value;
    
        if (value !== '' && value !== '上傳中~') {
            txt.value = '上傳中~';
            txt.disabled = true;
            const img2_path = document.getElementById('img1-path').dataset.value;
            save_text(value, img2_path, 'user2');
            get_gpt_text(value, img2_path);
        }
    }
});
