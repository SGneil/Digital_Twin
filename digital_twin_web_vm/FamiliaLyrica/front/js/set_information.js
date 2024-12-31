document.getElementById('submitCodeBtn').addEventListener('click', function() {
    const code = document.getElementById('codeInput').value;

    fetch('../end/add_code.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'code=' + encodeURIComponent(code)
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        window.location.reload();
    })
    .catch(error => {
        alert('提交代碼失敗: ' + error);
    });
});


document.querySelectorAll('#deleteBtn').forEach(function(button) {
    button.addEventListener('click', function() {
        const descendants_id = this.closest('tr').querySelector('.descendants-id').dataset.descendantsId;

        fetch('../end/delete_code.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'descendants_id=' + encodeURIComponent(descendants_id)
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            window.location.reload();
        })
        .catch(error => {
            alert('提交代碼失敗: ' + error);
        });
    });
});
