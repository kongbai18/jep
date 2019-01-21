<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/6 0006
 * Time: 13:49
 */

namespace app\index\controller;

use app\common\model\Region as RegionModle;
use think\Controller;
class Region extends Controller
{
    /**
     *
     * @SWG\Get(path="/platformMgmt/v1/region",
     *   summary="获取地区树状列表信息",
     *   description="请求该接口需要先登录并且有此权限,不需要传参",
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功返回数据data；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限"
     *   )
     * )
     */
    public function index(){
        $data = RegionModle::getCacheTree();

        return show(config('code.success'),'获取地区树状结构成功',$data);
    }
}