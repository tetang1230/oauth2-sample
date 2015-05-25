<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class authorizeAction extends BaseAction
{
    private $client_id = '';

    protected function beforeExecute()
    {
        if (! isset($_SESSION['auth_data'])
            || ! isset($_SESSION['auth_data']['client_id'])
            || empty($_SESSION['auth_data']['client_id'])
            || $_SESSION['auth_data']['client_id'] != $this->getParam('client_id')) {
            throw new HigoException(HigoErrorCode::AUTH_DATA_HAS_TAMPERED);
        }
        $this->client_id = $_SESSION['auth_data']['client_id'];
    }

    public function run($arg = null)
    {
        $request  = OAuth2\Request::createFromGlobals();
        $response = new OAuth2\Response();

        if (empty($_POST)) {
            $this->assign('client_id', $this->client_id);
            $this->getView()->display('oauth2/confirm.phtml');

            return false;
        }

        //SeasLog::debug(print_r($request, true));

        $client_detail = $this->storage->getClientDetails($this->client_id);
        //SeasLog::debug(print_r($client_detail, true));

        $redirect_uri = $client_detail['redirect_uri'];
        $redirect_uri .= (false === strpos($redirect_uri, '?') ? '?' : '&');
        $redirect_uri .= sprintf('state=%s', $_SESSION['auth_data']['state']);

        $is_authorized = ($_POST['authorized'] === 'yes');
        $this->server->handleAuthorizeRequest($request, $response, $is_authorized, $_SESSION['auth_data']['account_id']);
        if ($is_authorized) {
            // this is only here so that you get to see your code in the cURL request. Otherwise, we'd redirect back to the client
            $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=') + 5, 40);

            $log = sprintf('[client_id: %s] [user_id: %s] [code: %s]', $this->client_id, $_SESSION['auth_data']['account_id'], $code);
            SeasLog::debug($log);

            $redirect_uri .= sprintf('&code=%s', $code);
        } else {
            $redirect_uri .= '&error=access_denied';
        }

        // 销毁 session
        $_SESSION['auth_data'] = null;

        $this->redirect($redirect_uri);
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

