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
     * @var
     */
    protected $validate;
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

    /**
     * @param FileInterface $interface
     * @return $this
     */
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

        //检查指定的文件是否是通过 HTTP POST
        if (!is_uploaded_file($this->filename)) {
            return new FileException("upload illegal files");
        }
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
     * @param $validate
     * @return $this
     */
    public function validate($validate)
    {
        $this->validate = $validate;
        return $this;
    }

    /**
     * @return bool
     */
    public function check($rule = [])
    {

        $rule = $rule ?: $this->validate;

        /* 检查文件大小 */
        if (isset($rule['size']) && !$this->checkSize($rule['size'])) {
            return new FileException("filesize not match");
        }
        //检查文件 Mime 类型
        if (isset($rule['type']) && !$this->checkMime($rule['type'])) {
            return new FileException("mimetype to upload is not allowed");
        }
        if (isset($rule['ext']) && !$this->checkExt($rule['ext'])) {
            return new FileException("extensions to upload is not allowed");
        }
        //检查图像文件
        if (!$this->checkImg()) {
            return new FileException("illegal image files");
        }
        return true;
    }

    /**
     * @param $size
     * @return bool
     */
    protected function checkSize($size)
    {
        return $this->getInfo("size") <= $size;
    }

    /**
     * @param $ext
     * @return bool
     */
    protected function checkExt($ext)
    {
        if (is_string($ext)) {
            $ext = explode(',', $ext);
        }
        return in_array(strtolower(FileInfo::FileSuffix($this->getInfo('name'))), $ext);
    }

    /**
     * @return bool
     */
    protected function checkImg()
    {

        $images = FileInfo::GetImageSize($this->filename);

        if (isset($images[2])) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    protected function checkMime($mime)
    {
        $mime = is_string($mime) ? explode(',', $mime) : $mime;
        return in_array(strtolower(FileInfo::FileType($this->filename)), $mime);
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