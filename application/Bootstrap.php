<?php
/**
 * @name Bootstrap
 * @author MLS
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf_Bootstrap_Abstract
{
    public function _initConfig()
    {
        //把配置保存起来
        $arrConfig = Yaf_Application::app()->getConfig();
        Yaf_Registry::set('config', $arrConfig);

        // mysql 配置文件
        //$mysql_ini  = new Yaf_Config_Ini(APPLICATION_PATH . '/conf/mysql.ini');
        //$mysql_conf = $mysql_ini->mysql;
        //Yaf_Registry::set('mysql', $mysql_conf);
    }

    public function _initPlugin(Yaf_Dispatcher $dispatcher)
    {
        //注册一个插件
        $objSamplePlugin = new SamplePlugin();
        $dispatcher->registerPlugin($objSamplePlugin);
    }

    public function _initRoute(Yaf_Dispatcher $dispatcher)
    {
        //在这里注册自己的路由协议,默认使用简单路由
    }

    // 加入公共的基类 action
    public function _initBaseAction(Yaf_Dispatcher $dispatcher)
    {
        Yaf_loader::import(APPLICATION_PATH . '/application/actions/BaseAction.php');
    }

    /**
     * 加载 service 层
     * */
    public function _initService(Yaf_Dispatcher $dispatcher)
    {
        Yaf_loader::import(APPLICATION_PATH . '/application/service/autoload.php');
    }

    /**
     * 加载 oauth2 类库
     * */
    public function _initOauth2(Yaf_Dispatcher $dispatcher)
    {
        Yaf_loader::import(APPLICATION_PATH . '/contrib/oauth2-server-php-1.7.0/vendor/autoload.php');
    }

    public function _initView(Yaf_Dispatcher $dispatcher)
    {
        //在这里注册自己的view控制器，例如smarty,firekylin
    }
}
