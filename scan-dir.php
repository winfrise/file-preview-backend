<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: *");

header('Expires: -1');
header('Cache-Control: no-cache, must-revalidate');

header("Content-type:application/json");

ini_set('open_basedir', __DIR__);

// 路径
$path = isset($_GET['path']) ? $_GET['path'] : './uploads';

function scan_dir($path) {
    $dir_handle = opendir($path);
    $count = 0;
    $result = array(
      'file_list' => array(),
      'folder_list' => array(),
      'folder_data' => array()
    );
    while ($item = readdir($dir_handle)) {
        if ($item == '.' || $item == '..' || $item === '.git' || $item === 'assets') {
            continue;
        }
        $item_path = $path . '/' . $item;
        if (is_file($item_path)) {
          $full_path =  $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . substr($item_path, 2);
          $result['file_list'][] = $full_path;
        } elseif (is_dir($item_path)) {
          if (!isset($result['folders'])) {
            $result['folder_list'] = array();
            $result['folder_data'] = array();
          }
          $result['folder_list'][] = $item;
          $result['folder_data'][] = scan_dir($item_path);
        }
    }
    closedir($dir_handle);
    return $result;
}

$result = scan_dir($path);

$res = array(
  'code' => '000000',
  'message' => '成功',
  'data' => $result
);

echo json_encode($res)
?>
