<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: *");

header("Content-type:application/json");

function generateTree($dir) {
    $tree = [];
    $files =scandir($dir);
    foreach ($files as $file) {
        if ($file !== '.' &&$file !== '..') {
            $path = $dir . '/' . $file;
            if(is_dir($path)) {
                $tree[$file] = generateTree($path);
            } else{
                $tree[] = $file;
            }
        }
    }
    return $tree;
}

$path = isset($_GET['path']) ? $_GET['path'] : './uploads';
$tree = generateTree($path);
echo json_encode($tree);
?>
