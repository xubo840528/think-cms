<?php

namespace app\store\controller;


use think\Db;

class Index extends Base
{
    public function index(){
        $manager = session('manager');
        if(!$manager){
            $this->redirect('login/login');
        }
        $store = Db::name('stores')->find($manager['store_id']);
        $this->assign([
            'store'=>$store,
        ]);
        return $this->fetch();
    }
    public function rooms(){
        $manager = session('manager');
        if(!$manager){
            $this->redirect('login/login');
        }
        $store = Db::name('stores')->find($manager['store_id']);
        $rooms = Db::name('rooms')->where(['deleted'=>0,'store_id'=>$manager['store_id']])->paginate(20)->each(function ($item,$key){
            if(!$item['order_id']){
                $item['status'] = '空闲中';
            }elseif(!$item['order_state']){
                $item['status'] = '待咨询';
            }elseif($item['order_state']==1){
                $item['status'] = '咨询中';
            }elseif($item['order_state']==2){
                $item['status'] = '待收款';
            }
            return $item;
        });
        $this->assign([
            'store'=>$store,
            'rooms'=>$rooms,
        ]);
        return $this->fetch();
    }
    public function room($id){
        $room = Db::name('rooms')->find($id);
        if(!$room['order_id'] && !$room['order_state']){ //空闲中、待签入
            $store = Db::name('stores')->find($room['store_id']);
            $start_time = date('Y-m-d');
            $end_time = date("Y-m-d",strtotime("+1 day"));
            $orders = Db::query("SELECT orders.id,orders.name as customer,schedules.start_time,schedules.end_time,counselors.name as counselor
                FROM orders, schedules,counselors 
                WHERE  schedules.id = orders.schedule_id AND orders.counselor_id = counselors.id 
                AND schedules.start_time BETWEEN '{$start_time}' AND '{$end_time}' 
                AND orders.store_id = {$room['store_id']} AND orders.state = 0 AND schedules.state = 2 ORDER BY schedules.start_time ASC");
            $this->assign('orders',$orders);
            $html = $this->fetch('check_in');
            return json(['status'=>1,'title'=>$room['name'].'(空闲中)','html'=>$html]);
        }elseif($room['order_id'] && !$room['order_state']){ //待咨询、未开始
            $order = Db::name('orders')->find($room['order_id']);
            $counselor = Db::name('counselors')->where('id',$order['counselor_id'])->find();
            $this->assign('order',$order);
            $this->assign('counselor',$counselor);
            $html = $this->fetch('check_start');
            return json(['status'=>1,'title'=>$room['name'].'(待咨询)','html'=>$html]);
        }elseif($room['order_id'] && $room['order_state']==1){ //咨询中
            $order = Db::name('orders')->find($room['order_id']);
            $order['latest_minutes'] = floor((time() - strtotime($order['start_time']))/60);
            $counselor = Db::name('counselors')->where('id',$order['counselor_id'])->find();
            $this->assign('order',$order);
            $this->assign('counselor',$counselor);
            $html = $this->fetch('check_using');
            return json(['status'=>1,'title'=>$room['name'].'(咨询中)','html'=>$html]);
        }elseif($room['order_id'] && $room['order_state']==2){ //待收款（咨询结束）
            $order = Db::name('orders')->find($room['order_id']);
            $counselor = Db::name('counselors')->where('id',$order['counselor_id'])->find();
            $this->assign('order',$order);
            $this->assign('counselor',$counselor);
            $html = $this->fetch('check_pay');
            return json(['status'=>1,'title'=>$room['name'].'(待收款)','html'=>$html]);
        }
    }

    /**
     * 订单签入（未开始）
     */
    public function checkIn(){
        $manager = session('manager');
        $room_id = $this->request->param('room_id');
        $order_id = $this->request->param('order_id');
        if(!$order_id){
            $this->error('请选择要签入的订单');
        }
        $room = Db::name('rooms')->find($room_id);
        if($room['order_id']>0){
            $this->error('已被占用');
        }
        $order = Db::name('orders')->find($order_id);
        if($order['store_id'] != $room['store_id']){
            $this->error('此订单不属于该门店！');
        }
        $counuselor = Db::name('counselors')->where('id',$order['counselor_id'])->value('name');
        $rs = Db::name('rooms')->where('id',$room_id)->update(['order_id'=>$order_id,'order_state'=>0,'counselor'=>$counuselor]);
        if($rs){
            $this->success('签入成功');
        }else{
            $this->error('签入失败');
        }
    }
    /*
     * 签出
     */
    public function checkOut(){
        $manager = session('manager');
        $room_id = $this->request->param('room_id');
        $order_id = $this->request->param('order_id');
        $rs_room = Db::name('rooms')->where('id',$room_id)->update(['order_id'=>0,'order_state'=>0,'counselor'=>'']);
        $rs_order = Db::name('orders')->where('id',$order_id)->update(['state'=>0]);
        if($rs_room && $rs_order){
            $this->success('签出成功');
        }else{
            $this->error('签出失败');
        }
    }

    /**
     * 开始咨询
     */
    public function checkStart(){
        $manager = session('manager');
        $order_id = $this->request->param('order_id');
        $order = Db::name('orders')->find($order_id);
        if($order['state'] != 0){
            $this->error('订单状态异常');
        }
        $order_data['start_time'] = date('Y-m-d H:i:s');
        $order_data['state'] = 1;
        Db::startTrans();
        $rs_order = Db::name('orders')->where('id',$order_id)->update($order_data);
        $rs_order_state = Db::name('orders_state')->where('order_id',$order_id)->update(['state'=>1,'inserted'=>date('Y-m-d H:i:s')]);
        $rs_room = Db::name('rooms')->where('order_id',$order_id)->update(['order_state'=>1]);
        $rs_schedule = Db::name('schedules')->where('id',$order['schedule_id'])->update(['state'=>3]);
        $customer = Db::name('customers')->where('id',$order['customer_id'])->find();
        $counselor = Db::name('counselors')->where('id',$order['counselor_id'])->find();
        $schedule = Db::name('schedules')->find($order['schedule_id']);
        $store = Db::name('stores')->find($counselor['store_id']);

        //发微信给客户
        send_wx_msg('customer_access_token',[
            'touser' => $customer['openId'], // openid是发送消息的基础
            'template_id' => config('config.wx_msg.customers.start'), // 模板id
            'url' => config('config.main_url') . config('config.h5.customers.schedules'), // 点击跳转地址
            'topcolor' => '#FF0000', // 顶部颜色
            'data' => [
                'first' => [
                    'value' => "预约号：{$order['orderno']}\n您的预约已开始。",
                    'color'=>"#173177"
                ],
                'keyword1' => [
                    'value' => "{$counselor['name']}心理咨询。",
                    'color'=>"#173177"
                ],
                'keyword2' => [
                    'value' => "已开始。",
                    'color'=>"#173177"
                ],
                'remark' => [
                    'value' => "预约档期：".substr($schedule['start_time'],0,16)."-".substr($schedule['end_time'],0,16)."\n".$store['name']."：".$store['address'],
                    'color'=>"#173177"
                ],
            ]
        ]);
        $operation_data['store_id'] = $order['store_id'];
        $operation_data['manager_id'] = $manager['id'];
        $operation_data['type'] = 3;
        $operation_data['target'] = $order_id;
        $rs_operation = Db::name('operations')->insert($operation_data);
        if($rs_order && $rs_order_state && $rs_room && $rs_schedule && $rs_operation){
            Db::commit();
            $this->success('咨询开始成功');
        }else {
            // 回滚事务
            Db::rollback();
            $this->error('咨询开始失败');
        }
    }
    /*
     * 结束咨询
     */
    public function checkEnd(){
        $manager = session('manager');
        $order_id = $this->request->param('order_id');
        $order = Db::name('orders')->find($order_id);
        if($order['state'] != 1){
            $this->error('订单状态异常');
        }
        $room = Db::name('rooms')->where('order_id',$order_id)->find();
        $order['end_time'] = date('Y-m-d H:i:s');
        $order['state'] = 2;
        $order['minutes'] = floor((strtotime($order['end_time'])-strtotime($order['start_time']))/60);
        $order['receivable'] = floor(($order['price']/100/60)*$order['minutes'])*100;

        if($order['minutes'] > $order['deducted']){
            $packages = Db::name('packages')->where('customer_id',$order['customer_id'])->where('counselor_id',$order['counselor_id'])->where('balance','>',0)->select();
            foreach ($packages as $package) {
                $difference = $order['minutes'] - $order['deducted'];
                if($difference > $package['balance']){
                    $order['deducted'] += $package['balance'];
                    $order['cash_value'] += $package['amount'] * $package['balance'] / $package['minutes'];
                    Db::name('packages')->where('id',$package['id'])->update(['balance'=>0]);
                    continue ;
                }
                $balance = $package['balance'] - $difference;
                Db::name('packages')->where('id',$package['id'])->update(['balance'=>$balance]);
                $order['deducted'] = $order['minutes'];
                $order['cash_value'] += $package['amount'] * $difference / $package['minutes'];
                break ;
            }
            $order['balance'] = 0;
            $packages = Db::name('packages')->where('customer_id',$order['customer_id'])->where('counselor_id',$order['counselor_id'])->where('balance','>',0)->select();

            foreach ($packages as $package) {
                $order['balance'] += $package['balance'];
            }
        }
        $order['marketing_cost'] = $order['price'] * $order['deducted'] / 60 - $order['cash_value'];
        $order['retainage'] = $order['price'] * ($order['minutes'] - $order['deducted']) / 60 - $order['deposit'];
        $order['retainage'] = floor($order['retainage'] / 100) * 100;
        $order['commision'] = $order['receivable']/100 * $order['rate'] / 100;

        Db::startTrans();
        $rs_order = Db::name('orders')->update($order);
        $rs_order_state = Db::name('orders_state')->where('order_id',$order_id)->update(['state'=>2,'inserted'=>date('Y-m-d H:i:s')]);
        $rs_room = Db::name('rooms')->where('id',$room['id'])->update(['order_state'=>2]);

        $operation_data['store_id'] = $order['store_id'];
        $operation_data['manager_id'] = $manager['id'];
        $operation_data['type'] = 4;
        $operation_data['target'] = $order_id;
        $rs_operation = Db::name('operations')->insert($operation_data);

        $customer = Db::name('customers')->where('id',$order['customer_id'])->find();
        $counselor = Db::name('counselors')->where('id',$order['counselor_id'])->find();
        //发微信给客户
        send_wx_msg('customer_access_token',[
            'touser' => $customer['openId'], // openid是发送消息的基础
            'template_id' => config('config.wx_msg.customers.end'), // 模板id
            'url' => config('config.main_url') . config('config.h5.customers.schedules'), // 点击跳转地址
            'topcolor' => '#FF0000', // 顶部颜色
            'data' => [
                'first' => [
                    'value' => "预约号：{$order['orderno']}\n您的预约已完成。",
                    'color'=>"#173177"
                ],
                'keyword1' => [
                    'value' => "{$counselor['name']}心理咨询。",
                    'color'=>"#173177"
                ],
                'keyword2' => [
                    'value' => "待支付。",
                    'color'=>"#173177"
                ],
                'remark' => [
                    'value' => "单价：￥".($order['price']/100)."/小时\n时长：".$order['minutes']."分钟\n金额：￥".($order['retainage']/100)."",
                    'color'=>"#173177"
                ],
            ]
        ]);


        if($rs_order && $rs_order_state && $rs_room && $rs_operation){
            Db::commit();
            $this->success('咨询结束成功');
        }else{
            Db::rollback();
            $this->error('咨询结束失败');
        }
    }

    /**
     * 收款（套餐和现金）
     */
    public function checkPay(){
        $manager = session('manager');
        $order_id = $this->request->param('order_id');
        $order = Db::name('orders')->find($order_id);
        $room = Db::name('rooms')->where('order_id',$order_id)->find();
        if(!in_array($order['state'],[2,3,5])){
            $this->error('订单状态异常');
        }
        $order['tradeno'] = $this->request->param('tradeno');
        if($order['state'] < 5){
            $order['state'] = 5;
            Db::name('orders_state')->where('order_id',$order_id)->update(['state'=>5,'inserted'=>date('Y-m-d H:i:s')]);
        }
        Db::startTrans();
        $rs_order = Db::name('orders')->update($order);
        $rs_customer = Db::name('customers')->where('id',$order['customer_id'])->inc('orders')->update();
        $rs_counselor = Db::name('counselors')->where('id',$order['counselor_id'])->inc('orders')->inc('minutes',$order['minutes'])->update();

        $rs_room = Db::name('rooms')->where('id',$room['id'])->update(['order_id'=>0,'order_state'=>0,'counselor'=>'']);

        $operation_data['store_id'] = $order['store_id'];
        $operation_data['manager_id'] = $manager['id'];
        $operation_data['type'] = 6;
        $operation_data['target'] = $order_id;
        $rs_operation = Db::name('operations')->insert($operation_data);


        $customer = Db::name('customers')->where('id',$order['customer_id'])->find();
        $counselor = Db::name('counselors')->where('id',$order['counselor_id'])->find();
        //发微信给客户
        send_wx_msg('customer_access_token',[
            'touser' => $customer['openId'], // openid是发送消息的基础
            'template_id' => config('config.wx_msg.customers.payment'), // 模板id
            'url' => config('config.main_url') . config('config.h5.customers.historys'), // 点击跳转地址
            'topcolor' => '#FF0000', // 顶部颜色
            'data' => [
                'first' => [
                    'value' => "预约号：{$order['orderno']}\n付款成功。",
                    'color'=>"#173177"
                ],
                'keyword1' => [
                    'value' => "{$counselor['name']}心理咨询。",
                    'color'=>"#173177"
                ],
                'keyword2' => [
                    'value' => "已完成。",
                    'color'=>"#173177"
                ],
                'remark' => [
                    'value' => "希望本次服务对您有帮助。",
                    'color'=>"#173177"
                ],
            ]
        ]);
        //发微信给咨询师
        send_wx_msg('counselor_access_token',[
            'touser' => $counselor['openId'], // openid是发送消息的基础
            'template_id' => config('config.wx_msg.counselors.payment'), // 模板id
            'url' => config('config.main_url') . config('config.h5.counselors.historys'), // 点击跳转地址
            'topcolor' => '#FF0000', // 顶部颜色
            'data' => [
                'first' => [
                    'value' => "预约号：{$order['orderno']}\n已确认收款。",
                    'color'=>"#173177"
                ],
                'keyword1' => [
                    'value' => "{$counselor['name']}心理咨询。",
                    'color'=>"#173177"
                ],
                'keyword2' => [
                    'value' => "已完成。",
                    'color'=>"#173177"
                ],
                'remark' => [
                    'value' => "时长：".$order['minutes']."分钟\n佣金：￥".($order['commision']/100)."",
                    'color'=>"#173177"
                ],
            ]
        ]);

        if($rs_order && $rs_customer && $rs_counselor && $rs_room && $rs_operation){
            Db::commit();
            $this->success('收款成功');
        }else{
            Db::rollback();
            $this->error('收款失败');
        }
    }

}