<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/26 0026
 * Time: 11:07
 */

namespace app\platformmgmt\model;

use app\common\model\FurnitureAttr as FurnitureAttrModel;
class FurnitureAttr extends FurnitureAttrModel
{
    /**
     * 批量添加商品sku记录
     * @param $goods_id
     * @param $spec_list
     * @return array|false
     * @throws \Exception
     */
    public function addSkuList($fur_id, $spec_list)
    {
        $data = [];
        foreach ($spec_list as $item) {
            $data[] = array_merge($item['form'], [
                'attr_sku_id' => $item['spec_sku_id'],
                'fur_id' => $fur_id,
            ]);
        }
        return $this->saveAll($data);
    }

    /**
     * 添加商品规格关系记录
     * @param $goods_id
     * @param $spec_attr
     * @return array|false
     * @throws \Exception
     */
    public function addFurnitureAttrRel($fur_id, $attr_attr)
    {
        $data = [];
        array_map(function ($val) use (&$data, $fur_id) {
            array_map(function ($item) use (&$val, &$data, $fur_id) {
                $data[] = [
                    'fur_id' => $fur_id,
                    'attr_id' => $val['group_id'],
                    'attr_value_id' => $item['item_id'],
                ];
            }, $val['attr_items']);
        }, $attr_attr);
        $model = new FurnitureAttrRel;
        return $model->saveAll($data);
    }

    /**
     * 移除指定商品的所有sku
     * @param $goods_id
     * @return int
     */
    public function removeAll($fur_id)
    {
        $model = new GoodsSpecRel;
        $model->where('fur_id','=', $fur_id)->delete();
        return $this->where('fur_id','=', $fur_id)->delete();
    }
}