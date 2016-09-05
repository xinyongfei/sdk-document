<?php
/**
 * 配置信息类
 * @author shoufuyou phper
 *
 * CHARSET          编码格式
 * VERSION          版本号
 * URL              接口url
 * MERCHANT_CODE    商户号
 * PRIVATE_KEY      私钥
 */
Class SfyConfig {
    CONST CHARSET        = 'UTF-8';
    CONST VERSION        = '2.0';
    //CONST API_URL       = 'https://api.shoufuyou.com/service';
    CONST API_URL        = 'http://apitest.shoufuyou.com/service';
    CONST MERCHANT_CODE  = '1000000000';
    CONST PRIVATE_KEY    = '012345678901234567890123';
    //CONST PAY_URL       = 'https://pay.shoufuyou.com/v2/';
    CONST PAY_URL        = 'http://test1-pay.shoufuyou.com/v2/';
    CONST PAY_MOBILE_URL = 'http://test1-pay-mobile.shoufuyou.com/v2/';
    // CONST CA_CERT_PATH  = '../cacert.pem'; Windows环境需打开
}
