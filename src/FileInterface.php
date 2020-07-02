<?php

namespace tp5er;

interface  FileInterface
{
    /**
     * @param $temp
     * @param $fullname
     * @return mixed
     */
    public function save(&$file, &$filename);
}