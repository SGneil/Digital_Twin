document.getElementById('generateCodeBtn').addEventListener('click', function() {
    fetch('../end/generate_code.php')
        .then(response => response.text())
        .then(data => {
            const codeElement = document.getElementById('generatedCode');
            codeElement.innerText = data;
            codeElement.style.opacity = 1;

            document.getElementById('openCodeBtn').style.display = 'inline-block';
            document.getElementById('closeCodeBtn').style.display = 'inline-block';
        });
});

document.getElementById('openCodeBtn').addEventListener('click', function() {
    fetch('../end/open_code.php')
        .then(response => response.text())
        .then(data => {
            alert('代碼已開啟');
        })
        .catch(error => {
            alert('開啟代碼失敗: ' + error);
        });
});

document.getElementById('closeCodeBtn').addEventListener('click', function() {
    fetch('../end/close_code.php')
        .then(response => response.text())
        .then(data => {
            alert('代碼已關閉');
        })
        .catch(error => {
            alert('關閉代碼失敗: ' + error);
        });
});
