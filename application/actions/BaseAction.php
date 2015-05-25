<?php
/**
 * @describe: 将 action 抽象出一个基类,把常用的方法进行简化.
 * @author: Yongjie
 * */
abstract class BaseAction extends Yaf_Action_Abstract
{
    abstract public function run($arg = null);

    protected $code     = HigoErrorCode::SUCCESS;
    protected $message  = '';
    protected $data     = null;

    protected $storage = null;
    protected $server  = null;

    protected $current_controller   = null;
    protected $current_action_name  = null;

    private function _init()
    {
        /**
         * 一些常用公共数据
         */
        $current_controller  = strtolower($this->getRequest()->getControllerName());
        $current_action_name = strtolower($this->getRequest()->getActionName());
        $this->current_controller  = $current_controller;
        $this->current_action_name = $current_action_name;

        try {
            $config = Yaf_Registry::get('config')->mysql->oauth2->toArray();
            //print_r($config);exit;

            // $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
            $pdo_cfg = array(
                'dsn' => sprintf('mysql:dbname=%s;host=%s;port=%d',
                    $config['master']['dbname'],
                    $config['master']['host'],
                    $config['master']['port']
                ),
                'username' => $config['master']['username'],
                'password' => $config['master']['password'],
            );
            $storage = new OAuth2\Storage\Pdo($pdo_cfg);

            $oauth_cfg = array(
                'access_lifetime'    => 172800, // 暂定为 2d
            );
            // Pass a storage object or array of storage objects to the OAuth2 server class
            $server = new OAuth2\Server($storage, $oauth_cfg);

            // Add the "Client Credentials" grant type (it is the simplest of the grant types)
            $server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));

            // Add the "Authorization Code" grant type (this is where the oauth magic happens)
            $server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));

            //添加对refresh token的支持,每次重新生成refresh_token
            $server->addGrantType(new OAuth2\GrantType\RefreshToken($storage, array('always_issue_new_refresh_token' => true)));

            $this->storage = $storage;
            $this->server  = $server;
        } catch (Exception $e) {
            // 记录无法连接数据库的错误日志详细 trace
            SeasLog::error((string)$e);
            throw new HigoException(HigoErrorCode::DB_CAN_NOT_CONNECT);
        }

        if ('api' == $current_controller) {
            if (! $this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
                throw new HigoException(HigoErrorCode::INVALID_ACCESS_TOKEN);
            }

            OAuth2Service::checkPermission($this->server, $current_controller, $current_action_name);
        }

        return true;
    }

    protected function beforeExecute()
    {

    }

    protected function afterExecute()
    {
        return true;
    }

    /**
     * @param mixed $arg,...
     * @return mixed
     */
    public function execute($arg = null)
    {
        try {
            $this->_init();
            $this->beforeExecute();
            $this->run($arg);
            $this->afterExecute();
        } catch (HigoException $e) {
            $this->setException($e);
        } catch (Exception $e) {
            $this->code    = $e->getCode();
            $this->message = $e->getMessage();
            $this->data    = new ArrayObject();

            return $this->jsonResponse();
        }
    }

    /**
     * @param $name
     * @param string $defaultValue
     * @return string
     */
    public function getRequestParam($name, $default = '')
    {
        return isset($_REQUEST[$name]) ? Util::escape($_REQUEST[$name]) : $default;
    }

    /**
     * @param $name
     * @param string $defaultValue
     * @return string
     */
    public function getParam($name, $default = '')
    {
        return isset($_GET[$name]) ? Util::escape($_GET[$name]) : $default;
    }

    /**
     * @param $name
     * @param string $defaultValue
     * @return string
     */
    public function postParam($name, $default = '')
    {
        return isset($_POST[$name]) ? Util::escape($_POST[$name]) : $default;
    }

    public function postUnescape($name, $default = '')
    {
        return isset($_POST[$name]) ? $_POST[$name] : $default;
    }

    // 简化 assign 模板变量的操作
    public function assign($key, $value)
    {
        $this->getView()->assign($key, $value);
    }

    protected function jsonResponse()
    {
        header('Content-Type: application/json; charset=utf-8');

        $res = array(
            'code'    => $this->code,
            'message' => $this->message,
            'data'    => is_null($this->data) ? new ArrayObject() : $this->data ,
        );
        echo json_encode($res);

        return false;
    }

    protected function isMobilePlatform()
    {
        if (stristr($_SERVER['HTTP_USER_AGENT'], 'Android')) {
            return true;
        } elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'iPhone')) {
            return true;
        }
        return false;
    }

    protected function setException(HigoException $ex)
    {
        $this->code = $ex->getCode();
        $this->message = $ex->getMessage();
        return $this->jsonResponse();
    }

    /**
     * 将oauth2返回结果转换成同意格式
     * @param array $data
     * @return boolean
     */
    protected function convertOauth2Info($data){
        if(isset($data['error']) && !empty($data['error'])){
            $this->code = HigoErrorCode::OAUTH2_COMMON_ERROR;
        }else{
            $this->code = HigoErrorCode::SUCCESS;
        }
        $this->message = HigoErrorCode::getMessage($this->code);
        $this->data = $data;
        return $this->jsonResponse();
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */
