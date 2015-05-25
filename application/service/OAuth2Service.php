<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class OAuth2Service
{
    public static $permission_cfg = array(
        '360zebra' => array(
            'get_userinfo',
        ),
    );

    public static function getTokenData($server)
    {
        return $server->getAccessTokenData(OAuth2\Request::createFromGlobals());
    }

    public static function checkPermission($server, $controller, $action)
    {
        $token_data = $server->getAccessTokenData(OAuth2\Request::createFromGlobals());
        if (! isset(self::$permission_cfg[$token_data['client_id']]) 
            || ! in_array($action, self::$permission_cfg[$token_data['client_id']])) {
            throw new HigoException(HigoErrorCode::PERMISSION_DENIED);
        }
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

