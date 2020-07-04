<?php

namespace tp5er\drive;

use tp5er\FileInfo;
use tp5er\FileInterface;
use tp5er\FileException;

class Local implements FileInterface
{
    /**
     * @param $temp
     * @param $fullname
     * @return bool
     */
    public function save(&$file, &$filename)
    {
        $path = dirname($filename);
        if (false === $this->checkPath($path)) {
            new FileException("directory {$path} creation failed");
//            trigger_error("directory {$path} creation failed");
            return false;
        }
        if (!FileInfo::IsFileFuncArray($file)) {
            trigger_error($file);
            return false;
        }
        //io写入
        if (!file_put_contents($filename, file_get_contents($file['tmp_name'], FILE_APPEND))) {
//            trigger_error("upload write error");
            new FileException("upload write error");

            return false;
        }
        return true;
    }

    /**
     * @param $path
     * @return bool
     */
    protected function checkPath($path)
    {
        if (is_dir($path) || self::mkdirs($path)) {
            return true;
        }
        return false;
    }

    /**
     * @param $dir
     * @return bool
     */
    protected static function mkdirs($dir)
    {
        if (!is_dir($dir)) {
            if (!self::mkdirs(dirname($dir))) {
                return false;
            }
            if (!mkdir($dir, 0755)) {
                return false;
            }
        }
        return true;
    }
}