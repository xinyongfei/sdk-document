<?php
require_once 'SfyUtil.php';
require_once 'SfyConfig.php';
/**
 * 网关收单类
 * @author Administrator
 *
 */

class SfyPay {
    public function buildRequestHtml(array $requestData) {
        $sdkUrl = SfyConfig::PAY_URL;
        if (isset($requestData['source_type']) && in_array(strtolower($requestData['source_type']), ['android','ios','wap'])) {
            $sdkUrl = SfyConfig::PAY_MOBILE_URL;
        }

        if (isset($requestData['source_type']) && in_array(strtolower($requestData['source_type']), ['pc'])) {
            $sdkUrl = SfyConfig::PAY_URL;
        }

        $html = '<!DOCTYPE html><html><head><meta charset="utf-8"/></head><body onload="autosubmit()"><form method="post" action="' . $sdkUrl . '" id="payForm">';

        $formData = SfyUtil::buildFormData($requestData);

        //$formData = json_decode('{"biz_content":"XgRhPnsiN7EmWKwhdrOMQ0m3OJQNef%2b4gw5czeCMViWwxHf9tAkA95KSjvw%2fG3sTI%2fCSWTZustnyLbSujQ21XvxRAEX3zXNasp2pmQ9EtW9D2RanvYk6492xTUsKgf39c83uxDmPc8G69CynMSit4wgHqPs9NxSMdO2kWuQSi9rONgutfdNiKOwO0VsNeNwb0cHsmAT2nAUS%2bMlhymIYpRDTmuebMhmjS4IVqADQdUa15Eyt4HRwg%2fNVDnXuiYAdri%2bUPqqn6rywgFNnhLj4mvTzP%2f9RsARRkpKO%2fD8bexPhUtifb6e%2bu8zqYABAy365Q8r3bPSKysmwSnd2m6aiSfrsMKGdjkBJAg28zJbK3UvFXOvap7JfpSZBcghxEFV9ewrCpopXO%2ftzaX5x3PvgyyF0RIg6UIGYm7V8wEl6oy7XWvMZC1owXZX6Vx3L48uC2sytGg6wi6O%2fMa00chXBYp%2fRQDjrvruKuEaUOt1BkLciaWjxXQMyjKAOOekIV9Rfassst81Gep8jypPafmRESUbCPZnolN7CZa604Oxn%2bPfJxNA%2f3fowM6c6948zhPVXDpNoNGNjesPgkLSmEnkl%2fYF1KMRy61QGZYJvtYPkoPc9hB2YW8ZmqYNvZuNR3fr1ZYJvtYPkoPc9hB2YW8ZmqaCUsYR5eaKc6F4ljIz7gBnhyafXOHbo4ds4wDpExfR8RTv8EaWSuiZ%2fEjDVq09Qlecu0mGJpaTUTlx7gg5J4CNFiR1xm3Q7ksYQjj4X5bCij3IzFRnnjwxpe29qfIar8SWqKP9mp06xDMF1nKaVEOv7Nv2dZI5CGqVcrAKni68DzifwsgmvGBZLsInriwY6sDN3TDI%2bD7yY81sxFn6UbArNFF5DedKgYJt2w%2fnMOJwdjETzR%2fUfB%2bu1k884PwWRgtZ9dQk8Q%2bpTepX3wFc5ois5praIL%2fPnV2WCb7WD5KD3yDIaKfkmqzBzLwHY7sgbijwCgjRIOcvRzADYpuriT3Oyc%2feWzTLP4Q%3d%3d","charset":"utf-8","merchant_code":"90175500001","method":"trade.create","timestamp":"63601433441","version":"1.0","sign":"0e57f3c1defb05523c3883f77624c1d53e08e31019ed6a063e27728d4675b4ba"}', true);

        foreach ($formData as $inputName => $inputValue) {
            $html .= '<input type="hidden" id="' . $inputName . '" name="' . $inputName . '" value="' . $inputValue . '">';
        }

        $html .= '</form><script>function autosubmit(){document.getElementById("payForm").submit();}</script></body></html>';
        return $html;
    }

    public function getReturnData() {
        return json_decode(SfyUtil::decrypt($_GET['biz_content'], SfyConfig::PRIVATE_KEY), true);
    }

    public function getNotifyData() {
        return json_decode(SfyUtil::decrypt($_POST['biz_content'], SfyConfig::PRIVATE_KEY), true);
    }

    public function returnVerify() {
        return SfyUtil::checkSign($_GET);
    }

    public function notifyVerify() {
        return SfyUtil::checkSign($_POST);
    }

    private function checkRequiredParamters(array $requiredParametersArray, array $data, $errorCode) {
        foreach ($requiredParametersArray as $requiredParameters) {
            if (!isset($data[$requiredParameters])) {
                return array('code' => $errorCode, 'message' => '缺少必填参数' . $requiredParameters);
            }
        }
        return true;
    }
}