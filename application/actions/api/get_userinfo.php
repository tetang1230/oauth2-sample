<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class get_userinfoAction extends BaseAction
{
    private $token_data = array();

    protected function beforeExecute()
    {
        $token_data = OAuth2Service::getTokenData($this->server);
        if ($token_data['user_id'] <= 0)
        {
            throw new HigoException(HigoErrorCode::INCOMPLETE_DATA);
        }

        $this->token_data = $token_data;
    }

    public function run($arg = null)
    {
        $req_dt = array(
            'third_client_id' => $this->token_data['client_id'],
            'account_id'      => $this->token_data['user_id'],
        );

        $data = HigoInternalApiService::getUserInfo($req_dt);
        $this->data = $data;

        $this->jsonResponse();
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

