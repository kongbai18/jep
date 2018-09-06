<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/5 0005
 * Time: 11:04
 */

namespace app\platformmgmt\controller;

use app\platformmgmt\model\Delivery as DeliveryModel;
use app\platformmgmt\model\DeliveryRule as DeliveryRuleModel;

class Delivery extends Base
{

    /* @var DeliveryModel  */
    private $deliveryModel;

    /* @var DeliveryRuleModel  */
    private $deliveryRuleModel;

    /**
     * 构造方法
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->deliveryModel = new DeliveryModel;
        $this->deliveryRuleModel = new DeliveryRuleModel;
    }
    /**
     *
     * @SWG\Get(path="/platformMgmt/v1/delivery",
     *   summary="获取配送模板列表信息",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功返回数据data；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *   )
     * )
     */
    public function index()
    {
        $list = $this->deliveryModel->select();

        return show(config('code.success'),'',$list);
    }

    /**
     *
     * @SWG\Get(path="/platformMgmt/v1/delivery/{id}",
     *   summary="获取配送模板信息",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="id",in="path",type="number",required="true",
     *      description="配送模板ID"
     *   ),
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功返回数据data；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *   )
     * )
     */
    public function read($id){
        $deliveryData = $this->deliveryModel->find($id);
        $ruleData = $this->deliveryRuleModel->where(['delivery_id'=>['eq',$id]])->select();

        if(!$deliveryData){
            return show(config('code.error'),'运费模板不存在');
        }

        $data = [
            'deliveryData' => $deliveryData,
            'ruleData' => $ruleData
        ];

        return show(config('code.success'),'获取运费模板详细信息成功',$data);
    }

    /**
     *
     * @SWG\Post(path="/platformMgmt/v1/delivery",
     *   summary="添加配送计费模板",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="delivery_name",in="formData",type="string",required="true",
     *      description="配送计费模板名称,不得超过30字符"
     *   ),
     *   @SWG\Parameter(name="method",in="formData",type="number",required="true",
     *      description="计费方式，10按件，20按重量"
     *   ),
     *   @SWG\Parameter(name="sort_id",in="formData",type="string",required="true",
     *      description="排序"
     *   ),
     *     @SWG\Parameter(name="rule[region][]",in="formData",type="string",
     *      description="配送规则中包含地区"
     *   ),
     *     @SWG\Parameter(name="rule[first][]",in="formData",type="string",
     *      description="首件数或者首重数"
     *   ),
     *     @SWG\Parameter(name="rule[first_fee]",in="formData",type="number",required="true",
     *      description="首件或者首重费"
     *   ),
     *     @SWG\Parameter(name="rule[additional][]",in="formData",type="string",
     *      description="续件数或者续重数"
     *   ),
     *     @SWG\Parameter(name="rule[additional_fee]",in="formData",type="number",required="true",
     *      description="续件或者续重费"
     *   ),
     *
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *   )
     * )
     */
    public function save()
    {
        $data = input('post.');

        if (!isset($data['rule']) || empty($data['rule'])) {
            return show(config('code.error'),'请选择可配送区域');
        }
        // 新增记录
        $deliveryId = $this->deliveryModel->add($data);

        $data['rule']['delivery_id'] = $deliveryId;

        if($this->deliveryRuleModel->createDeliveryRule($data['rule'])){
            return show(config('code.success'),'添加成功');
        }

        return show(config('code.error'),'添加失败');
    }


    /**
     *
     * @SWG\Get(path="/platformMgmt/v1/delivery/{$delivery_id}/edit",
     *   summary="修改配送计费模板",
     *   description="请求该接口需要先登录并且有此权限。",
     *     @SWG\Parameter(name="delivery_id",in="path",type="string",required="true",
     *      description="配送计费模板Id"
     *   ),
     *   @SWG\Parameter(name="delivery_name",in="query",type="string",required="true",
     *      description="配送计费模板名称,不得超过30字符"
     *   ),
     *   @SWG\Parameter(name="method",in="query",type="number",required="true",
     *      description="计费方式，10按件，20按重量"
     *   ),
     *   @SWG\Parameter(name="sort_id",in="query",type="string",required="true",
     *      description="排序"
     *   ),
     *     @SWG\Parameter(name="rule[region][]",in="query",type="string",
     *      description="配送规则中包含地区"
     *   ),
     *     @SWG\Parameter(name="rule[first][]",in="query",type="string",
     *      description="首件数或者首重数"
     *   ),
     *     @SWG\Parameter(name="rule[first_fee]",in="query",type="number",required="true",
     *      description="首件或者首重费"
     *   ),
     *     @SWG\Parameter(name="rule[additional][]",in="query",type="string",
     *      description="续件数或者续重数"
     *   ),
     *     @SWG\Parameter(name="rule[additional_fee]",in="query",type="number",required="true",
     *      description="续件或者续重费"
     *   ),
     *
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *   )
     * )
     */
    public function edit($delivery_id)
    {
        $data = input('get.');
        $dat['delivery_id'] = $delivery_id;

        if (!isset($data['rule']) || empty($data['rule'])) {
            return show(config('code.error'),'请选择可配送区域');
        }

        // 更新记录
        $this->deliveryModel->edit($data);

        $data['rule']['delivery_id'] = $data['id'];

        if($this->deliveryRuleModel->createDeliveryRule($data['rule'])){
            return show(config('code.success'),'修改成功');
        }

        return show(config('code.error'),'修改失败');

    }

    /**
     *
     * @SWG\Delete(path="/platformMgmt/v1/delivery/{$delivery_id}",
     *   summary="删除计费模板",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="delivery_id",in="path",type="number",required="true",
     *      description="管理员ID"
     *   ),
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *  )
     * )
     */
    public function delete($delivery_id)
    {
        // 判断是否存在商品
        /*if ($goodsCount = (new Goods)->where(['delivery_id' => $delivery_id])->count()) {
            return show(config('error'),'该模板被' . $goodsCount . '个商品使用，不允许删除');
        }*/

        $this->deliveryModel->where(['id'=>['eq',$delivery_id]])->delete();

        $this->deliveryRuleModel->where(['delivery_id'=>['eq',$delivery_id]])->delete();

        return show(config('code.success'),'删除成功');
    }

}