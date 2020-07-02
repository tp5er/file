安装
~~~
composer require tp5er/file dev-master
~~~

文件上传

~~~
$file = $_FILES['image'];
$path = ROOT_PATH . 'public' . DS . 'uploads';
$info = (new \tp5er\FileApp())->fileupload($file, $path);


//输出  jpg
echo $info->getExtension();

//输出  ea307c4cdc7f7e91e8578caad1db1240.jpg
echo $info->getFilename();
~~~





