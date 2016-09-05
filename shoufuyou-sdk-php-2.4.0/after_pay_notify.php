<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'SfyPay.php';

$sfyPay = new SfyPay();
if ($sfyPay->notifyVerify() === true) {
    $notifyData = $sfyPay->getNotifyData();
    if ($notifyData['trade_status'] === 'PAY_SUCCESS') {
        //此处执行支付成功业务逻辑
        $notifyData['merchant_order_id'];

    } else {

    }
    echo 'SUCCESS';exit;
} else {
    //验证失败;
}
?>