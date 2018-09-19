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
     * page
     * @var string
     */
    public $page = '';

    /**
     * 每页显示多少条
     * @var string
     */
    public $size = '';
    /**
     * 查询条件的起始值
     * @var int
     */
    public $from = 0;

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

    /**
     * 修改
     * @param $data
     * @return Base
     * @throws \Exception
     */
    public function edit($data){
        if(!is_array($data)) {
            exception('传递数据不合法');
        }
        return $this->allowField(true)->update($data);
    }

    /**
     * 获取分页page size 内容
     */
    public function getPageAndSize($data) {
        $this->page = (isset($data['page']) && !empty($data['page'])) ? $data['page'] : 1;
        $this->size = (isset($data['size']) && !empty($data['size'])) ? $data['size'] : config('paginate.list_rows');
        $this->from = ($this->page - 1) * $this->size;
    }

}