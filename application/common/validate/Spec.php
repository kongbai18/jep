<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/4 0004
 * Time: 15:08
 */

namespace app\common\validate;

use think\Validate;

class Spec extends Validate
{
    protected $rule = [
        'spec_name' => 'require|max:30',
        'type_id' => 'require',
        'spec_value' => 'require',
        'spec_value_alt' => 'require',
        'spec_id' => 'require',
    ];

    protected $message = [
        'spec_name.require' => '规格组名称不能为空',
        'spec_name.max' => '规格组名称名称不得超过30字符',
        'type_id' => '必须选择规格组类型',
        'spec_value.require' => '规格值不能为空',
        'spec_value_alt' => '规格值提示词不能为空',
        'spec_id' => '规格组ID不能为空',
    ];

    protected $scene = [
        'addSpec' => ['spec_name','type_id','spec_value','spec_value_alt'],
        'addSpecVal'  =>  ['spec_id','spec_value','spec_value_alt'],
    ];
}