<?php
// ====== 访问统计功能 ======
$counterFile = __DIR__ . "/counter.txt";

// 过滤机器人（可修改白名单）
$ua = strtolower($_SERVER["HTTP_USER_AGENT"]);
$isBot = preg_match("/bot|spider|crawler|curl|wget|python/", $ua);

if (!$isBot) {
    if (!file_exists($counterFile)) {
        file_put_contents($counterFile, "0");
    }
    $count = (int)file_get_contents($counterFile);
    file_put_contents($counterFile, $count + 1);
}

// ====== 原有内容继续执行 ======
include __DIR__ . '/pdf_mapping_template.php';  // 引入资源
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>试卷入口</title>

<!-- 引入广告脚本 -->
<script src="ads.js"></script>
<style>

/* 全局样式 */
body {
    font-family: Arial, sans-serif;
    background-color: #f8f8f8;
    margin: 0;
    padding: 20px;
    color: #333;
}

h2 {
    font-size: 1.5em;
    margin-bottom: 15px;
    text-align: left; /* 改成靠左 */
}

/* 搜索框 */
#search {
    width: 100%;
    max-width: 400px;
    padding: 10px 12px;
    font-size: 1rem;
    margin-bottom: 10px;
    display: block;
    border: 1px solid #ccc;
    border-radius: 6px;
    box-sizing: border-box;
}

/* 搜索按钮 */
#searchBtn {
    padding: 8px 12px;
    border-radius: 6px;
    border: none;
    background-color: #0077cc;
    color: #fff;
    cursor: pointer;
}

/* 文件夹按钮样式 */
.folder {
    display: block;
    padding: 14px 18px;
    margin-bottom: 12px;
    background-color: #ffffff;
    border-radius: 8px;
    text-decoration: none;
    color: #0077cc;
    font-weight: bold;
    text-align: left; /* 改成靠左 */
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    word-break: break-word;
}

.folder:hover {
    background-color: #e6f0ff;
    transform: translateY(-2px);
}

/* 响应式调整 */
@media (max-width: 600px) {
    body { padding: 15px; }
    h2 { font-size: 1.3em; }
    .folder { padding: 12px 15px; font-size: 0.95em; }
    #search { font-size: 0.95em; padding: 8px 10px; }
}
</style>
<script>
function searchFolders() {
    let kw = document.getElementById("search").value.trim();
    let folders = Array.from(document.getElementsByClassName("folder"));
    if(kw===""){
        folders.forEach(f=>f.style.display="block");
        return;
    }
    
    let matches = folders.map(f=>{
        let name = f.innerText;
        let matchCount = 0;
        for(let ch of kw){
            if(name.includes(ch)) matchCount++;
        }
        // 修正：除以搜索词长度，而不是文件夹名长度
        let score = matchCount / kw.length;
        return {folder:f, score:score};
    }).filter(m=>m.score>0.8);
    
    matches.sort((a,b)=>b.score-a.score);
    folders.forEach(f=>f.style.display="none");
    matches.forEach(m=>m.folder.style.display="block");
}
function goHome() {
    window.location.href = "https://nuaaexam.store";
}
</script>
</head>
<body>

<h2 style="margin: 0;">为防止该页面无法访问</h2>
<h2 style="margin: 0;">建议浏览器输入nuaaexam.store进入</h2>
<h2 style="margin: 0;"> </h2>
<!-- <h3 style="margin: 0; font-weight: normal;">
  最近更新时间：
  <?php
    date_default_timezone_set("Asia/Shanghai");  // 设置为北京时间
    $last_modified = filemtime(__FILE__);
    echo date("Y/m/d H:i:s", $last_modified);
  ?>
</h3> -->
<!-- <h4 style="margin: 0; font-weight: normal;">若水印较为影响学习,请联系创作者重新上传科目</h4> -->
<input type="text" id="search" placeholder="搜索试卷...也期待您为资源贡献力量">
<!-- <h4 style="margin: 0; font-weight: normal;">部分科目存在只有答案没有试卷的情况,后续添加</h4> -->
<h4 style="margin: 0; font-weight: normal;">上传资源邮箱:nuaaexam@163.com</h4>
<button onclick="searchFolders()" 
        style="margin-top:10px; padding:8px 12px; border-radius:6px; border:none; background-color:#0077cc; color:#fff; cursor:pointer;">
        搜索</button>
<button onclick="goHome()" 
        style="margin-top:10px; margin-left:10px; padding:8px 12px; border-radius:6px; border:none; background-color:#28a745; color:#fff; cursor:pointer;">
    看看主页有什么
</button>

<div class="exam-list" style="margin-top:20px;">
<?php
foreach ($exams as $dir => $_) {

    // 跳过空白项
    if (trim($dir) === "") continue;

    // 如果遇到 "---"，输出自定义提示
    if ($dir === "---") {
        echo '<div style="margin:30px 0 15px; text-align:center; color:#0077cc; font-weight:normal; font-size:1.1em;">—— 以下为部分同学急需科目，已提前上传——</div>';
        continue;
    }

    // 正常输出科目按钮
    echo '<div class="folder" onclick="location.href=\'exam_subject.php?dir=' . urlencode($dir) . '\'">' . htmlspecialchars($dir) . '</div>';
}
?>
</div>

</body>

</html>
