<?php
/**
 * Created by PhpStorm.
 * User: baidu
 * Date: 18/8/26
 * Time: 下午1:07
 */
namespace app\common\model;

use think\Model;

class Base extends Model {

    protected $field = true;
    protected  $autoWriteTimestamp = true;

    /**
     * 新增
     * @param $data
     * @return mixed
     */
    public function add($data) {
        if(!is_array($data)) {
            exception('传递数据不合法');
        }
        $this->allowField(true)->save($data);

        return $this->id;
    }

    public function edit($data){
        if(!is_array($data)) {
            exception('传递数据不合法');
        }
        return $this->update($data);
    }

}