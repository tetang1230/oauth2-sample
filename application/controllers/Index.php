<?php
/**
 * @name IndexController
 * @author MLS
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends Yaf_Controller_Abstract
{

    /**
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/oauth2/index/index/index/name/MLS 的时候, 你就会发现不同
     */
    public function indexAction($name = "Stranger")
    {
        echo 'hi.';
        //1. fetch query
        $get = $this->getRequest()->getQuery("get", "default value");

        //2. fetch model
        $model = new SampleModel();

        //3. assign
        $this->getView()->assign("content", $model->selectSample());
        $this->getView()->assign("name", $name);

        //4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
        return true;
    }

    public function debugAction()
    {
        // debug action
        // 打印出 mysql->oauth2 的配置信息
        //$config = Yaf_Registry::get('mysql')->oauth2->toArray();
        //print_r($config);
        //
        
        /*
        $dsn      = 'mysql:dbname=oauth;host=localhost';
        $username = 'root';
        $password = '123456';
        
        // error reporting (this is a demo, after all!)
        ini_set('display_errors',1);
        error_reporting(E_ALL);
        
        // Autoloading (composer is preferred, but for this example let's just do this)
        //require_once('oauth2-server-php/src/OAuth2/Autoloader.php');
        OAuth2\Autoloader::register();
        
        // $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
        $storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));
        //return false;
        */
    }
}
