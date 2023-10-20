<?php
// 参考链接：https://blog.csdn.net/qq_45047809/article/details/125068090

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: *");

header('Expires: -1');
header('Cache-Control: no-cache, must-revalidate');

header("Content-type:application/json");

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
    $result = array('dir'=>array(),'file'=>array());
    //从目录总读取文件
    while(false !== ($file_name = readdir($handle))) {
        //除去上级目录和本级目录
        if($file_name != '.' && $file_name != '..' && $file_name != '.DS_Store') {
            //文件全路径
            $file_path = "$path/$file_name";
            //文件类型
            $file_type = filetype($file_path);
            //判断，文件类型是文件或者目录
            if(!in_array($file_type,array('file','dir'))) {
                continue;
            }

            //数组填入值
            $result[$file_type][] = array(
                'file_suffix'=>$file_type == 'file' ? getExt($file_path) : '',
                'file_name'=>$file_name,
                'file_path'=>$file_path,
                'file_size'=>round(filesize($file_path)/1024),
                'file_time'=>date('Y/m/d H:i:s',filemtime($file_path)),
                'real_path'=>realpath($file_path),
                'full_path'=>'http://' . $_SERVER['HTTP_HOST'] . substr($file_path, 1),
            );
        }
    }
    //释放句柄
    closedir($handle);
    return $result;
}

$result = getFileList($path);

$data = array(
  'code' => '000000',
  'message' => '成功',
  'data' => $result
);

echo json_encode($data)
?>
