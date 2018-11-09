<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/4 0004
 * Time: 14:22
 */

namespace app\platformmgmt\controller;

use app\platformmgmt\model\Spec as SpecModel;
use app\platformmgmt\model\SpecValue as SpecValueModel;

class Spec extends Base
{
    /* @var SpecModel $SpecModel */
    private $SpecModel;

    /* @var SpecValueModel $SpecModel */
    private $SpecValueModel;

    /**
     * 构造方法
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->SpecModel = new SpecModel;
        $this->SpecValueModel = new SpecValueModel;
    }

    /**
     *
     * @SWG\Post(path="/platformMgmt/v1/addSpec",
     *   summary="添加规则组",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="spec_name",in="formData",type="string",required="true",
     *      description="规格组名称,不得超过30字符"
     *   ),
     *   @SWG\Parameter(name="type_id",in="formData",type="string",required="true",
     *      description="规格组类型，1文字，2图片"
     *   ),
     *   @SWG\Parameter(name="spec_value",in="formData",type="string",required="true",
     *      description="规格值"
     *   ),
     *     @SWG\Parameter(name="spec_value_alt",in="formData",type="string",
     *      description="规格值提示词"
     *   ),
     *
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *   )
     * )
     */
    public function addSpec()
    {
        $data = input('post.');

        // validate
        $validate = validate('Spec');
        if(!$validate->scene('addSpec')->check($data)) {
            return show(config('code.error'), $validate->getError());
        }

        // 判断规格组是否存在
        if (!$specId = $this->SpecModel->getSpecIdByName($data['spec_name'],$data['type_id'])) {
            // 新增规格组and规则值
            try{
                $specId = $this->SpecModel->add($data);
                $data['spec_id'] = $specId;
                $specValueId = $this->SpecValueModel->add($data);
            }catch (\Exception $e){
                return show(config('error'),'系统内部错误','',500);
            }

            $rdata = [
                'spec_id' => (int)$specId,
                'spec_value_id' => (int)$specValueId,
            ];

            return show(config('code.success'),'',$rdata);
        }
        // 判断规格值是否存在
        if ($specValueId = $this->SpecValueModel->getSpecValueIdByName($specId,$data['spec_value_alt'])) {
            $rdata = [
                'spec_id' => (int)$specId,
                'spec_value_id' => (int)$specValueId,
            ];
            return show(config('success'),'',$rdata);
        }
        // 添加规则值
        $data['spec_id'] = $specId;
        try{
            $specValueId = $this->SpecValueModel->add($data);
        }catch (\Exception $e){
            return show(config('error'),'系统内部错误','',500);
        }

        $rdata = [
            'spec_id' => (int)$specId,
            'spec_value_id' => (int)$specValueId,
        ];
        return show(config('success'),'',$rdata);
    }

    /**
     *
     * @SWG\Post(path="/platformMgmt/v1/addSpecVal",
     *   summary="添加规则组",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="spec_id",in="formData",type="string",required="true",
     *      description="规格组ID"
     *   ),
     *   @SWG\Parameter(name="spec_value",in="formData",type="string",required="true",
     *      description="规格值"
     *   ),
     *     @SWG\Parameter(name="spec_value_alt",in="formData",type="string",
     *      description="规格值提示词"
     *   ),
     *
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *   )
     * )
     */
    public function addSpecVal()
    {
        $data = input('post.');

        // validate
        $validate = validate('Spec');
        if(!$validate->scene('addSpecVal')->check($data)) {
            return show(config('code.error'), $validate->getError());
        }

        try{
            $specValueId = $this->SpecValueModel->add($data);
        }catch (\Exception $e){
            return show(config('error'),'系统内部错误','',500);
        }

        $rdata = [
            'spec_id' => (int)$data['spec_id'],
            'spec_value_id' => (int)$specValueId,
        ];
        return show(config('success'),'',$rdata);
    }
}