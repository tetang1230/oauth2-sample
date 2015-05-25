<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class HigoInternalApiService
{
    const API_SUCCESS_CODE = 0;

    private static function getApiPrefix()
    {
        return Yaf_Registry::get('config')->higo->internal_api_prefix;
    }

    public static function checkLogin($account_mobile, $account_password)
    {
        $account_id = 0;

        $internal_api_prefix = self::getApiPrefix();
        $api = $internal_api_prefix . '/account/login';
        $ct = new CurlTool();

        $post_dt = array(
            'mobile'   => $account_mobile,
            'password' => $account_password,
        );
        $json_str = $ct->post($api, $post_dt);
        $res_dt = json_decode($json_str, true);
        if (! is_array($res_dt)) {
            $log = sprintf('[account_mobile: %s] [api_data: %s]', $account_mobile, $json_str);
            SeasLog::warning($log);
            return false;
        }

        if (self::API_SUCCESS_CODE != $res_dt['code']) {
            return -1;
        } elseif ($res_dt['data']['account_id'] > 0)
        {
            $account_id = $res_dt['data']['account_id'];
        }

        return $account_id;
    }

    public static function getUserInfo($req_dt)
    {
        $internal_api_prefix = self::getApiPrefix();
        $api = $internal_api_prefix . '/account/get_user_info';
        $ct = new CurlTool();

        $json_str = $ct->post($api, $req_dt);
        $res_dt = json_decode($json_str, true);
        if (! is_array($res_dt) || self::API_SUCCESS_CODE != $res_dt['code']) {
            $log = sprintf('[require_data: %s] [api_data: %s]', json_encode($req_dt), $json_str);
            SeasLog::warning($log);
            throw new HigoException(HigoErrorCode::SERVICE_IS_UNAVAILABLE);
        }

        return $res_dt['data'];
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

