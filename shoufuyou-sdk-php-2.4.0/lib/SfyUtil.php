<?php
/**
 * 工具类
 * @author shoufuyou phper
 *
 */

require_once 'SfyConfig.php';
require_once 'SfyTripleDESCrypt.php';


class SfyUtil {
    /**
     * 加密biz_content，封装通用层，通过curl请求api
     * @param array     $unencryptedBizContent
     * @param string    $method
     * @return array
     */
    public static function submitData(array $unencryptedBizContent, $method) {
        $encryptedBizContent = static::encrypt(json_encode($unencryptedBizContent, JSON_UNESCAPED_UNICODE), SfyConfig::PRIVATE_KEY);
        $assembledData = static::assembleData($encryptedBizContent, $method);
        if (($checkResult = static::checkRequiredParameters($assembledData)) !== true) {
            return $checkResult;
        }
        if (($postResult = static::postDataByCurl($assembledData)) === false) {
            return array('code' => '10010',  'message' => 'api请求异常');
        }
        if (!static::checkSign($postResult)) {
            return array('code' => '10002', 'message' => '签名错误');
        }
        $decryptedData = static::decrypt($postResult['biz_content'], SfyConfig::PRIVATE_KEY);
        return json_decode($decryptedData, TRUE);
    }

    public static function buildFormData(array $unencryptedBizContent) {
        $encryptedBizContent = static::encrypt(json_encode($unencryptedBizContent, JSON_UNESCAPED_UNICODE), SfyConfig::PRIVATE_KEY);
        return static::assembleData($encryptedBizContent, 'trade.create');
    }

    /**
     * 检查签名
     * @param array     $returnArray    需要检查签名的返回数组
     * @return boolean
     */
    public static function checkSign(array $returnArray) {
        $returnSign = $returnArray['sign'];
        unset($returnArray['sign']);
        if (static::sign($returnArray) == $returnSign) {
            return true;
        }
        return false;
    }

    /**
     * 数据组装，加通用外层，数据签名
     * @param string    $bizContent
     * @param string    $method
     * @return array
     */

    private static function assembleData($bizContent, $method) {
        $assembledData['timestamp']     = time();
        $assembledData['merchant_code'] = SfyConfig::MERCHANT_CODE;
        $assembledData['charset']       = SfyConfig::CHARSET;
        $assembledData['version']       = SfyConfig::VERSION;
        $assembledData['biz_content']   = $bizContent;
        $assembledData['method']        = $method;
        $assembledData['sign']          = SfyUtil::sign($assembledData);
        return $assembledData;
    }

    /**
     * 通用层参数校验
     * @param array         $data
     * @return multitype    array|true
     */
    private static function checkRequiredParameters(array $data) {
        $requiredParametersArray = array(
            'method',
            'timestamp',
            'merchant_code',
            'charset',
            'version',
            'biz_content',
            'sign'
        );

        foreach ($requiredParametersArray as $requiredParameters) {
            if (!isset($data[$requiredParameters])) {
                return array('code' => '10001', 'message' => '缺少必填参数' . $requiredParameters);
            }
        }
        return true;
    }

    /**
     * 数据加密，3DESEncrypt->base64_encode->urlencode
     * @param string    $unencryptedData
     * @param string    $key
     * @return string
     */
    private static function encrypt($unencryptedData, $key = 'shoufuyou') {
        return urlencode(base64_encode(SfyTripleDESCrypt::encrypt($unencryptedData, $key)));
    }

    /**
     * 数据解密，urldecode->base64_decode->3DESDecrypt
     * @param string    $encryptedData
     * @return string
     */
    public static function decrypt($encryptedData, $key = 'shoufuyou') {
        return SfyTripleDESCrypt::decrypt(base64_decode(urldecode($encryptedData)), $key);
    }

    /**
     * 签名，连接biz_content， charset， merchant_code， method， timestamp， version，$private_key， 然后进行sha256哈希
     * @param array     $postArray
     * @return string
     */
    private static function sign(array $postArray) {
        ksort($postArray);
        $sign = '';
        foreach ($postArray as $value) {
            $sign .= $value;
        }
        return hash('sha256', $sign . SfyConfig::PRIVATE_KEY);
    }

    /**
     *
     * @param string    $postData   需要post的postData数据
     * @param int       $expire     url执行超时时间，默认30s
     * @throws
     */
    private static function postDataByCurl($postData, $expire = '30') {
        $url = SfyConfig::API_URL;
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $expire);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);//严格校验
        if (defined('SfyConfig::CA_CERT_PATH')) {
            $path = (string)SfyConfig::CA_CERT_PATH;
            if ($path !== '') {
                //$path = realpath(SfyConfig::CA_CERT_PATH);
                $path = 'D:\shoufuyou\shoufuyou-sdk\cacert.pem';
                if ($path === false) {
                    throw new Exception("SfyConfig::CA_CERT_PATH 配置的文件 '"
                        . SfyConfig::CA_CERT_PATH . "' 不存在.");
                }
                curl_setopt($ch, CURLOPT_CAINFO, $path);
            }
        }
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        //运行curl
        //echo (curl_exec($ch));exit;
        $data = json_decode(curl_exec($ch), TRUE);
        //返回结果
        if (is_array($data)) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            return false;
        }
    }
}
