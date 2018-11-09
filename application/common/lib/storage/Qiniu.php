<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/3 0003
 * Time: 9:31
 */

namespace app\common\lib\storage;

vendor('qiniu.autoload');
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class Qiniu
{
    /**
     * 图片上传
     */
    public static function image() {
        if(empty($_FILES['file']['tmp_name'])) {
            exception('您提交的图片数据不合法', 404);
        }
        /// 要上传的文件的
        $file = $_FILES['file']['tmp_name'];

        /*$ext = explode('.', $_FILES['file']['name']);
        $ext = $ext[1];*/
        $pathinfo = pathinfo($_FILES['file']['name']);

        $ext = $pathinfo['extension'];

        $config = config('qiniu');
        // 构建一个鉴权对象
        $auth  = new Auth($config['ak'], $config['sk']);
        //生成上传的token
        $token = $auth->uploadToken($config['bucket']);
        // 上传到七牛后保存的文件名
        $key  = date('Y')."/".date('m')."/".substr(md5($file), 0, 5).date('YmdHis').rand(0, 9999).'.'.$ext;

        //初始UploadManager类
        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->putFile($token, $key, $file);

        if($err !== null) {
            return null;
        } else {
            return $key;
        }
    }

    /**
     * 图片删除
     */
    public static function delete($key){
        $config = config('qiniu');

        $auth  = new Auth($config['ak'], $config['sk']);

        $qiniuConfig = new Config();
        $bucketManager = new BucketManager($auth, $qiniuConfig);

        $err = $bucketManager->delete($config['bucket'], $key);

        return $err;
    }
}