<?php
/**
 * Created by PhpStorm.
 * User: mayn
 * Date: 2018/10/9
 * Time: 16:33
 */
namespace app\manage\model;

use think\Env;
use think\Model;

class Store extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'stores';
    public function getTypeAttr($value)
    {
        $type = [1=>'直营',2=>'共享'];
        return $type[$value];
    }
//    public function getTypeTextAttr($value,$data)
//    {
//        $status = [1=>'直营',2=>'共享'];
//        return $status[$data['status']];
//    }
}