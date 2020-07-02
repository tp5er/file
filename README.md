安装
~~~
composer require tp5er/file dev-master
~~~

文件上传

~~~
$stor = new \tp5er\FileApp();
$info =$stor->fileupload($_FILES['image'],ROOT_PATH . 'public' . DS . 'uploads');
~~~

