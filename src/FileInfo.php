<?php


namespace tp5er;


class FileInfo
{
    /**
     * @param $filename
     * @return mixed
     */
    public static function FileSuffix($filename)
    {
        return self::PathInfo($filename,PATHINFO_EXTENSION);
    }

    /**
     * @return mixed
     */
    public static function FileBaseName($filename){
        return self::PathInfo($filename,PATHINFO_BASENAME );
    }

    /**
     * @return mixed
     */
    public static function FileExtension($filename){
        return self::PathInfo($filename,PATHINFO_EXTENSION  );
    }

    /**
     * @param $filename
     * @param null $options
     * @return mixed
     */
    public static function PathInfo($filename, $options=null){
        if (is_null($options)){
            return pathinfo($filename);
        }
        return pathinfo($filename,$options);
    }
    /**
     * @param $filename
     * @return mixed
     */
    public static function FileType($filename)
    {
        return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filename);
    }

    /**
     * @param $filename
     * @return array|false
     */
    public static function GetImageSize($filename)
    {
        return getimagesize($filename);
    }

    /**
     * @param $oldfilename
     * @return string
     */
    public static function buildFileName($oldfilename)
    {
        $savename = md5(microtime(true));
        return $savename . "." . self::FileSuffix($oldfilename);
    }

    /**
     * @param $file
     * @return bool
     */
    public static function IsFileFuncArray($file)
    {
        if (array_key_exists("name", $file)
            && array_key_exists("name", $file)
            && array_key_exists("tmp_name", $file)
            && array_key_exists("size", $file)
            && array_key_exists("error", $file)
        ) {
            return true;
        }
        return false;
    }
}