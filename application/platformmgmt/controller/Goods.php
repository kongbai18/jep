<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/10 0010
 * Time: 9:18
 */

namespace app\platformmgmt\controller;

use app\platformmgmt\model\Goods as GoodsModel;

class Goods extends Base
{
    /**
     * @SWG\Get(path="/platformMgmt/v1/goods",
     *   summary="获取商品列表信息",
     *   description="请求该接口需要先登录并且有此权限,不需要传参",
     *   @SWG\Parameter(name="page",in="query",type="number",
     *      description="搜索页数，当页数超过总也是，返回最后一页数据，不传输默认第一页"
     *   ),
     *   @SWG\Parameter(name="size",in="query",type="number",
     *      description="搜索单页数，不传输默认一页20"
     *   ),
     *   @SWG\Parameter(name="category_id",in="query",type="number",
     *      description="搜索条件，分类ID"
     *   ),
     *   @SWG\Parameter(name="goods_status",in="query",type="number",
     *      description="搜索条件，商品状态，如上架10，下架20"
     *   ),
     *   @SWG\Parameter(name="is_delete",in="query",type="number",
     *      description="搜索条件，是否回收站商品1代表是，默认为0不是"
     *   ),
     *   @SWG\Parameter(name="keywords",in="query",type="string",
     *      description="搜索条件，商品名中包含关键字"
     *   ),
     *   @SWG\Parameter(name="sort['type']",in="query",type="string",
     *      description="排序条件，goods代表商品ID，sales代表销售量，price代表价格，不传递有默认排序"
     *   ),
     *   @SWG\Parameter(name="sort['order']",in="query",type="string",
     *      description="排序方式，asc升序，desc降序"
     *   ),
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功返回数据data；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限"
     *   )
     * )
     */
    public function index(){
        $data = input('get.');

        $goodsModel = new GoodsModel();

        $rdata = $goodsModel->getGoodsData($data);

        return show(config('code.success'),'获取商品列表成功',$rdata);
    }


    /**
     *
     * @SWG\Get(path="/platformMgmt/v1/goods/{goods_id}",
     *   summary="获取文章详细信息",
     *   description="请求该接口需要先登录并且有此权限。返回商品基本信息，商品图片，商品与规格关系表---利用此数据构建表格，商品sku数据",
     *   @SWG\Parameter(name="goods_id",in="path",type="number",required="true",
     *      description="商品ID"
     *   ),
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功返回数据data；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *   )
     * )
     */
    public function read($id){
        $goodsModel = new GoodsModel();

        $data = $goodsModel->getGoodsDetail($id);

        return show(config('code.success'),'获取商品详情成功',$data);
    }

    /**
     *
     * @SWG\Post(path="/platformMgmt/v1/goods",
     *   summary="添加商品",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="goods_name",in="formData",type="string",required="true",
     *      description="商品名称，不超过50字符"
     *   ),
     *   @SWG\Parameter(name="category_id",in="formData",type="number",
     *      description="商品分类ID"
     *   ),
     *   @SWG\Parameter(name="spec_type",in="formData",type="string",required="true",
     *      description="规格类型，10但规格，20多规格"
     *   ),
     *     @SWG\Parameter(name="content",in="formData",type="string",
     *      description="商品详情"
     *   ),
     *     @SWG\Parameter(name="sort_id",in="formData",type="string",
     *      description="排序"
     *   ),
     *     @SWG\Parameter(name="delivery_id",in="formData",type="number",required="true",
     *      description="运费方式ID"
     *   ),
     *   @SWG\Parameter(name="goods_status",in="formData",type="number",required="true",
     *      description="商品状态10上架，20下架"
     *   ),
     *     @SWG\Parameter(name="images[]",in="formData",type="number",required="true",
     *      description="商品图片地址，为一个一维数组"
     *   ),
     *    @SWG\Parameter(name="spec[]",in="formData",type="number",required="true",
     *      description="单规格时候使用,一维数组，包含goods_price(商品价格),line_price(市场价格),stock_num(库存量),goods_weight(商品重量)，image_url(图片地址)"
     *   ),
     *     @SWG\Parameter(name="spec_many['spec_list']",in="formData",type="number",required="true",
     *      description="多规格时候使用,二维数组，其中包含多组【goods_price(商品价格),line_price(市场价格),stock_num(库存量),goods_weight(商品重量)，spec_sku_id(id从小到大排列，两个直接用 _ 隔开),image_url(图片地址)】"
     *   ),
     *   @SWG\Parameter(name="spec_many['attr']",in="formData",type="number",required="true",
     *      description="多规格时候使用,二维数组，包含多组【group_id(代表规格的spec_id),item_id(代表spec_value_id)】"
     *   ),
     *
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *   )
     * )
     */
    public function save()
    {
        $data = input('post.');

        if (!isset($data['images']) || empty($data['images'])) {
            return show(config('code.error'),'请上传商品图片');
        }

        $goodsModel = new GoodsModel();
        if($goodsModel->addGoods($data)){
            return show(config('code.success'),'添加商品成功');
        }

        return show(config('code.error'),'添加失败');
    }

    /**
     *
     * @SWG\Post(path="/platformMgmt/v1/goods/{goods_id}/edit",
     *   summary="添加商品",
     *   description="请求该接口需要先登录并且有此权限。",
     *     @SWG\Parameter(name="id",in="path",type="number",required="true",
     *      description="商品id"
     *   ),
     *   @SWG\Parameter(name="goods_name",in="query",type="string",required="true",
     *      description="商品名称，不超过50字符"
     *   ),
     *   @SWG\Parameter(name="category_id",in="query",type="number",
     *      description="商品分类ID"
     *   ),
     *   @SWG\Parameter(name="spec_type",in="query",type="string",required="true",
     *      description="规格类型，10但规格，20多规格"
     *   ),
     *     @SWG\Parameter(name="content",in="query",type="string",
     *      description="商品详情"
     *   ),
     *     @SWG\Parameter(name="sort_id",in="query",type="string",
     *      description="排序"
     *   ),
     *     @SWG\Parameter(name="delivery_id",in="query",type="number",required="true",
     *      description="运费方式ID"
     *   ),
     *   @SWG\Parameter(name="goods_status",in="query",type="number",required="true",
     *      description="商品状态10上架，20下架"
     *   ),
     *     @SWG\Parameter(name="images[]",in="query",type="number",required="true",
     *      description="商品图片地址，为一个一维数组"
     *   ),
     *    @SWG\Parameter(name="spec[]",in="query",type="number",required="true",
     *      description="单规格时候使用,一维数组，包含goods_price(商品价格),line_price(市场价格),stock_num(库存量),goods_weight(商品重量)，image_url(图片地址)"
     *   ),
     *     @SWG\Parameter(name="spec_many['spec_list']",in="query",type="string",required="true",
     *      description="多规格时候使用,二维数组，其中包含多组【goods_price(商品价格),line_price(市场价格),stock_num(库存量),goods_weight(商品重量)，spec_sku_id(id从小到大排列，两个直接用 _ 隔开),image_url(图片地址)】"
     *   ),
     *   @SWG\Parameter(name="spec_many['attr']",in="query",type="number",required="true",
     *      description="多规格时候使用,二维数组，包含多组【group_id(代表规格的spec_id),item_id(代表spec_value_id)】"
     *   ),
     *
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *   )
     * )
     */
    public function edit()
    {
        $data = input('post.');

        if (!isset($data['images']) || empty($data['images'])) {
            return show(config('code.error'),'请上传商品图片');
        }


        $goodsModel = new GoodsModel();
        if($goodsModel->editGoods($data)){
            return show(config('code.success'),'修改商品成功');
        }

        return show(config('code.error'),'修改失败');
    }

    /**
     *
     * @SWG\Delete(path="/platformMgmt/v1/admin/{goods_id}",
     *   summary="删除商品",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="goods_id",in="path",type="number",required="true",
     *      description="商品ID"
     *   ),
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *  )
     * )
     */
    public function delete($id)
    {
        $goodsModel = new GoodsModel();

        $udata = [
            'id' => $id,
            'is_delete' => 1,
        ];

        try{
            $goodsModel->update($udata);
        }catch (\Exception $e){
            return show(config('code.error'),'删除失败');
        }

         return show(config('code.success'),'删除成功');

    }
}