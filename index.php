<!-- 参考链接：https://blog.csdn.net/qq_45047809/article/details/125068090 -->

<?php

ini_set('open_basedir', __DIR__);

// 路径
$path = isset($_GET['path']) ? $_GET['path'] : './uploads';

// 文件名
$file = '';

if(is_file($path)) { //判断，如果是文件类型
    //获得文件名
    $file = basename($path);
    //获得路径
    $path = dirname($path);
} elseif (!is_dir($path))  { //判断，不是目录
    die('无效的文件路径参数');
}

function getExt($filename) {
    $arr = pathinfo($filename);
    $ext = $arr['extension'];
    return $ext;
}

//获得文件列表
function getFileList($path) {
    // 打开目录，获得句柄
    $handle = opendir($path);
    //空数组
    $list = array('dir'=>array(),'file'=>array());
    //从目录总读取文件
    while(false !== ($file_name = readdir($handle))) {
        //除去上级目录和本级目录
        if($file_name != '.' && $file_name != '..') {
            //文件全路径
            $file_path = "$path/$file_name";
            //文件类型
            $file_type = filetype($file_path);
            //判断，文件类型是文件或者目录
            if(!in_array($file_type,array('file','dir'))) {
                continue;
            }
            //数组填入值
            $list[$file_type][] = array(
                'file_suffix'=>getExt($file_path),
                'file_name'=>$file_name,
                'file_path'=>$file_path,
                'file_size'=>round(filesize($file_path)/1024),
                'file_time'=>date('Y/m/d H:i:s',filemtime($file_path)),
            );
        }
    }
    //释放句柄
    closedir($handle);
    return $list;
}

$file_list = getFileList($path);

?>

<html>
<head>
    <meta charset="UTF-8">
    <title>文件管理器</title>

    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-7ymO4nGrkm372HoSbq1OY2DP4pEZnMiA+E0F3zPr+JQQtQ82gQ1HPY3QIVtztVua" crossorigin="anonymous"></script> -->
    <link rel="stylesheet" href="./assets/styles/reset.css" />
    <link rel="stylesheet" href="./assets/styles/components.css" />
    <script src="./assets/tools/clipboard.js"></script>
    <script src="./assets/tools/toast.js"></script>
    <style>
	html, body{
        font-size:12px;
        height: 100%;
    }

    .wrapper {
        padding: 30px;
    }
    .icon-file-suffix {
        display: block;
        width: 40px;
        height: 40px;
    }
  </style>
</head>
<body>
<div class="wrapper">
    <div>
        <a href="?path=<?= $path;?>&a=prev">返回上一级目录</a>
    </div>


    <table width="100%" class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th>图标</th>
                <th>名称</th>
                <th>修改日期</th>
                <th>大小</th>
                <th>路径</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php  foreach ($file_list['dir'] as $v): ?>
            <tr>
                <td><img class="icon-file-suffix" src="./assets/icons/folder.svg"></td>
                <td><?= $v['file_name'];?></td>
                <td><?= $v['file_time']?></td>
                <td>-</td>
                <td><?= $v['file_path'];?></td>
                <td><a href="?path=<?= $v['file_path'];?>">打开</a></td>
            </tr>
            <?php endforeach;?>
            <?php foreach ($file_list['file'] as $v):?>
            <tr>
                <td><img class="icon-file-suffix" src="./assets/icons/<?=$v['file_suffix'];?>.svg"></td>
                <td><?= $v['file_name'];?></td>
                <td><?= $v['file_time'];?></td>
                <td><?= $v['file_size'];?>KB</td>
                <td><?= $v['file_path'];?></td>
                <td>
                    <button type="button" class="btn btn-info" onclick="handleCopy('<?= $v['file_path'];?>')">复制</button>
                    <a href="./details.php?filename=<?= $v['file_path'];?>" >查看</a>
                </td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    <script>
    window.handleCopy = (str) => {
        window.copyText(str)
            .then(() => {
              window.toast.show('复制成功')
            })
    }

</script>
</div>
</body>
</html>
