<?php


namespace tp5er;


use Throwable;

class FileException
{
    public function __construct($message, $options = [])
    {
        $func = isset($options['function']) ? $options['function'] : "Exception";
        $code = isset($options['code']) ? $options['code'] : $func == 'Exception' ? E_USER_NOTICE : 0;
        $this->$func($message, $code);
    }

    public function Exception($message = "", $code = 0, Throwable $previous = null)
    {
        throw  new \Exception($message, $code, $previous);
    }

    public function trigger($error_msg, $error_type = E_USER_NOTICE)
    {
        trigger_error($error_msg, $error_type);
    }

}