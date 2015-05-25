<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class HigoException extends Exception
{
    protected $code = -1;
    protected $message = '';

    public function __construct($code)
    {
        $this->code = $code;
        $this->message = HigoErrorCode::getMessage($code);
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */
