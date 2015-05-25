<?php
/**
 * @describe:
 * @author: jichao
 * */
class tokenAction extends BaseAction
{
    public function run($arg = null)
    {
        //Yaf_Dispatcher::getInstance()->autoRender(false);
        $response = $this->server->handleTokenRequest(OAuth2\Request::createFromGlobals());
        $ret = $response->getParameters();
        $this->convertOauth2Info($ret);
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */
