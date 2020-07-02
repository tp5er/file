安装
~~~
composer require tp5er/file dev-master
~~~

文件上传

~~~
$file = $_FILES['image'];
$path = ROOT_PATH . 'public' . DS . 'uploads';
$info = (new \tp5er\FileApp())->fileupload($file, $path);
~~~

