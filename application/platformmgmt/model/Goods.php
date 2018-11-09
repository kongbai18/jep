<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/10 0010
 * Time: 9:19
 */

namespace app\platformmgmt\model;

use app\common\model\Goods as GoodsModel;
use app\common\model\GoodsImage as GoodsImageModel;
use think\Db;

class Goods extends GoodsModel
{
    public function addGoods($data)
    {
        // 开启事务
        Db::startTrans();
        try {
            // 添加商品
            $goodsId = $this->add($data);
            // 商品规格
            $this->addGoodsSpec($data,$goodsId);
            // 商品图片
            $this->addGoodsImages($data['images'],$goodsId);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
        }
        return false;
    }

    /**
     * 编辑商品
     * @param $data
     * @return bool
     */
    public function editGoods($data)
    {
        // 开启事务
        Db::startTrans();
        try {
            // 保存商品
            $this->allowField(true)->save($data);
            // 商品规格
            $this->addGoodsSpec($data,$data['goods_id'], true);
            // 商品图片
            $this->addGoodsImages($data['images'],$data['goods_id']);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
        }
        return false;
    }


    /**
     * 添加商品图片
     * @param $images
     * @return int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    private function addGoodsImages($images,$goodsId)
    {
        $goodsImgModel = new GoodsImageModel;
        $goodsImgModel->where(['goods_id'=>['eq',$goodsId]])->delete();
        $udata = [];
        foreach ($images as $v){
            $udata[] = [
                'goods_id' => $goodsId,
                'image_url' => $v['img_url']
            ];
        }

        $goodsImgModel->saveAll($udata);


    }


    /**
     * 添加商品规格
     * @param $data
     * @param $isUpdate
     * @throws \Exception
     */
    private function addGoodsSpec(&$data,$goodsId,$isUpdate = false)
    {
        // 更新模式: 先删除所有规格
        $goodsSpecModel = new GoodsSpec;
        $isUpdate && $goodsSpecModel->removeAll($goodsId);
        // 添加规格数据
        if ($data['spec_type'] === '10') {
            // 单规格
            $data['spec']['goods_id'] = $goodsId;
            try{
                $goodsSpecModel->allowField(true)->save($data['spec']);
            }catch (\Exception $e){
                var_dump($e->getMessage());
            }

        } else if ($data['spec_type'] === '20') {
                // 添加商品与规格关系记录
                $goodsSpecModel->addGoodsSpecRel($goodsId, $data['spec_many']['spec_attr']);
                // 添加商品sku
                $goodsSpecModel->addSkuList($goodsId, $data['spec_many']['spec_list']);

        }
    }

    /**
     * 删除商品
     * @return int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function remove($goods_id)
    {
        $goodsSpecModel = new GoodsSpec();
        $goodsSpecRelModel = new GoodsSpecRel();
        $goodsImgModel = new GoodsImg();
        Db::startTrans();
        $goodsSpecModel->where(['goods_id'=>['eq',$goods_id]])->delete();
        $goodsSpecRelModel->where(['goods_id'=>['eq',$goods_id]])->delete();
        $goodsImgModel->where(['goods_id'=>['eq',$goods_id]])->delete();
        $this->delete($goods_id);
        Db::commit();
        return true;
    }

}