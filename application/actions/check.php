<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class checkAction extends BaseAction
{
    private $client_id = '';
    private $account_mobile   = '';
    private $account_password = '';
    private $state = '';

    protected function beforeExecute()
    {
        // 仅支持 POST 提交
        if ('POST' != $this->getRequest()->getMethod()) {
            throw new HigoException(HigoErrorCode::INVALID_REQUEST_WITHOT_POST);
        }

        if (! isset($_SESSION['auth_data']['client_id'])) {
            throw new HigoException(HigoErrorCode::LOST_CLIENT_ID);
        }
        $this->client_id = $_SESSION['auth_data']['client_id'];
        $this->state     = $_SESSION['auth_data']['state'];

        $account_mobile   = $this->postParam('account_mobile');
        $account_password = $this->postParam('account_password');
        if (empty($account_mobile) || empty($account_password)) {
            throw new HigoException(HigoErrorCode::EMPTY_MOBILE_OR_PASSWORD);
        }
        $this->account_mobile   = $account_mobile;
        $this->account_password = $account_password;
    }

    public function run($arg = null)
    {
        $account_id = HigoInternalApiService::checkLogin($this->account_mobile, $this->account_password);
        if (false === $account_id) {
            $redirect = sprintf('/Oauth2/login?client_id=%s&state=%s&error=%s', $this->client_id, $this->state, HigoErrorCode::SERVICE_IS_UNAVAILABLE);
        } elseif (-1 == $account_id) {
            $redirect = sprintf('/Oauth2/login?client_id=%s&state=%s&error=%s', $this->client_id, $this->state, HigoErrorCode::ACCOUNT_OR_PASSWORD_IS_INCORRECT);
        } else {
            $_SESSION['auth_data']['account_id'] = $account_id;

            // debug data
            //$_SESSION['auth_data']['account_id']   = '123456';
            //$_SESSION['auth_data']['access_token'] = md5('higo-oauth2');

            $redirect = sprintf('/Oauth2/authorize?response_type=code&client_id=%s&state=%s', $this->client_id, $this->state);
        }

        $this->redirect($redirect);
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

