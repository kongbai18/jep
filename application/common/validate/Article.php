<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/22 0022
 * Time: 14:41
 */

namespace app\common\validate;


use think\Validate;

class Article extends Validate
{
    protected $rule = [
        'article_name' => 'require|max:30',
        'image_url' => 'require',
        'cate_id' => 'require',
    ];

    protected $message = [
        'article_name.require' => '名称不能为空',
        'article_name.max' => '名称不得超过30字符',
        'cate_id' => '至少选择一种角色',
        'image_url' => '封面图不能为空',
    ];
}