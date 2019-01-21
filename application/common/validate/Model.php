<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/27 0027
 * Time: 15:11
 */
namespace app\common\validate;


use think\Validate;

class Model extends Validate
{
    protected $rule = [
        'model_name' => 'require',
        'project_area' => 'require',
        'material' => 'require',
        'parameter' => 'require',
        'formula' => 'require',
    ];

    protected $message = [
        'model_name.require' => '模型名称不能为空',
        'project_area.require' => '投影面积计算不能为空',
        'material.require' => '模型材料数组不能为空',
        'parameter.require' => '模型参数数组不能为空',
        'formula.require' => '模型计价公式不能为空',
    ];

}