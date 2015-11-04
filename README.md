# HIGO oauth2

基于 php 的 oauth2 服务

# 环境依赖

- yaf
- SeasLog
- git@github.com:bshaffer/oauth2-server-php.git


##### OAuth2 服务接口说明

第一步

引导用户进入登陆授权入口：http://localhost/Oauth2/login?client_id=XXX&state=1234


参数名      			| 是否必须	 		|   备注
--------   			| ---    			|   ---
client_id  			| 是      			|   合作方的注册id
state               | 是             |   OAuth2 服务参数,授权成功会原样带回


返回值：

```conf
{
    "code": 0,
    "data": {
        "ip": 123//注意此处是iplong
    }
}
```

第二步

用户完成在系统的登陆后,会现让用户确认授权的操作.如果用户确认使用第三方服务,系统会将用户重定向到第三方注册的链接,例如

http://localhost/higo?state=1234&code=b8ffb2e57afbbe0aea213908e46924ab43a34e4b

其中, state 为请求授权时的参数,原样带回; code 为下步换取 access_token 的必要参数.

如果用户取消授权,也会重定向到第三方注册的回传地址,但不会带 code 参数,会形如: http://localhost/higo?state=1234&error=access_denied


第三步

使用第二步获取的 code,通过类似以下方法获取 access_token.

接口地址:

```
http://localhost/oauth2/token
```

```
请求示例:
$ curl -u secretid:secret http://api.higo.meilishuo.com/oauth2/token -d 'grant_type=authorization_code&code=f160165e2a4c69ed1c90b0484929b9f39f760f11'
```

说明：

- 将 clientid, clientsecret 以 HTTP AUTHORIZATION 形式发送到服务端curl\_setopt($curl, CURLOPT\_HTTPAUTH, CURLAUTH\_BASIC ) ; curl\_setopt($curl, CURLOPT\_USERPWD, "username:password");
- 必须以 POST 请求方式访问接口,其中传两个参数,1: granttype, 其值为 authorizationcode; 2: code, 其值为第二步回传的 code 值.


成功后得到形如下的数据:

```
{
    "code": 0,
    "message": "OK",
    "data": {
        "access_token": "f40a919800930671b38db9d9c1fe8ff1a1c3b821",
        "expires_in": 3600,
        "token_type": "Bearer",
        "scope": null,
        "refresh_token": "c79283e04a5b4e8d16277271b63475b4d0788dad"
    }
}
```

完成整个授权过程.




第四步

当发现access_token失效时，可以通过刷新access_token接口,来重新生成access_token

请求格式如下：

接口地址:

```
http://localhost/oauth2/token
```

返回格式如下

```
请求示例:
curl -u testclient:testpass http://localhost/oauth2/token -d 'grant_type=refresh_token&refresh_token=REFRESH_TOKEN_CODE'
```

```
{
    "code": 0,
    "message": "OK",
    "data": {
        "access_token": "6e92a932e2c8c64b6cd1302f90fa7be05b804541",
        "expires_in": 3600,
        "token_type": "Bearer",
        "scope": null,
        "refresh_token":"34737d165d0eea14cf263be6bf6585080424c9e9"
}

```
