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
    $result = array();
    while ($item = readdir($dir_handle)) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        $item_path = $path . '/' . $item;
        if (is_file($item_path)) {
          $result[] = $item_path;
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
  'data' => array(
    'list' => $result
  )
);

echo json_encode($res)
?>
