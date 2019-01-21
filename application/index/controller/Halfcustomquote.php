<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/19 0019
 * Time: 11:02
 */

namespace app\index\controller;

use app\common\model\HalfCustomQuote as HalfCustomQuoteModel;

class Halfcustomquote extends Base
{
    public function index(){
        $where['user_type'] = ['eq',1];
        $where['user_id'] = $this->userId;

        $HalfCustomQuoteModel = new HalfCustomQuoteModel();
        $result = $HalfCustomQuoteModel->getHalfCustQuote($where);
        return show(config('code.success'),'获取半定制报价成功',$result);
    }


    public function save(){
        $data = input('post.');
        $data['user_type'] = 1;
        $data['user_id'] = $this->userId;

        $HalfCustomQuoteModel = new HalfCustomQuoteModel();
        $result = $HalfCustomQuoteModel->addHalfCustomQuote($data);

        if($result){
            return show(config('code.success'),'添加半定制报价成功');
        }

        return show(config('code.error'),'添加半定制报价失败');

    }


    public function delete($id){
        $HalfCustomQuoteModel = new HalfCustomQuoteModel();
        $result = $HalfCustomQuoteModel->remove($id);

        if($result){
            return show(config('code.success'),'删除半定制报价成功');
        }

        return show(config('code.error'),'删除半定制报价失败');
    }
}