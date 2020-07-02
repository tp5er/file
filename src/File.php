<?php

namespace tp5er;

class File
{
    /**
     * @var array 上传文件信息
     */
    protected $info;
    /**
     * @var string 当前完整文件名
     */
    protected $filename;
    /**
     * @var array
     */
    protected $suffix = ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf'];

    /**
     * @var FileInterface|null
     */
    protected $drive = null;

    /**
     * wImage constructor.
     * @param $file
     * @param string $mode
     */
    public function __construct($file)
    {
        if (is_array($file) && FileInfo::IsFileFuncArray($file)) {
            $this->info = $file;
            $file = $file['tmp_name'];
        }
        $this->filename = $file;
    }

    public function setDrive(FileInterface $interface)
    {
        $this->drive = $interface;
        return $this;
    }

    /**
     * @param $path
     * @return bool|wImage
     */
    public function move($path)
    {
        //图片文件检查
        $this->check();
        // 文件保存命名规则
        $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $saveName = FileInfo::buildFileName($this->getInfo('name'));
        $filename = $path . $saveName;
        // 调用接口save方法是实现io保存
        if (!$this->drive->save($this->info, $filename)) {
            return false;
        }
        $file = (new self($filename))->setDrive($this->drive)->setInfo($this->getInfo());
        return $file;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return FileInfo::FileBaseName($this->filename);
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return FileInfo::FileExtension($this->filename);
    }

    /**
     * @return bool
     */
    public function check()
    {
        //检查指定的文件是否是通过 HTTP POST
        if (!is_uploaded_file($this->filename)) {
            trigger_error("upload illegal files");
            return false;
        }
        //检查文件 Mime 类型
        if (!$this->checkMime()) {
            trigger_error("extensions to upload is not allowed");
            return false;
        }
        //检查图像文件
        if (!$this->checkImg()) {
            trigger_error("illegal wimage files");
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function checkImg()
    {
        $images = FileInfo::GetImageSize($this->filename);
        $infosuffix = FileInfo::FileSuffix($this->getInfo('name'));
        if (in_array($infosuffix, $this->suffix) || isset($images[2])) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    protected function checkMime()
    {
        $infotype = $this->getInfo("type");
        $systemtype = FileInfo::FileType($this->filename);
        return strcmp($infotype, $systemtype) == 0 ? true : false;
    }

    /**
     * @param $info
     * @return $this
     */
    public function setInfo($info)
    {
        $this->info = $info;
        return $this;
    }

    /**
     * @param string $name
     * @return array|mixed
     */
    protected function getInfo($name = '')
    {
        return isset($this->info[$name]) ? $this->info[$name] : $this->info;
    }

}