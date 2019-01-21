<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/11 0011
 * Time: 9:59
 */

namespace app\platformmgmt\model;

use app\common\model\GoodsSpec as GoodsSpecModel;
class GoodsSpec extends GoodsSpecModel
{
    /**
     * 批量添加商品sku记录
     * @param $goods_id
     * @param $spec_list
     * @return array|false
     * @throws \Exception
     */
    public function addSkuList($goods_id, $spec_list)
    {
        $specData = $this->field('id')->where('goods_id','eq',$goods_id)->select();
        $oldId = [];
        foreach ($spec_list as $item) {
            $spec_sku_id = [];
            foreach ($item['sku'] as $v){
               $spec_sku_id[] = $v['spec_value_id'];
            }
            sort($spec_sku_id,SORT_NUMERIC);
            $spec_sku_id = (string)implode('_',$spec_sku_id);

            $data = array_merge($item['form'], [
                'spec_sku_id' => $spec_sku_id,
                'goods_id' => $goods_id,
            ]);
            if(isset($data['id']) && $data['id']){
                $this->where('id','eq',$data['id'])->update($data);
                $oldId[] = $data['id'];
            }else{
                unset($data['id']);
                $this->add($data);
            }
        }

        foreach ($specData as $v){
            $isDel = true;
            foreach ($oldId as $item){
                if($v['id'] == $item){
                    $isDel = false;
                }
            }
            if($isDel){
                $this->where('id','eq',$v['id'])->delete();
            }
        }
    }

    /**
     * 添加商品规格关系记录
     * @param $goods_id
     * @param $spec_attr
     * @return array|false
     * @throws \Exception
     */
    public function addGoodsSpecRel($goods_id, $spec_attr)
    {
        $data = [];
        array_map(function ($val) use (&$data, $goods_id) {
            array_map(function ($item) use (&$val, &$data, $goods_id) {
                $data[] = [
                    'goods_id' => $goods_id,
                    'spec_id' => $val['group_id'],
                    'spec_value_id' => $item['item_id'],
                ];
            }, $val['spec_items']);
        }, $spec_attr);
        $model = new GoodsSpecRel;
        return $model->saveAll($data);
    }

    /**
     * 移除指定商品的所有sku
     * @param $goods_id
     * @return int
     */
    public function removeAll($goods_id)
    {
        $model = new GoodsSpecRel;
        $model->where('goods_id','=', $goods_id)->delete();
        $this->where('goods_id','=', $goods_id)->delete();
    }
}