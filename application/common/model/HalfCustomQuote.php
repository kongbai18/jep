<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5 0005
 * Time: 14:01
 */

namespace app\common\model;

use think\Db;

class HalfCustomQuote extends Base
{
    public function getHalfCustQuote($where=[]){
        $where['is_delete'] = ['eq',0];
        //取出主报价值
        $halfCusDtl = $this->field('a.id,b.goods_id,b.halfcust_name,c.goods_price,c.image_url,c.spec_sku_id')
            ->alias('a')
            ->join('half_custom b','a.halfcus_id = b.id','left')
            ->join('goods_spec c','b.goods_id = c.goods_id AND a.spec_sku_id = c.spec_sku_id','left')
            ->where($where)
            ->select()
            ->toArray();

        //报价计算
        $data = $this->computePrice($halfCusDtl);

        return $data;
    }

    public function addHalfCustomQuote($data){
        // 开启事务
        Db::startTrans();
        try{
            $hcqId = $this->add($data);

            if(is_string($data['detail'])){
                $data['detail'] = json_decode($data['detail'],true);
            }
            $result = $this->addQuoteDtl($data,$hcqId);

            if(!$result){
                Db::rollback();
                return false;
            }
        }catch (\Exception $e){
            Db::rollback();
            return false;
        }
        Db::commit();
        return true;
    }

    public function addQuoteDtl($data,$hcqId){
        $halfCusDtl = model('half_customdtl')->where('halfcus_id','eq',$data['halfcus_id'])->select();

        foreach ($halfCusDtl as $item){
            $isType =false;
            foreach ($data['detail'] as $v){

                if($item['type_name'] == $v['type_name']){
                    $isType =true;
                    $udata = [
                        'hcq_id' => $hcqId,
                        'type_name' => $v['type_name'],
                        'goods_id' => $v['goods_id'],
                        'spec_sku_id' => $v['spec_sku_id'],
                        'num' => $item['num'],
                    ];

                    model('half_custom_quotedtl')->insert($udata);
                }
            }
            if(!$isType){
                return false;
            }
        }

        return true;
    }

    private function computePrice($data){
        foreach ($data as &$v){
            $goodsData = model('goods')->field('goods_name')->find($v['goods_id']);
            if($v['spec_sku_id'] == 0){
                $v['goods_name'] = $goodsData['goods_name'];
                $v['attr'] = '默认';
            }else{
                $spec = explode('_',$v['spec_sku_id']);

                $specVal = model('spec_value')->field('a.spec_value_alt,b.spec_name')
                    ->alias('a')
                    ->where(['a.id'=>['in',$spec]])
                    ->join('spec b','a.spec_id = b.id','left')
                    ->select()
                    ->toArray();

                $attr = [];

                foreach($specVal as $sp){
                    $attr[] = $sp['spec_name'].':'.$sp['spec_value_alt'];
                }

                $v['goods_name'] = $goodsData['goods_name'];
                $v['attr'] = implode(';',$attr);
            }

            $detail = model('half_custom_quotedtl')->where('hcq_id','eq',$v['id'])->select();
            $v['detail'] = [];
            foreach ($detail as $item){
                $goods = model('goods_spec')->where(['goods_id'=>['eq',$item['goods_id']],'spec_sku_id'=>['eq',$item['spec_sku_id']]])->find();
                $v['goods_price'] = $v['goods_price'] + $goods['goods_price'] * $item['num'];

                $goodsData = model('goods')->field('goods_name')->find($item['goods_id']);
                if($item['spec_sku_id'] == 0){
                    $v['detail'][] = [
                        'type_name' => $item['type_name'],
                        'attr' => '默认',
                        'num' => $item['num'],
                        'goods_pic' => $goods['image_url'],
                        'goods_name' => $goodsData['goods_name'],
                    ];
                }else{
                    $spec = explode('_',$item['spec_sku_id']);

                    $specVal = model('spec_value')->field('a.spec_value_alt,b.spec_name')
                        ->alias('a')
                        ->where(['a.id'=>['in',$spec]])
                        ->join('spec b','a.spec_id = b.id','left')
                        ->select()
                        ->toArray();

                    $attr = [];

                    foreach($specVal as $sp){
                        $attr[] = $sp['spec_name'].':'.$sp['spec_value_alt'];
                    }

                    $v['detail'][] = [
                        'type_name' => $item['type_name'],
                        'attr' => implode(';',$attr),
                        'num' => $item['num'],
                        'goods_pic' => $goods['image_url'],
                        'goods_name' => $goodsData['goods_name'],
                    ];
                }
            }
        }

        return $data;
    }

    public function remove($id){
        // 开启事务
        Db::startTrans();
        try{
            $udata = [
                'is_delete' => 1,
            ];
            $this->where('id','eq',$id)->update($udata);
        }catch (\Exception $e){
            Db::rollback();
            return false;
        }
        Db::commit();
        return true;
    }
}