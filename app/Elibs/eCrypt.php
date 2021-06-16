<?php

namespace App\Elibs;

use phpseclib\Crypt;

class eCrypt
{

    static  $rsa = null; // rsa with public key
    static  $rsa_private = null;
    static $secret_key = 'E57CEF25535C819E929BA1C560A9852A';
    static $method = 'AES-256-CBC';

    static $publickey = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDOI2DUDC7QcLpRPYWvi6QkX
KuPnSHieSe5B89GTGCqQiE0Z9rJPBgMpdZoVwwOYFP7pelFdIgiBkduKP7e56
xgPDMYXOaDUus4H1YUjORYPlVWnn5bME9N0tqR6cuTBIrpBgioPBppA+kfP1M
kBOtNQZKGRTCS3jh/eQxs0XqXxQIDAQAB
-----END PUBLIC KEY-----';

    static $privatekey = '-----BEGIN RSA PRIVATE KEY-----
MIICeAIBADANBgkqhkiG9w0BAQEFAASCAmIwggJeAgEAAoGBAM4jYNQMLtBwul
E9ha+LpCRcq4+dIeJ5J7kHz0ZMYKpCITRn2sk8GAyl1mhXDA5gU/ul6UV0iCIG
R24o/t7nrGA8Mxhc5oNS6zgfVhSM5Fg+VVaeflswT03S2pHpy5MEiukGCKg8Gm
kD6R8/UyQE601BkoZFMJLeOH95DGzRepfFAgMBAAECgYB5FyswJR/y7eVlB8Da
SCtZ+N4G9QUMpqMA/Gd0IjW+ji43PLK294ghFeIHVOsOHuP2tZV8aWH2qr2XJp
nhkzIcH9yhRaCb5y+UgMRSwK2jNExgxoIpAkkFXVmYd1NAQ8myz5B3iFY+sqzQ
JzulYFaHo7D/W+tqB0kg6zc0WYz7mQJBAO/YhtOak/x2bDJu6qMVbzrVXa9xf3
uegX2SbWaMYTBCcX8mDJJ65a9F/1m6Bw7UcUIs10oATBa76uk8Iy1h0DsCQQDc
BahbOnMHmCKYYT32Tpyb7VAXlJ8jLohv8NHuPgpSuHvT1xwuvPpCzlQtLUBCCv
F5hXLjcauBEmxXlT3Cdrf/AkEApc9gLkuQARnxZNBPP91inx7AfLiPNGv5A1HG
df2Ydt+ITSmFyYJS5WATzvkPRg5SGjibwVoBQDo7hXCAtTAI3wJBANUiEeVVqG
pZ4GFWCYzYt/KAH07IZKPTBs3RLbsolB1volwii6Vm4NLoRjiBFjcjnKlIMXPM
AAQHUbZB1tLS6AcCQQCCed8+pChCFr5WDmMz1ezsLe3VJ8yC6BP2+jRfORFcrt
P3Phl2uRhs3Em1xuvWqkdmw9rv5Ana96MJ7GqbQjde
-----END RSA PRIVATE KEY-----';

    public function __construct()
    {

    }

    static function encrypt($str)
    {
        if(!self::$rsa)
        {
            self::getEncoder();
        }

        return  base64_encode(self::$rsa->encrypt($str));
    }

    static function decrypt($str)
    {
        if(!self::$rsa_private)
        {
            self::getDecoder();
        }

        return  self::$rsa_private->decrypt(base64_decode($str));
    }

    static public function getEncoder()
    {

        self::$rsa = new Crypt\RSA();
        self::$rsa->loadKey(self::$publickey);
        self::$rsa->setSignatureMode(Crypt\RSA::SIGNATURE_PKCS1);
        self::$rsa->setEncryptionMode(Crypt\RSA::ENCRYPTION_PKCS1);

        return self::$rsa;

    }
    static public function getDecoder()
    {

        self::$rsa_private = new Crypt\RSA();
        self::$rsa_private->loadKey(self::$privatekey);
        self::$rsa_private->setSignatureMode(Crypt\RSA::SIGNATURE_PKCS1);
        self::$rsa_private->setEncryptionMode(Crypt\RSA::ENCRYPTION_PKCS1);

        return self::$rsa_private;

    }

    static function setPublicKey($publickey)
    {
        self::$publickey = $publickey;
        self::getEncoder();
    }
    static function setPrivateKey($privatekey)
    {
        self::$privatekey = $privatekey;
        self::getDecoder();

    }

    static function encryptAES($str)
    {
        $iv = substr(self::$secret_key, 0, 16);

        return openssl_encrypt($str, self::$method, self::$secret_key, 0, $iv);

    }

    static function decryptAES($str)
    {
        $iv = substr(self::$secret_key, 0, 16);

        return openssl_decrypt($str, self::$method, self::$secret_key, 0, $iv);

    }

}
