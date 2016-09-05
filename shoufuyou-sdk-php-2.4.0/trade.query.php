<?php 
require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'SfyApi.php';

//demo 订单查询接口
$arr = array(
    //商户订单号
    'merchant_order_id'     => '201604181725081345',
    //首付游交易号
    'trade_number'          => '201604281451098465',
);
$api = new sfyApi();
$resultArray = $api->tradeQuery($arr);
echo json_encode($resultArray);exit;
?>