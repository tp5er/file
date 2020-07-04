<?php

namespace tp5er;

use tp5er\drive\Local;

class FileApp
{
    /**
     * @var FileInterface|null
     */
    protected $drive = null;

    /**
     * Storage constructor.
     * @param FileInterface|null $drive
     */
    public function __construct(FileInterface $drive = null)
    {
        if(is_null($drive)){
            $this->drive = new Local();
        }else{
            $this->drive = $drive;
        }
    }

    /**
     * @param $file
     * @param $fullpath
     * @return bool|wImage
     */
    public function fileupload($file, $fullpath,$rule=[]){
        return (new File($file))->setDrive($this->drive)->validate($rule)->move($fullpath);
    }

}