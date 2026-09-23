<?php

namespace App\library;

class CCAvenueCrypto
{
    public static function encrypt($plainText, $key)
    {
        $key = self::hextobin(md5($key));
        $iv = pack("C*", 0x00, 0x01, 0x02, 0x03,
                        0x04, 0x05, 0x06, 0x07,
                        0x08, 0x09, 0x0a, 0x0b,
                        0x0c, 0x0d, 0x0e, 0x0f);
        $encryptedText = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return bin2hex($encryptedText);
    }

    public static function decrypt($encryptedText, $key)
    {
        $key = self::hextobin(md5($key));
        $iv = pack("C*", 0x00, 0x01, 0x02, 0x03,
                        0x04, 0x05, 0x06, 0x07,
                        0x08, 0x09, 0x0a, 0x0b,
                        0x0c, 0x0d, 0x0e, 0x0f);
        $encryptedText = self::hextobin($encryptedText);
        return openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
    }

    private static function hextobin($hexString)
    {
        $binString = "";
        $length = strlen($hexString);
        for ($i = 0; $i < $length; $i += 2) {
            $binString .= pack("H*", substr($hexString, $i, 2));
        }
        return $binString;
    }
}
