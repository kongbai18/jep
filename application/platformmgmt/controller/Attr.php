<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/26 0026
 * Time: 10:51
 */

namespace app\platformmgmt\controller;

use app\platformmgmt\model\Attr as AttrModel;
use app\platformmgmt\model\AttrValue as AttrValueModel;

class Attr extends Base
{
    /* @var SpecModel $SpecModel */
    private $AttrModel;

    /* @var SpecValueModel $SpecModel */
    private $AttrValueModel;

    /**
     * 构造方法
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->AttrModel = new AttrModel;
        $this->AttrValueModel = new AttrValueModel;
    }

    /**
     *
     * @SWG\Post(path="/platformMgmt/v1/addAttr",
     *   summary="添加规则组",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="attr_name",in="formData",type="string",required="true",
     *      description="规格组名称,不得超过30字符"
     *   ),
     *   @SWG\Parameter(name="type_id",in="formData",type="string",required="true",
     *      description="规格组类型，1选择，2增减"
     *   ),
     *   @SWG\Parameter(name="attr_value",in="formData",type="string",required="true",
     *      description="规格值"
     *   ),
     *
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *   )
     * )
     */
    public function addAttr()
    {
        $data = input('post.');

        // validate
        $validate = validate('Spec');
        if(!$validate->scene('addSpec')->check($data)) {
            return show(config('code.error'), $validate->getError());
        }

        // 判断规格组是否存在
        if (!$specId = $this->AttrModel->getAttrIdByName($data['attr_name'],$data['type_id'])) {
            // 新增规格组and规则值
            try{
                $attrId = $this->AttrModel->add($data);
                $data['attr_id'] = $attrId;
                $attrValueId = $this->AttrValueModel->add($data);
            }catch (\Exception $e){
                return show(config('error'),'系统内部错误','',500);
            }

            $rdata = [
                'attr_id' => (int)$attrId,
                'attr_value_id' => (int)$attrValueId,
            ];

            return show(config('success'),'',$rdata);
        }
        // 判断规格值是否存在
        if ($attrValueId = $this->AttrValueModel->getAttrValueIdByName($attrId,$data['attr_value'])) {
            $rdata = [
                'attr_id' => (int)$attrId,
                'attr_value_id' => (int)$attrValueId,
            ];
            return show(config('success'),'',$rdata);
        }
        // 添加规则值
        $data['attr_id'] = $attrId;
        try{
            $attrValueId = $this->AttrValueModel->add($data);
        }catch (\Exception $e){
            return show(config('error'),'系统内部错误','',500);
        }

        $rdata = [
            'attr_id' => (int)$attrId,
            'attr_value_id' => (int)$attrValueId,
        ];
        return show(config('success'),'',$rdata);
    }

    /**
     *
     * @SWG\Post(path="/platformMgmt/v1/addAttrVal",
     *   summary="添加规则组",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="attr_id",in="formData",type="string",required="true",
     *      description="规格组ID"
     *   ),
     *   @SWG\Parameter(name="attr_value",in="formData",type="string",required="true",
     *      description="规格值"
     *   ),
     *
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *   )
     * )
     */
    public function addAttrVal()
    {
        $data = input('post.');

        // validate
        $validate = validate('Spec');
        if(!$validate->scene('addSpecVal')->check($data)) {
            return show(config('code.error'), $validate->getError());
        }

        try{
            $attrValueId = $this->AttrValueModel->add($data);
        }catch (\Exception $e){
            return show(config('error'),'系统内部错误','',500);
        }

        $rdata = [
            'attr_id' => (int)$data['attr_id'],
            'attr_value_id' => (int)$attrValueId,
        ];
        return show(config('success'),'',$rdata);
    }
}