
> 没有依赖任何框架的函数，因此所有的框架基本都兼容
>
> 目前测试通过的框架thinkphp全家桶，laravel框架。


# 安装

~~~
composer require tp5er/file dev-master
~~~

# 文件上传

~~~
$file = $_FILES['image'];
$path =  './uploads';
$info = (new \tp5er\FileApp())->fileupload($file, $path);


//输出  jpg
echo $info->getExtension();

//输出  ea307c4cdc7f7e91e8578caad1db1240.jpg
echo $info->getFilename();

~~~

# 安全校验

~~~
$info = (new \tp5er\FileApp())->fileupload($file, $path,['type'=>"image/png",'size'=>15678,'ext'=>'jpg,png,gif']);
~~~



# COS上传

~~~
//首先安装 composer require qcloud/cos-sdk-v5

上传方法
$info = (new \tp5er\FileApp(new \tp5er\drive\Cos([
    'SecretId'=>'',
    'SecretKey'=>"",
    'bucket'=>"",
    'region'=>''
])))->fileupload($file, $path);
~~~





# 自定义上传驱动（COS）

官方文档：https://cloud.tencent.com/document/product/436/12266

包地址：https://packagist.org/packages/qcloud/cos-sdk-v5



## 安装

~~~
composer require qcloud/cos-sdk-v5
~~~

## 接口实现类

~~~
<?php
class Cos implements \tp5er\FileInterface
{
    public function save(&$file, &$fullname)
    {
		//初始化用户身份信息API密钥(secretId, secretKey)请参照  https://console.cloud.tencent.com/cam/capi
        $secretId = "36位字符串";
        $secretKey = "32位字符串";
        
        //设置bucket的区域, COS地域的简称请参照  https://cloud.tencent.com/document/product/436/6224
        $bucket = "name-APPID";
        $region = "地区";
        
        try{
            $cosClient =  new \Qcloud\Cos\Client([
                'region' => $region,
                'schema' => 'https',
                'credentials' => [
                    'secretId' => $secretId,
                    'secretKey' => $secretKey
                ]
            ]);
            $key = $fullname;
            $body = file_get_contents($file['tmp_name']);
            $result = $cosClient->putObject(array('Bucket' => $bucket, 'Key' => $key, 'Body' => $body));
            if (!isset($result['Location']) && !isset($result['Key'])){
                trigger_error("upload write error");
                return false;
            }
            return true;
        }catch (Exception $exception){
            throw  new  \Exception($exception);
        }
    }
}
~~~

## 传入接口实现类实现上传功能

~~~
$info = (new \tp5er\FileApp(new \Cos()))->fileupload($file, $path);
~~~



# 官方sdk汇总

## 阿里云oss

https://help.aliyun.com/document_detail/85580.html

## 七牛云

https://developer.qiniu.com/kodo/sdk/1241/php

## 京东云

https://docs.jdcloud.com/cn/object-storage-service/sdk-php



## 请私人助教：
QQ：1751212020
