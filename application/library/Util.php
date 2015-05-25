<?php
/**
 * @describe:
 * @author: Yongjie
 * */
class Util
{
    public static function isBinary($str)
    {
        $blk = substr($str, 0, 512);
        return (substr_count($blk, "\x00") > 0);
    }

    /**
     * @brief escape 防 xss 攻击
     *
     * @param: $str
     *
     * @return: string
     */
    public static function escape($str)
    {
        return Util::isBinary($str) ? addslashes($str) : htmlspecialchars(trim($str), ENT_QUOTES);
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */
