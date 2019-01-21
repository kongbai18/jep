<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/16 0016
 * Time: 10:44
 */

namespace app\platformmgmt\controller;

use app\platformmgmt\model\Theme as ThemeModel;
use think\Controller;

class Theme extends Controller
{
    public function index(){
        $themeModel = new ThemeModel();

        $result = $themeModel->getTheme();

        if($result !== false){
            return show(config('code.success'),'获取信息成功！',$result);
        }
        return show(config('code.error'),'获取信息失败');
    }

    public function read($id){
        try{
            $themeModel = new ThemeModel();
            $data = $themeModel->find($id);
        }catch (\Exception $e){
            return show(config('code.error'),'获取信息失败');
        }

        return show(config('code.success'),'获取信息成功',$data);
    }

    public function save(){
        $data = input('post.');
        $themeModel = new ThemeModel();

        $result = $themeModel->addTheme($data);

        if($result){
            return show(config('code.success'),'添加成功');
        }
        return show(config('code.error'),'添加失败');
    }

    public function update($id){
        $data = input('put.');
        $data['id'] = $id;
        $themeModel = new ThemeModel();

        $result = $themeModel->editTheme($data);

        if($result){
            return show(config('code.success'),'修改成功');
        }
        return show(config('code.error'),'修改失败');
    }

    public function delete($id){
        $themeModel = new ThemeModel();
        try{
            $themeModel->where('id','eq',$id)->delete();
        }catch (\Exception $e){
            return show(config('code.error'),'删除失败');
        }
        return show(config('code.success'),'删除成功');
    }
}