<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/10 0010
 * Time: 13:28
 */

namespace app\platformmgmt\controller;

use app\platformmgmt\model\CustomQuote as CustomQuoteModel;


class Customquote extends Base
{
    public function index(){
        $CustomQuoteModel = new CustomQuoteModel();

        $result = $CustomQuoteModel->getQuote();

        if($result !== false){
            return show(config('code.success'),'获取全定制报价成功',$result);
        }

        return show(config('code.error'),'获取全定制报价失败');
    }

    public function save(){
        $data = input('post.');
        $data['user_type'] = 2;
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
            $CustomQuoteModel->where('id','eq',$id)->save($udata);
        }catch (\Exception $e){
            return show(config('code.error'),'删除失败');
        }

        return show(config('code.success'),'删除成功');
    }

}