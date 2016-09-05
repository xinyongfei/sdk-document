<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'SfyPay.php';

$sfyPay = new SfyPay();
if ($sfyPay->returnVerify() === true) {
    $returnData = $sfyPay->getReturnData();
    var_dump($returnData);exit;
    if ($returnData['trade_status'] === 'PAY_SUCCESS') {
        //此处执行支付成功业务逻辑
        $returnData['merchant_order_id'];

    } else {

    }
} else {
    echo '验证失败';
}
?>