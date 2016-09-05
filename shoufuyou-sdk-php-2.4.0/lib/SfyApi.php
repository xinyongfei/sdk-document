<?php
require_once 'SfyUtil.php';
/**
 * API接口类
 * @author Administrator
 *
 */

class SfyApi {
    /**
    *
    *查询首付游订单接口
    *method:sfyapi.trade.query
    */
    public function tradeQuery($data) {
        $method = 'trade.query';
        //检测必填参数
        $requiredParametersArray = array(
            'merchant_order_id',
            'trade_number',
        );

        if (($checkResult = $this->checkRequiredParamters($requiredParametersArray, $data, '40001')) !== true) {
            return $checkResult;
        }

        return SfyUtil::submitData($data, $method);
    }

    /**
    *
    *请求首付游退款接口，
    * method:trade.refund
    */
    public function tradeRefund($data) {
        $method = 'trade.refund';
        //检测必填参数
        $requiredParametersArray = array(
            'merchant_order_id',
            'merchant_refund_id',
            'refund_amount',
            'refund_reason'
        );

        if (($checkResult = $this->checkRequiredParamters($requiredParametersArray, $data, '50001')) !== true) {
            return $checkResult;
        }

        return SfyUtil::submitData($data, $method);
    }

    /**
    *
    *查询首付游订单接口
    *method:sfyapi.trade.rate.query
    */
    public function tradeRateQuery($data) {
        $method = 'trade.rate.query';

        return SfyUtil::submitData($data, $method);
    }

    private function checkRequiredParamters(array $requiredParametersArray, array $data, $errorCode) {
        foreach ($requiredParametersArray as $requiredParameters) {
            if (!isset($data[$requiredParameters])) {
                return array('code' => $errorCode, 'message' => '缺少必填参数：' . $requiredParameters);
            }
        }
        return true;
    }
}
