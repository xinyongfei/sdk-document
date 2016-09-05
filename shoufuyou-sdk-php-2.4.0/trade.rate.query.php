<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'SfyApi.php';

//demo 订单查询接口
$arr = array(
);
$api = new sfyApi();
$resultArray = $api->tradeRateQuery($arr);
echo json_encode($resultArray);exit;
?>
