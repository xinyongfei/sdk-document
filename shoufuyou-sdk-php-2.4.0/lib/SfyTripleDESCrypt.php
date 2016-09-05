<?php
/**
 * 3DES加解密类
 * @author shoufuyou phper
 * 
 */
class SfyTripleDESCrypt {
    /**
     * 
     * @param string    $unencryptedData    待加密的串
     * @param string    $key                加密key
     * @return string   $encryptedData      加密后的串
     */
    public static function encrypt($unencryptedData, $key = 'shoufuyou') { // 数据加密
        $size = mcrypt_get_block_size(MCRYPT_3DES, 'ecb');
        $input = self::pkcs5Pad($unencryptedData, $size);
        $key = str_pad($key, 24, '0');
        $td = mcrypt_module_open(MCRYPT_3DES, '', 'ecb', '');
        $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        @mcrypt_generic_init($td, $key, $iv);
        $encryptedData = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return $encryptedData;
    } 

    /**
     * 
     * @param string    $encryptedData  待解密的串
     * @param string    $key            解密key
     * @return string   $decryptedData  解密后的串
     */
    public static function decrypt($encryptedData, $key = 'shoufuyou') { // 数据解密
        $key = str_pad($key, 24, '0');
        $td = mcrypt_module_open(MCRYPT_3DES, '', 'ecb', '');
        $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        $ks = mcrypt_enc_get_key_size($td);
        @mcrypt_generic_init($td, $key, $iv);

        $decryptedData = mdecrypt_generic($td, $encryptedData);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return self::pkcs5Unpad($decryptedData);
    } 

    /**
     * 
     * @param string    $text
     * @param int       $blockSize
     * @return string
     */
    private static function pkcs5Pad($text, $blockSize) {
        $pad = $blockSize - (strlen($text) % $blockSize);
        return $text . str_repeat(chr($pad), $pad);
    } 

    /**
     * 
     * @param string    $text
     * @return boolean|string
     */
    private static function pkcs5Unpad($text) {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)) {
            return false;
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return false;
        }
        return substr ($text, 0, -1 * $pad);
    }

    /**
     * 
     * @param string    $data
     * @return string
     */
    private static function paddingPKCS7($data) {
        $blockSize = mcrypt_get_block_size(MCRYPT_3DES, MCRYPT_MODE_CBC);
        $paddingChar = $blockSize - (strlen($data) % $blockSize);
        $data .= str_repeat(chr($paddingChar), $paddingChar);
        return $data;
    }
}