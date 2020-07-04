<?php


namespace tp5er\drive;


use think\Exception;
use tp5er\FileException;
use tp5er\FileInterface;

class Cos implements FileInterface
{

    /**
     * @var
     */
    public static $conf;
    /**
     * @var
     */
    public static $cosClient;

    /**
     * Cos constructor.
     * @param $conf
     */
    public function __construct($conf)
    {
        if (!array_key_exists("SecretId", $conf) || !array_key_exists("SecretKey", $conf)) {
            return new FileException("SecretId  && SecretKey Undefined");
        }
        if (!array_key_exists("bucket", $conf)) {
            return new FileException("bucket Undefined");
        }
        if (!array_key_exists("region", $conf)) {
            return new FileException("region Undefined");
        }
        if (!class_exists("\Qcloud\Cos\Client")) {
            return new FileException("composer require qcloud/cos-sdk-v5");
        }
        self::$conf = $conf;
    }

    /**
     * @return mixed
     */
    public static function CosClientIns($conf)
    {
        if (empty(self::$cosClient)) {
            self::$cosClient = new \Qcloud\Cos\Client(
                [
                    'region' => $conf['region'],
                    'schema' => 'https',
                    'credentials' => [
                        'secretId' => $conf['SecretId'],
                        'secretKey' => $conf['SecretKey']
                    ]
                ]);
            return self::$cosClient;
        }
        return self::$cosClient;
    }

    /**
     * @param $file
     * @param $filename
     * @return bool|mixed|FileException
     */
    public function save(&$file, &$filename)
    {
        $conf = self::$conf;
        try {
            $result = self::CosClientIns($conf)->putObject(array('Bucket' => $conf['bucket'], 'Key' => $filename, 'Body' => file_get_contents($file['tmp_name'])));
            if (!isset($result['Location']) && !isset($result['Key'])) {
                return new FileException("upload write error");
                return false;
            }
            return true;
        } catch (\Exception $e) {
            return new FileException($e);
        }
    }

}