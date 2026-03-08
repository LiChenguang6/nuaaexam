<?php
// JSON 文件路径
#$JSON_PATH = __DIR__ . '/pdf_mapping_nested.json';
$JSON_PATH = '/srv/data/pdf_mapping_nested.json';

// 获取科目参数
$dir = isset($_GET['dir']) ? $_GET['dir'] : '';

// 读取 JSON 文件
$exams = [];
if(file_exists($JSON_PATH)){
    $exams = json_decode(file_get_contents($JSON_PATH), true) ?: [];
}

// 检查科目是否存在
if($dir === '' || !isset($exams[$dir])){
    echo "<p>该科目不存在或未上传试卷。</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($dir) ?>试卷</title>
<style>
body { font-family: Arial, sans-serif; background-color: #f8f8f8; margin: 0; padding: 20px; color: #333; }
h2 { font-size: 1.4em; margin-bottom: 15px; }

.file-list { display: flex; flex-direction: column; gap: 12px; }

.file a {
    display: block;
    padding: 14px 18px;
    background-color: #ffffff;
    border-radius: 8px;
    text-decoration: none;
    color: #0077cc;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    transition: all 0.2s ease-in-out;
    word-break: break-word;
}
.file a:hover { background-color: #e6f0ff; transform: translateY(-2px); }

a.back {
    display: inline-block;
    margin-top: 25px;
    padding: 10px 15px;
    background-color: #f0f0f0;
    border-radius: 6px;
    text-decoration: none;
    color: #555;
    font-size: 0.95em;
    transition: all 0.2s;
}
a.back:hover { background-color: #e0e0e0; }

@media (max-width: 600px) {
    body { padding: 15px; }
    h2 { font-size: 1.2em; }
    .file a { padding: 12px 15px; font-size: 0.95em; }
    a.back { padding: 8px 12px; font-size: 0.9em; }
}
</style>
</head>
<body>

<h2><?= htmlspecialchars($dir) ?>试卷</h2>

<div class="file-list">
<?php
$files = $exams[$dir];
if(!empty($files)){
    foreach($files as $name => $url){
        echo '<div class="file">';
        echo '<a href="'.htmlspecialchars($url).'" target="_blank">'.htmlspecialchars($name).'</a>';
        echo '</div>';
    }
} else {
    echo '<p>该科目暂无试卷文件。</p>';
}
?>
</div>

<a class="back" href="exam.php">返回一级目录</a>

</body>
</html>
