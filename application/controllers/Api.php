<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class ApiController extends Yaf_Controller_Abstract
{
    public function init()
    {
        // 关闭自动渲染模板
        Yaf_Dispatcher::getInstance()->autoRender(false);
    }


    public $actions = array(
        'index' => 'actions/api/index.php',
        'get_userinfo' => 'actions/api/get_userinfo.php',
    );
}
/* vi:set ts=4 sw=4 et fdm=marker: */
