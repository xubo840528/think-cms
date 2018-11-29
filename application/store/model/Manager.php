<?php
/**
 * Created by PhpStorm.
 * User: mayn
 * Date: 2018/9/26
 * Time: 8:59
 */

namespace app\store\model;


use think\Model;

class Manager extends Model
{
    protected $table = 'managers';
    public function getByName($login_name = ''){
        return $this->where('mobile',$login_name)->find();
    }
    public function login($login_name = '',$login_password = ''){
        return $this->where(['mobile'=>$login_name,'password'=>$login_password])->find();
    }
}