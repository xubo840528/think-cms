<?php
/**
 * Created by PhpStorm.
 * User: mayn
 * Date: 2018/10/25
 * Time: 16:50
 */

namespace app\store\controller;

use think\Db;
class Package extends Base
{
    public function index(){
        $manager = session('manager');
        if(!$manager){
            $this->redirect('login/login');
        }
        $mobile = $this->request->param('mobile');

        if($mobile){
            $customer = Db::name('customers')->where('mobile',$mobile)->find();
            if($customer){
                $packages = Db::name('packages')->where('deleted',0)->where('customer_id',$customer['id'])->select();
            }else{
                $customer = $packages = '';
            }
        }else{
            $customer = $packages = '';
        }
        $store = Db::name('stores')->find($manager['store_id']);
        $counselors = Db::name('counselors')->where('deleted',0)->where('store_id',$manager['store_id'])->select();
        $this->assign([
            'mobile'=>$mobile,
            'customer'=>$customer,
            'packages'=>$packages,
            'store'=>$store,
            'counselors'=>$counselors,
        ]);
        return $this->fetch();
    }
    /*
     * 给客户添加套餐
     */
    public function add(){
        $manager = session('manager');
        if(!$manager){
            $this->redirect('login/login');
        }
        if($this->request->isAjax()){
            $customer_id = $this->request->param('customer_id');
            $counselor_id = $this->request->param('counselor_id');
            $tradeno = $this->request->param('tradeno');
            $coupon = $this->request->param('coupon');
            if(!$customer_id){
                $this->error('请先搜索客户');
            }
            if(!$counselor_id){
                $this->error('请选择咨询师套餐');
            }
            $customer= Db::name('customers')->find($customer_id);
            $counselor = Db::name('counselors')->find($counselor_id);
            $orderno = time();
            $data['orderno'] = $orderno;
            $data['tradeno'] = $tradeno;
            $data['customer_id'] = $customer_id;
            $data['counselor_id'] = $counselor_id;
            $data['store_id'] = $manager['store_id'];
            $data['price'] = $counselor['price'];
            $data['minutes'] = 600;
            $data['amount'] = $counselor['price'] * 9;
            $data['balance'] = 600;
            if($coupon > 0){
                $data['amount'] = $data['amount'] - $coupon;
                $data['remark'] = "￥".($coupon/100)."优惠券";
            }
            $package_id = Db::name('packages')->insertGetId($data);

            $operation_data['store_id'] = $manager['store_id'];
            $operation_data['manager_id'] = $manager['id'];
            $operation_data['type'] = 7;
            $operation_data['target'] = $package_id;
            $rs_operation = Db::name('operations')->insert($operation_data);


            //发微信给客户
            send_wx_msg('customer_access_token',[
                'touser' => $customer['openId'], // openid是发送消息的基础
                'template_id' => config('config.wx_msg.customers.package'), // 模板id
                'url' => '', // 点击跳转地址
                'topcolor' => '#FF0000', // 顶部颜色
                'data' => [
                    'first' => [
                        'value' => "套餐订单：{$orderno}\n付款成功。",
                        'color'=>"#173177"
                    ],
                    'keyword1' => [
                        'value' => "{$counselor['name']}600分钟心理咨询套餐。",
                        'color'=>"#173177"
                    ],
                    'keyword2' => [
                        'value' => "￥".$data['amount']/100,
                        'color'=>"#173177"
                    ],
                    'keyword3' => [
                        'value' => $customer['name'],
                        'color'=>"#173177"
                    ],
                    'remark' => [
                        'value' => "感谢您的信任与支持。",
                        'color'=>"#173177"
                    ],
                ]
            ]);
            //发微信给咨询师
            send_wx_msg('counselor_access_token',[
                'touser' => $counselor['openId'], // openid是发送消息的基础
                'template_id' => config('config.wx_msg.counselors.package'), // 模板id
                'url' => '', // 点击跳转地址
                'topcolor' => '#FF0000', // 顶部颜色
                'data' => [
                    'first' => [
                        'value' => "套餐订单：{$orderno}\n付款成功。",
                        'color'=>"#173177"
                    ],
                    'keyword1' => [
                        'value' => "{$counselor['name']}600分钟心理咨询套餐。",
                        'color'=>"#173177"
                    ],
                    'keyword2' => [
                        'value' => "￥".$data['amount']/100,
                        'color'=>"#173177"
                    ],
                    'keyword3' => [
                        'value' => $customer['name'],
                        'color'=>"#173177"
                    ],
                    'remark' => [
                        'value' => "感谢您的信任与支持。",
                        'color'=>"#173177"
                    ],
                ]
            ]);

            if($package_id  && $rs_operation){
                Db::commit();
                $this->success('保存成功');
            }else{
                Db::rollback();
                $this->error('保存失败');
            }

        }


    }
}