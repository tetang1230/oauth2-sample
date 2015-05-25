<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class loginAction extends BaseAction
{
    private $client_id = '';
    private $state = '';

    protected function beforeExecute()
    {
        // 每次重新登陆,重置以前的会话
        $_SESSION['auth_data'] = array();

        //$this->jsonResponse();
        if (! isset($_GET['client_id'])) {
            throw new HigoException(HigoErrorCode::LOST_CLIENT_ID);
        }
        $this->client_id = $this->getParam('client_id');

        if (! isset($_GET['state'])) {
            throw new HigoException(HigoErrorCode::LOST_STATE);
        }
        $this->state = $this->getParam('state');
    }

    public function run($arg = null)
    {
        $client_detail = $this->storage->getClientDetails($this->client_id);
        //var_dump($client_detail);
        if (! $client_detail) {
            throw new HigoException(HigoErrorCode::INVALID_CLIENT_ID);
        }

        $auth_data = array(
            'client_id' => $this->client_id,
            'state'     => $this->state,
        );
        $_SESSION['auth_data'] = $auth_data;

        $error_msg = '';
        $error = $this->getParam('error', '');
        if ($error) {
            $error_msg = HigoErrorCode::getMessage($error);
        }
        $this->assign('error_msg', $error_msg);

        $this->getView()->display('oauth2/login.phtml');
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */
