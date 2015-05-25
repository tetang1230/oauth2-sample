<?php
/**
 * @describe:
 * @author: YongjieYang
 * */
class Oauth2Controller extends Yaf_Controller_Abstract
{

    public $actions = array(
        'login'     => 'actions/login.php',
        'check'     => 'actions/check.php',
        'authorize' => 'actions/authorize.php',
        'token'     => 'actions/token.php',
        // 刷新 access_token
        'refresh_token' => 'actions/refresh_token.php',
    );

    public function init()
    {
        session_start();
        // 关闭自动渲染模板
        Yaf_Dispatcher::getInstance()->autoRender(false);
        //Yaf_Dispatcher::getInstance()->disableView();
    }

    public function IndexAction()
    {
        $output = array(
            'code' => 0,
            'message' => 'ok',
            'data' => new ArrayObject(),
        );
        echo json_encode($output);
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */
