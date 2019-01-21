<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/7 0007
 * Time: 10:04
 */

namespace app\index\model;

use app\common\lib\exception\ApiException;
use app\common\model\WxUser as WxUserModel;
use app\common\lib\wxchat\WxUser as WxUserLib;
use think\Cache;

class Wxuser extends WxUserModel
{
    private $token;

    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'create_time',
        'update_time'
    ];

    /**
     * 获取用户信息
     * @param $token
     * @return null|static
     */
    public static function getUser($token)
    {
        return self::detail(['open_id' => Cache::get($token)['openid']]);
    }

    /**
     * 获取token
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * 微信登陆
     * @param $post
     * @return mixed
     */
    public function login($post)
    {
        // 微信登录 获取session_key
        $session = $this->wxlogin($post['code']);
        // 自动注册用户
        $userInfo = json_decode(htmlspecialchars_decode($post['user_info']), true);
        $user_id = $this->register($session['openid'], $userInfo);
        // 生成token (session3rd)
        $this->token = $this->token($session['openid']);
        // 记录缓存, 7天
        Cache::set($this->token, $user_id, 86400 * 7);
        return $user_id;
    }

    /**
     * 微信登陆
     * @param $code
     * @return mixed
     */
    private function wxlogin($code)
    {
        // 微信登录 (获取session_key)
        $WxUserLib = new WxUserLib(config('wxapp.app_id'),config('wxapp.app_secret'));
        if (!$session = $WxUserLib->sessionKey($code)){
            throw new ApiException('session_key 获取失败');
        }
        return $session;
    }

    /**
     * 微信注册
     * @param $open_id
     * @param $userInfo
     * @return mixed
     */
    private function register($open_id, $userInfo)
    {
        if (!$user = self::get(['open_id' => $open_id])) {
            $user = $this;
            $userInfo['open_id'] = $open_id;
            $userInfo['nickName'] = preg_replace('/[\xf0-\xf7].{3}/', '', $userInfo['nickName']);

            try{
                $model = model('user');
                $uData = [
                    'image_url' => $userInfo['avatarUrl'],
                ];
                $userId = $model->insertGetId($uData);

                $userInfo['user_id'] = $userId;
                $user->allowField(true)->save($userInfo);
            }catch (\Exception $e){
                throw new ApiException('用户注册失败');
            }
        }
        return $user['user_id'];
    }

    /**
     * 生成用户认证的token
     * @param $openid
     * @return string
     */
    private function token($openid)
    {
        return md5($openid.config('wxapp.token_salt'));
    }
}