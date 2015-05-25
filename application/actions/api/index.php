<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class indexAction extends BaseAction
{
    public function run($arg = null)
    {
        throw new HigoException(HigoErrorCode::ACCESS_DENIED);
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

