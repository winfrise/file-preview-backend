<?php
    $filename = isset($_GET['filename']) ? $_GET['filename'] : '';

    $fileinfo = pathinfo($filename);
    var_dump($fileinfo);

    $video_suffix = array('mp4', 'avi');
    $picture_suffix = array('jpg', 'jpeg','png', 'gif');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>文件详情</title>
</head>
<body>
    <?php if(in_array($fileinfo['extension'], $video_suffix)):  ?>
        <video src="<?=$filename ?>" controls></video>

    <?php elseif (in_array($fileinfo['extension'], $picture_suffix)): ?>
        <img src="<?=$filename ?>">
    <?php else: ?>
        <p>文件格式不支持</p>
        <!-- <iframe src="<?=$filename ?>"></iframe> -->
    <?php endif; ?>
</body>
</html>