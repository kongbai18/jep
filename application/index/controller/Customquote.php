<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/19 0019
 * Time: 10:59
 */

namespace app\index\controller;

use app\common\model\CustomQuote as CustomQuoteModel;

class Customquote extends Base
{
    public function index(){
        $where['user_type'] = ['eq',1];
        $where['user_id'] = $this->userId;
        $CustomQuoteModel = new CustomQuoteModel();
        $result = $CustomQuoteModel->getQuote($where);

        if($result !== false){
            return show(config('code.success'),'获取全定制报价成功',$result);
        }

        return show(config('code.error'),'获取全定制报价失败');
    }

    public function save(){
        $data = input('post.');
        $data['user_type'] = 1;
        $data['user_id'] = $this->userId;

        $CustomQuoteModel = new CustomQuoteModel();

        $result = $CustomQuoteModel->addCustomQuote($data);

        if($result){
            return show(config('code.success'),'添加报价成功');
        }

        return show(config('code.error'),'添加报价失败');
    }

    public function delete($id){
        try{
            $CustomQuoteModel = new CustomQuoteModel();
            $udata = [
                'is_delete' => 1,
            ];
            $CustomQuoteModel->where('id','eq',$id)->update($udata);
        }catch (\Exception $e){
            return show(config('code.error'),'删除失败');
        }

        return show(config('code.success'),'删除成功');
    }
}