<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'SfyApi.php';

//demo 退款接口
$arr = array(
    //商户订单号
    'merchant_order_id'     => '1470389865',
    //商户退款流水号
    'merchant_refund_id'    => '201608051736032105',
    //退款金额
    'refund_amount'         => '300',
    //退款理由
    'refund_reason'         => '用户要求退款',
);
$api = new SfyApi();
$resultArray = $api->tradeRefund($arr);
echo json_encode($resultArray);exit;
?>
