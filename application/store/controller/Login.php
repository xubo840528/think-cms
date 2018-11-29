<?php
/**
 * Created by PhpStorm.
 * User: mayn
 * Date: 2018/10/25
 * Time: 11:48
 */

namespace app\store\controller;

use app\store\model\Manager;

class Login extends Base
{
    public function login(){
        if($this->request->isAjax()){
            $data = $this->request->param();

            $manager = new Manager();
            if(!$manager->getByName($data['login_name'])){
                $this->error('手机号不存在');
            }
            if(!$manager = $manager->login($data['login_name'],$data['login_password'])){
                $this->error('密码错误');
            }

            session('manager',$manager);
            $this->success('登录成功');
        }else{
            return $this->fetch();
        }

    }
    public function logout(){
        session('manager',null);
        $this->redirect('login/login');
    }
}