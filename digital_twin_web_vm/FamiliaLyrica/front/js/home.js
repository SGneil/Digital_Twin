// 點擊按鈕時平滑滾動到頁面頂部
function scrollToTop() {
    // 平滑滾動到頁面頂部
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// 當滾動條位置大於100時，顯示回到頂部按鈕
window.onscroll = function() {
    scrollFunction();
};

function scrollFunction() {
    var backButton = document.getElementById("back-to-top");
    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        backButton.style.display = "block";
    } else {
        backButton.style.display = "none";
    }
}



