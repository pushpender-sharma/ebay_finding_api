<?php

require_once 'mysqli.php';

function pr($d) {
    echo "<pre>";
    print_r($d);
    echo "</pre>";
}

//pr($_POST['items']);
//echo ("**************************");
//pr($_REQUEST['items']);
//echo ("**************************");
//pr($_REQUEST);
$ids = $_POST['items'];
$query = "SELECT * FROM products WHERE id IN (" . implode(',', $ids) . ")";
//pr($query);
$items = $conn->query($query);
$headers = array();
foreach ($items->rows as $key => $item) {
    if($key == 0){
        $headers []= array_keys($item);
    }
}
//pr($headers);
//die(':');
header('Content-Type: application/excel');
header('Content-Disposition: attachment; filename="export.csv"');
$file_name = '/var/www/dev.techmarbles.com/Ecommerce/Ebay/export.csv';
$file = fopen($file_name, 'w+');
$csv_with_headers = array_merge($headers, $items->rows);
//pr($csv_with_headers);
foreach ($csv_with_headers as $line) {
//    pr($line);
   fputcsv($file, $line, ";");
}
//header('Content-Type: text/csv');
//header('Content-Disposition: attachment; filename="export_items.csv"');
//header('Pragma: no-cache');
//header('Expires: 0');

fclose($file);
$data['response'] = "ok";
echo json_encode($data);
//echo "ok";
//exit();
//ob_clean();
//die(":ASd");
//return "asdbahsdjkaljsdlk";
//return response()->json(['result' => 1]);


