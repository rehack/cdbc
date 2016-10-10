<?php
namespace app\index\model;
use think\Model;
use think\Db;

class Menu extends Model{
	public function getmenu(){
        // 查询数据
        $data = Db::name('menu')
            ->field('menuid,menuname,icon,url,pid')
            // ->where("pid = $pid")
            ->select();
        $menudata=array();

        $arr['menus']=$this::_get_child($data);
        $json=json_encode($arr);
        return $json;
	}
    //分类级别
    static protected function _cate_level($data, $pid=0, $level=0){
        $array = array();
        foreach ($data as $k => $v){
            if ($v['pid'] == $pid){
                $v['level'] = $level;
                $array[] = $v;
                unset($data[$k]);
                $array = array_merge($array, self::_cate_level($data, $v['menuid'],$level+1));
            }
        }
        return $array;
        // return json_encode($array);

    }



    //菜单
    static protected function _get_child($data, $pid=0){
        $array = array();
        foreach ($data as $k => $v){
            if ($v['pid'] == $pid){
                $v['menus'] = self::_get_child($data, $v['menuid']);
                $array[] = $v;
            }
        }
        return $array;
    }
}
