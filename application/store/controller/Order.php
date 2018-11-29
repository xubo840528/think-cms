<?php

namespace app\store\controller;

use think\Db;

class Order extends Base
{
    public function index(){
        $manager = session('manager');
        if(!$manager){
            $this->redirect('login/login');
        }
        $where = "orders.store_id = ".$manager['store_id'];
        $start_time = $this->request->param('start_time',date('Y-m-d'));
        $end_time = $this->request->param('end_time',date('Y-m-d',strtotime('+7 day')));
        $where .= " AND schedules.start_time > '".$start_time."' AND schedules.start_time < '".$end_time."'";

        $counselor_id = $this->request->param('counselor_id');
        if($counselor_id){
            $where .= " AND orders.counselor_id = ".$counselor_id;
        }
        $state = $this->request->param('state','');
        if (is_numeric($state)) {
            $where .= " AND orders.state = ".$state;
        }
        $mobile = $this->request->param('mobile');
        if($mobile){
            $where .= " AND orders.mobile = ".$mobile;
        }
        $store = Db::name('stores')->find($manager['store_id']);
        $counselors = Db::name('counselors')->where('deleted',0)->where('store_id',$manager['store_id'])->select();
        $sql = "SELECT 
        orders.id,orders.name as customer,orders.price,orders.start_time as st,orders.end_time as et,orders.minutes,
        orders.receivable,orders.deducted,orders.retainage,orders.state,orders.balance,
        schedules.start_time,schedules.end_time,
        counselors.name as counselor
        FROM orders, schedules,counselors 
        WHERE schedules.id = orders.schedule_id AND orders.counselor_id = counselors.id AND {$where} 
        ORDER BY orders.id ASC";
        $list = Db::query($sql);
        $this->assign([
            'start_time'=>$start_time,
            'end_time'=>$end_time,
            'counselor_id'=>$counselor_id,
            'mobile'=>$mobile,
            'state'=>$state,
            'store'=>$store,
            'counselors'=>$counselors,
            'list'=>$list,
            'order_state'=>config('config.order_status')
        ]);
        return $this->fetch();
    }
    public function cancel(){
        $type = $this->request->param('type');
        $order_id = $this->request->param('order_id');
        $order = Db::name('orders')->find($order_id);
        if($type == 1){
            $remark = '客户取消';
        }else{
            $remark = '老师取消';
        }
        $data = ['state' => 4,'remark' => $remark];
        Db::name('orders')->where('id',$order_id)->update($data);
        //订单状态表
        $data2 = ['order_id' => $order_id, 'state' =>4];
        Db::name('orders_state')->data($data2)->insert();
        //档期表
        $data3 = ['state' => 1];
        Db::name('schedules')->where('id',$order['schedule_id'])->update($data3);
        //房间状态
        $room = Db::name('rooms')->where('order_id',$order_id)->find();
        if($room){
            Db::name('rooms')->where('id',$room['id'])->update(['order_id'=>0,'order_state'=>0,'counselor'=>'']);
        }
        $customer = Db::name('customers')->where('id',$order['customer_id'])->find();
        $counselor = Db::name('counselors')->where('id',$order['counselor_id'])->find();
        $schedule = Db::name('schedules')->find($order['schedule_id']);
        //发微信给客户
        send_wx_msg('customer_access_token',[
            'touser' => $customer['openId'], // openid是发送消息的基础
            'template_id' => config('config.wx_msg.customers.cancel'), // 模板id
            'url' => config('config.main_url') . config('config.h5.customers.schedules'), // 点击跳转地址
            'topcolor' => '#FF0000', // 顶部颜色
            'data' => [
                'first' => array('value' => "预约号：{$order['orderno']}\n您在芒果心理预约的咨询已取消。",'color'=>"#173177"),
                'keyword1' => array('value' => "{$counselor['name']}心理咨询。",'color'=>"#173177"),
                'keyword2' => array('value' => "已取消。",'color'=>"#173177"),
                'remark' => array('value' => "取消档期：".substr($schedule['start_time'],0,16)."-".substr($schedule['end_time'],0,16)."\n取消原因：".$remark,'color'=>"#173177"),
            ]
        ]);
        //发微信给咨询师
        send_wx_msg('counselor_access_token',[
            'touser' => $counselor['openId'], // openid是发送消息的基础
            'template_id' => config('config.wx_msg.counselors.cancel'), // 模板id
            'url' => config('config.main_url') . config('config.h5.counselors.schedules'), // 点击跳转地址
            'topcolor' => '#FF0000', // 顶部颜色
            'data' => [
                'first' => array('value' => "预约号：{$order['orderno']}\n【{$order['name']}】的咨询预约已取消。",'color'=>"#173177"),
                'keyword1' => array('value' => "{$counselor['name']}心理咨询。",'color'=>"#173177"),
                'keyword2' => array('value' => "已取消。",'color'=>"#173177"),
                'remark' => array('value' => "取消档期：".substr($schedule['start_time'],0,16)."-".substr($schedule['end_time'],0,16)."\n取消原因：".$remark,'color'=>"#173177"),
            ]
        ]);

        $this->success('取消成功');
    }

}