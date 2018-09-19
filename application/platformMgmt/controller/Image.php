<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/3 0003
 * Time: 13:30
 */

namespace app\platformmgmt\controller;

use app\common\lib\storage\Qiniu;

class Image extends Base
{
    /**
     *
     * @SWG\Post(path="/platformMgmt/v1/upload",
     *   summary="获取上传图片路径",
     *   description="请求该接口需要先登录并且有此权限,上传file",
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功返回数据data；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限"
     *   )
     * )
     */
    public function upload() {
        try {
            $image = Qiniu::image();
        }catch (\Exception $e) {
            return show(config('error'),$e->getMessage(),'',500);
        }
        if($image) {
            $data = [
                'path' => config('qiniu.image_url').'/'.$image
            ];
            return show(config('code.success'),'',$data);
        }else {
            return show(config('code.error'),'图片上传失败','',500);
        }
    }
}