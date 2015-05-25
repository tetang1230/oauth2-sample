<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class HigoErrorCode
{
    const ERROR_UNKNOWN = -1;
    const SUCCESS       = 0;
    const CODE_DOES_NOT_EXIST = 1;

    /** mmmxxyy 前3位按类开分; 后三位为该类型下的具体错误 */
    // 3xx 数据库相关的错误码
    const DB_CAN_NOT_CONNECT = 300001;

    // 4xx 授权相操作
    const OAUTH2_COMMON_ERROR               = 400001;
    const LOST_CLIENT_ID                    = 400100;
    const LOST_REQUIRED_PARAMETERS          = 400101;
    const INVALID_CLIENT_ID                 = 400102;
    const EMPTY_MOBILE_OR_PASSWORD          = 400103;
    const INVALID_REQUEST_WITHOT_POST       = 400104;
    const INVALID_MOBILE_OR_PASSWORD        = 400105;
    const LOST_STATE                        = 400106;
    const AUTH_DATA_HAS_TAMPERED            = 400107;
    const ACCOUNT_OR_PASSWORD_IS_INCORRECT  = 400108;
    const INVALID_ACCESS_TOKEN              = 400109;
    const PERMISSION_DENIED                 = 400110;

    // 5xx 服务错误
    const SERVICE_IS_UNAVAILABLE        = 500101;
    const ACCESS_DENIED                 = 500102;
    const INCOMPLETE_DATA               = 500103;

    private static $message_conf = array(
        self::ERROR_UNKNOWN => '未知错误',
        self::SUCCESS       => 'OK',
        self::CODE_DOES_NOT_EXIST => '错误码不存在',

        self::DB_CAN_NOT_CONNECT  => '数据库连接失败',
        self::OAUTH2_COMMON_ERROR => 'oauth错误',

        self::LOST_CLIENT_ID                => '缺少 client_id 参数',
        self::LOST_REQUIRED_PARAMETERS      => '缺少必要参数',
        self::INVALID_CLIENT_ID             => '无效的 client_id',
        self::EMPTY_MOBILE_OR_PASSWORD      => '账号和密码不能为空',
        self::INVALID_REQUEST_WITHOT_POST   => '无效的请求,仅支持 POST 请求',
        self::INVALID_MOBILE_OR_PASSWORD    => '账号或密码无效',
        self::LOST_STATE                    => '缺少 state 参数',
        self::AUTH_DATA_HAS_TAMPERED        => '授权数据被篡改',
        self::ACCOUNT_OR_PASSWORD_IS_INCORRECT => '账号或密码错误',

        self::SERVICE_IS_UNAVAILABLE        => '后端服务不可用,请稍后重试',
        self::ACCESS_DENIED                 => '访问被拒绝',
        self::INVALID_ACCESS_TOKEN          => '无效的 access_token',
        self::PERMISSION_DENIED             => '没有权限访问此接口',

        self::INCOMPLETE_DATA               => '数据不完整',
    );

    public static function getMessage($code)
    {
        $message = '';
        if (! isset(self::$message_conf[$code])) {
            //$message = self::$message_conf[self::CODE_DOES_NOT_EXIST];
            throw new HigoException(self::CODE_DOES_NOT_EXIST);
        } else {
            $message = self::$message_conf[$code];
        }

        return $message;
    }
}

/* vi:set ts=4 sw=4 et fdm=marker: */
