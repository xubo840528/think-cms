<?php
/**
 * Created by PhpStorm.
 * User: mayn
 * Date: 2018/10/19
 * Time: 15:19
 */

namespace app\manage\controller;


use think\Db;

class Crontab extends Base
{
    /* 添加老师的档期
     * 每周一次
     */
    public function addSchedule(){
        $counselor = Db::name('schedules')->field('DISTINCT counselor_id')->select();

        foreach ($counselor as $item) {
            $price = Db::name('counselors')->where('id',$item['counselor_id'])->value('price');
            $schedule = Db::name('schedules')->where('counselor_id',$item['counselor_id'])->order('id','desc')->find();
            $begin_date = substr($schedule['start_time'],0,10);
            $days = round((strtotime("+14 day")-strtotime($begin_date))/3600/24);
            if( $days > 0 && $days < 30){
                for ($i=1;$i<$days;$i++){
                    $date = date('Y-m-d',strtotime("{$begin_date} +{$i} day"));
                    $data = ['counselor_id'=>$item['counselor_id'],'store_id'=>$schedule['store_id'],'start_time'=>$date.' 09:00:00','end_time'=>$date.' 10:00:00','price'=>$price];
                    Db::name('schedules')->data($data)->insert();
                    $data = ['counselor_id'=>$item['counselor_id'],'store_id'=>$schedule['store_id'],'start_time'=>$date.' 10:00:00','end_time'=>$date.' 11:30:00','price'=>$price];
                    Db::name('schedules')->data($data)->insert();
                    $data = ['counselor_id'=>$item['counselor_id'],'store_id'=>$schedule['store_id'],'start_time'=>$date.' 13:00:00','end_time'=>$date.' 14:30:00','price'=>$price];
                    Db::name('schedules')->data($data)->insert();
                    $data = ['counselor_id'=>$item['counselor_id'],'store_id'=>$schedule['store_id'],'start_time'=>$date.' 15:00:00','end_time'=>$date.' 16:30:00','price'=>$price];
                    Db::name('schedules')->data($data)->insert();
                    $data = ['counselor_id'=>$item['counselor_id'],'store_id'=>$schedule['store_id'],'start_time'=>$date.' 17:00:00','end_time'=>$date.' 18:30:00','price'=>$price];
                    Db::name('schedules')->data($data)->insert();
                    $data = ['counselor_id'=>$item['counselor_id'],'store_id'=>$schedule['store_id'],'start_time'=>$date.' 19:00:00','end_time'=>$date.' 20:00:00','price'=>$price];
                    Db::name('schedules')->data($data)->insert();
                    $data = ['counselor_id'=>$item['counselor_id'],'store_id'=>$schedule['store_id'],'start_time'=>$date.' 20:00:00','end_time'=>$date.' 21:30:00','price'=>$price];
                    Db::name('schedules')->data($data)->insert();
                }
            }

        }
        return 'success at '.date('Y-m-d H:i:s');
    }
    /* 每月统计咨询师的数据
     * 每月一次
     */
    public function counselorStat(){
        $begin_time = date('Y-m-01 00:00:00',strtotime('-1 month'));
        $end_time = date("Y-m-d 23:59:59", strtotime(-date('d').'day'));
//        echo $begin_time;
        $counselors = Db::name('counselors')->where('deleted',0)->select();
        foreach ($counselors as $key=>$counselor) {
            $stat = Db::name('orders')
                ->field('counselor_id,COUNT(id) AS total_count,SUM(minutes) AS total_minutes,SUM(commision) AS total_commision')
                ->where('counselor_id',$counselor['id'])
                ->where('state',5)
                ->where('end_time','>',$begin_time)
                ->where('end_time','<',$end_time)
                ->find();
            $check = Db::name('counselors_records')->where('counselor_id',$counselor['id'])->where('records_date',$begin_time)->find();
            if(!$check){
                $data['counselor_id'] = $counselor['id'];
                $data['records_date'] = $begin_time;
                $data['salary'] = $counselor['salary'];
                $data['price'] = $counselor['price'];
                $data['rate'] = $counselor['rate'];
                $data['orders'] = $stat['total_count']?$stat['total_count']:0;
                $data['minutes'] = $stat['total_minutes']?$stat['total_minutes']:0;
                $data['commision'] = $stat['total_commision']?$stat['total_commision']:0;
                Db::name('counselors_records')->insert($data);
            }

//            echo '<pre>';
//            echo $key;
//            print_r($stat);


        }

        return 'success at '.date('Y-m-d H:i:s');

    }
    /* 定时抓取access token
     * 每小时一次
     * 有白名单限制
     */
    public function accessToken(){
        //客户
        $appid = config('config.apps.customer.0');
        $appsecret = config('config.apps.customer.1');
        //获取access token
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
        $result = curl_post($url);
        $access_token_arr = json_decode($result,true);
        if(isset($access_token_arr['access_token'])){
            $access_token = $access_token_arr['access_token'];
            $data['sys_value'] = $access_token;
            $data['updated'] = date('Y-m-d H:i:s');
            Db::name('sys_cache')->where('sys_key','customer_access_token')->update($data);
            //获取jsapi_ticket
            $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi';
            $result = curl_post($url);
            $jsapi_ticket_arr = json_decode($result,true);
            if(isset($jsapi_ticket_arr['ticket'])){
                $jsapi_ticket = $jsapi_ticket_arr['ticket'];
                $data['sys_value'] = $jsapi_ticket;
                $data['updated'] = date('Y-m-d H:i:s');
                Db::name('sys_cache')->where('sys_key','customer_jsapi_ticket')->update($data);
            }
        }
        //咨询师
        $appid = config('config.apps.counselor.0');
        $appsecret = config('config.apps.counselor.1');
        //获取access token
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
        $result = curl_post($url);
        $access_token_arr = json_decode($result,true);
        if(isset($access_token_arr['access_token'])){
            $access_token = $access_token_arr['access_token'];
            $data['sys_value'] = $access_token;
            $data['updated'] = date('Y-m-d H:i:s');
            Db::name('sys_cache')->where('sys_key','counselor_access_token')->update($data);
            //获取jsapi_ticket
            $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi';
            $result = curl_post($url);
            $jsapi_ticket_arr = json_decode($result,true);
            if(isset($jsapi_ticket_arr['ticket'])){
                $jsapi_ticket = $jsapi_ticket_arr['ticket'];
                $data['sys_value'] = $jsapi_ticket;
                $data['updated'] = date('Y-m-d H:i:s');
                Db::name('sys_cache')->where('sys_key','counselor_jsapi_ticket')->update($data);
            }
        }

        return 'success at '.date('Y-m-d H:i:s');
    }
    /*
     * 预约订单提前一天通知客户
     * 每天下午4点
     */
    public function oneDayRemind(){
//        $start_time = '2018-10-31 00:00:00';
        $start_time = date('Y-m-d 00:00:00',strtotime('+1 day'));
        $end_time = date('Y-m-d 00:00:00',strtotime('+2 day'));
        $sql = "SELECT orders.id,orders.orderno,orders.name as customer_name,counselors.name as counselor_name,counselors.openId as counselor_openId,schedules.start_time,schedules.end_time,customers.openId as customer_openId,customers.mobile,stores.name as store_name,stores.address
        FROM orders
        INNER JOIN counselors ON orders.counselor_id = counselors.id 
        INNER JOIN schedules ON orders.schedule_id = schedules.id
        INNER JOIN customers ON orders.customer_id = customers.id
        INNER JOIN stores ON orders.store_id = stores.id
        WHERE orders.state = 0 AND schedules.start_time > '{$start_time}' AND schedules.start_time < '{$end_time}' 
        ORDER BY orders.id ASC";
        $orders = Db::query($sql);
        foreach ($orders as $order) {
            //发微信给客户
            send_wx_msg('customer_access_token',[
                'touser' => $order['customer_openId'], // openid是发送消息的基础
                'template_id' => config('config.wx_msg.customers.create_order'), // 模板id
                'url' => config('config.main_url') . config('config.h5.customers.schedules'), // 点击跳转地址
                'topcolor' => '#FF0000', // 顶部颜色
                'data' => [
                    'first' => [
                        'value' => "预约号：{$order['orderno']}\n您已成功预约芒果心理咨询。",
                        'color'=>"#173177"
                    ],
                    'keyword1' => [
                        'value' => "{$order['counselor_name']}心理咨询。",
                        'color'=>"#173177"
                    ],
                    'keyword2' => [
                        'value' => "预约成功，请准时到达。",
                        'color'=>"#173177"
                    ],
                    'remark' => [
                        'value' => "预约档期：".substr($order['start_time'],0,16)."-".substr($order['end_time'],0,16)."\n".$order['store_name']."：".$order['address'],
                        'color'=>"#173177"
                    ],
                ]
            ]);
            send_sms(config('config.sys_mobile'),$order['counselor_name'].' '.$order['start_time'].' '.$order['customer_name'].' '.$order['mobile']);
        }
    }
    /*
     * 预约订单提前2小时再通知一下
     * 每天7点,8点,11点,13点,15点,17点,18点
     */
    public function twoHourRemind(){
        $start_time = date('Y-m-d H:i:s');
        $end_time = date('Y-m-d H:i:s',time()+7200);
        $sql = "SELECT orders.id,orders.orderno,orders.name as customer_name,
        counselors.name as counselor_name,counselors.openId as counselor_openId,schedules.start_time,schedules.end_time,
        customers.openId as customer_openId,customers.mobile,stores.name as store_name,stores.address
        FROM orders
        INNER JOIN counselors ON orders.counselor_id = counselors.id 
        INNER JOIN schedules ON orders.schedule_id = schedules.id
        INNER JOIN customers ON orders.customer_id = customers.id
        INNER JOIN stores ON orders.store_id = stores.id
        WHERE orders.state = 0 AND schedules.start_time > '{$start_time}' AND schedules.start_time < '{$end_time}' 
        ORDER BY orders.id ASC";
        $orders = Db::query($sql);
        foreach ($orders as $order) {
            //发微信给客户
            send_wx_msg('customer_access_token',[
                'touser' => $order['customer_openId'], // openid是发送消息的基础
                'template_id' => config('config.wx_msg.customers.remind_order'), // 模板id
                'url' => config('config.main_url') . config('config.h5.customers.schedules'), // 点击跳转地址
                'topcolor' => '#FF0000', // 顶部颜色
                'data' => [
                    'first' => [
                        'value' => "预约号：{$order['orderno']}\n您在芒果心理预约的咨询即将在2小时后开始。",
                        'color'=>"#173177"
                    ],
                    'keyword1' => [
                        'value' => "{$order['counselor_name']}心理咨询。",
                        'color'=>"#173177"
                    ],
                    'keyword2' => [
                        'value' => "即将开始，请准时到达。",
                        'color'=>"#173177"
                    ],
                    'remark' => [
                        'value' => "预约档期：".substr($order['start_time'],0,16)."-".substr($order['end_time'],0,16)."\n".$order['store_name']."：".$order['address']."\n温馨提示：若首次预约该老师，请提前15分钟到达。若需更改或取消，请及时联系客服。（联系电话/微信：".config('config.sys_mobile')."）",
                        'color'=>"#173177"
                    ],
                ]
            ]);
            //发微信给咨询师
            send_wx_msg('counselor_access_token',[
                'touser' => $order['counselor_openId'], // openid是发送消息的基础
                'template_id' => config('config.wx_msg.counselors.remind_order'), // 模板id
                'url' => config('config.main_url') . config('config.h5.counselors.schedules'), // 点击跳转地址
                'topcolor' => '#FF0000', // 顶部颜色
                'data' => [
                    'first' => [
                        'value' => "预约号：{$order['orderno']}\n【".$order['customer_name']."】与您预约的咨询即将在2小时后开始。",
                        'color'=>"#173177"
                    ],
                    'keyword1' => [
                        'value' => "{$order['counselor_name']}心理咨询。",
                        'color'=>"#173177"
                    ],
                    'keyword2' => [
                        'value' => "即将开始，请准时到达。",
                        'color'=>"#173177"
                    ],
                    'remark' => [
                        'value' => "预约档期：".substr($order['start_time'],0,16)."-".substr($order['end_time'],0,16)."\n".$order['store_name']."：".$order['address'],
                        'color'=>"#173177"
                    ],
                ]
            ]);
        }
    }
    /*
     * 每天统计一次订单 到 指定邮箱
     * 每天8点
     */
    public function oneDayStat(){

        $start_time = date('Y-m-d 00:00:00',strtotime('-1 day'));
        $end_time = date('Y-m-d 00:00:00');
        $title = date('Y-m-d',strtotime('-1 day'))." 单日统计数据";
        $content = "<pre>";
        $content .= "直营：";
        $rs1 = Db::query("SELECT COUNT(*) AS orders FROM orders WHERE inserted BETWEEN '".$start_time."' AND '".$end_time."' AND rate < 10000");
        $content .= "\n".$title."\n预约：".$rs1[0]['orders'];
        $rs2 = Db::query("SELECT COUNT(*) AS orders, IFNULL(SUM(orders.minutes), 0) AS minutes, IFNULL(SUM(orders.cash_value), 0) AS cash_value, IFNULL(SUM(orders.retainage), 0) AS retainage FROM orders, schedules 
            WHERE schedules.start_time BETWEEN '".$start_time."' AND '".$end_time."' AND orders.schedule_id = schedules.id 
            AND orders.state = 5 AND orders.rate < 10000");
        $content .= "\n订单：".$rs2[0]['orders']."\n时长：".$rs2[0]['minutes']."(m)\n金额：￥".($rs2[0]['cash_value']+$rs2[0]['retainage'])/100;
        $content .= "\n订单流水：￥".($rs2[0]['retainage']/100)."\n套餐流水：￥".($rs2[0]['cash_value']/100);
        $rs3 = Db::query("SELECT IFNULL(SUM(amount), 0) AS amount FROM packages WHERE inserted BETWEEN '".$start_time."' AND '".$end_time."'");
        $content .= "\n套餐购买流水：￥".($rs3[0]['amount']/100);
        $content .= "\n\n共享：";
        $rs4 = Db::query("SELECT COUNT(*) AS orders FROM orders WHERE inserted BETWEEN '".$start_time."' AND '".$end_time."' AND rate = 10000");
        $content .= "\n".$title."\n预约：".$rs4[0]['orders'];
        $rs5 = Db::query("SELECT COUNT(*) AS orders, IFNULL(SUM(orders.minutes), 0) AS minutes, IFNULL(SUM(orders.cash_value), 0) AS cash_value, IFNULL(SUM(orders.retainage), 0) AS retainage FROM orders, schedules 
            WHERE schedules.start_time BETWEEN '".$start_time."' AND '".$end_time."' AND orders.schedule_id = schedules.id 
            AND orders.state = 5 AND orders.rate < 10000");
        $content .= "\n订单：".$rs5[0]['orders']."\n时长：".$rs5[0]['minutes']."(m)\n金额：￥".($rs5[0]['cash_value']+$rs5[0]['retainage'])/100;
        $content .= '</pre>';
        $mail= Array (
            'mailsend' => 2,
            'maildelimiter' => 1,
            'mailusername' => 1,
            'server' => 'smtp.ym.163.com',
            'port' => '25',
            'mail_type' => '1',
            'auth' => '1',
            'from' => 'sys@mangoxinli.com',
            'auth_username' => 'sys@mangoxinli.com',
            'auth_password' => 'sys123321'
        );
        if(sendmail('shenwubin@mangoxinli.com',$title,$content,'sys@mangoxinli.com',$mail,'芒果心理')){
            echo '发送邮件成功';
        }else{
            echo '发送邮件失败';
        }


    }
    /*
     * 每周统计一次订单 到 指定邮箱
     * 每周一8点
     */
    public function oneWeekStat(){
        $start_time = date('Y-m-d 00:00:00',strtotime('-7 day'));
        $end_time = date('Y-m-d 00:00:00');
        $counselors = '(1, 2, 4, 5, 17,38)';
		$stores = '(1, 4)';
		$title = substr($start_time,0,10) . " 周统计数据";
		$content = "<pre>";
		$content .= "公司：";
		$rs1 = Db::query("SELECT COUNT(*) AS 总订单数, SUM(minutes) / 60 AS 总时长, AVG(price) / 100 AS 平均单价, SUM(receivable) / 100 AS 总交易额, COUNT(DISTINCT(counselor_id)) AS 咨询师数量 FROM orders WHERE start_time BETWEEN '".$start_time."' AND '".$end_time."' AND counselor_id IN ".$counselors." AND state = 5");
        $content .= "\n总订单数：\t".$rs1[0]['总订单数'];
		$content .= "\n总时长：\t".round($rs1[0]['总时长'],2)."(h)";
		$content .= "\n平均单价：￥".round($rs1[0]['平均单价'], 2);
		$content .= "\n总交易额：￥".round($rs1[0]['总交易额'], 2);
		$content .= "\n咨询师数量：\t".$rs1[0]['咨询师数量'];
		$rs2 = Db::query("SELECT COUNT(*) AS 咨询师开档期数 FROM schedules WHERE start_time BETWEEN '".$start_time."' AND '".$end_time."' AND counselor_id IN ".$counselors." AND state > 0");
        $content .= "\n咨询师开档期数：\t".$rs2[0]['咨询师开档期数'];
        $rs3 = Db::query("SELECT COUNT(*) AS 房间数量 FROM rooms WHERE store_id IN ".$stores);
        $content .= "\n房间数量：\t".$rs3[0]['房间数量'];
		$content .= "\n房间使用数：\t".round($rs1[0]['总订单数']/($rs3[0]['房间数量']*7), 2);
        $rs4 = Db::query("SELECT COUNT(DISTINCT(customer_id)) AS 新增客户数 FROM orders WHERE start_time BETWEEN '".$start_time."' AND '".$end_time."' AND counselor_id IN ".$counselors." AND state = 5 AND customer_orders = 1");
        $content .= "\n新增客户数：\t".$rs4[0]['新增客户数'];
        $rs5 = Db::query("SELECT COUNT(DISTINCT(customer_id)) AS 下单客户数 FROM orders WHERE start_time BETWEEN '".$start_time."' AND '".$end_time."' AND counselor_id IN ".$counselors." AND state = 5");
        $content .= "\n下单客户数：\t".$rs5[0]['下单客户数'];
        if($rs5[0]['下单客户数']==0){
            $fkl = 0;
        }else{
            $fkl = round(100 - $rs4[0]['新增客户数']/$rs5[0]['下单客户数']*100, 2);
        }
        $content .= "\n复客率：\t".$fkl."%";
        $rs6 = Db::query("SELECT COUNT(*) AS 预约数 FROM orders, orders_state WHERE orders.id = orders_state.order_id AND orders_state.inserted BETWEEN '".$start_time."' AND '".$end_time."' AND orders.counselor_id IN ".$counselors." AND orders_state.state = 0");
        $content .= "\n预约数：\t".$rs6[0]['预约数'];
        $rs7 = Db::query("SELECT COUNT(*) AS 取消数 FROM orders, orders_state WHERE orders.id = orders_state.order_id AND orders_state.inserted BETWEEN '".$start_time."' AND '".$end_time."' AND orders.counselor_id IN ".$counselors." AND orders_state.state = 4");
        $content .= "\n取消数：\t".$rs7[0]['取消数'];
        if($rs6[0]['预约数'] == 0){
            $qxl = 0;
        }else{
            $qxl = round($rs7[0]['取消数']/$rs6[0]['预约数']*100, 2);
        }
        $content .= "\n取消率：\t".$qxl."%";

		$content .= '</pre>';
        $mail= Array (
            'mailsend' => 2,
            'maildelimiter' => 1,
            'mailusername' => 1,
            'server' => 'smtp.ym.163.com',
            'port' => '25',
            'mail_type' => '1',
            'auth' => '1',
            'from' => 'sys@mangoxinli.com',
            'auth_username' => 'sys@mangoxinli.com',
            'auth_password' => 'sys123321'
        );
        if(sendmail('shenwubin@mangoxinli.com',$title,$content,'sys@mangoxinli.com',$mail,'芒果心理')){
            echo '发送邮件成功';
        }else{
            echo '发送邮件失败';
        }
    }
    /*
     * 每月统计一次订单 到 指定邮箱
     * 每月一号8点
     */
    public function oneMonthStat(){


        $start_time = date('Y-m-01 00:00:00',strtotime('-1 month'));
        $end_time = date("Y-m-d 23:59:59", strtotime(-date('d').'day'));
        $counselors = '(1, 2, 4, 5, 17,38)';
        $stores = '(1, 4)';
        $title = substr($start_time,0,10) . " 周统计数据";
        $content = "<pre>";
        $content .= "公司：";
        $rs1 = Db::query("SELECT COUNT(*) AS 总订单数, SUM(minutes) / 60 AS 总时长, AVG(price) / 100 AS 平均单价, SUM(receivable) / 100 AS 总交易额, COUNT(DISTINCT(counselor_id)) AS 咨询师数量 FROM orders WHERE start_time BETWEEN '".$start_time."' AND '".$end_time."' AND counselor_id IN ".$counselors." AND state = 5");
        $content .= "\n总订单数：\t".$rs1[0]['总订单数'];
        $content .= "\n总时长：\t".$rs1[0]['总时长'];
        $content .= "\n平均单价：\t".$rs1[0]['平均单价'];
        $content .= "\n总交易额：\t".$rs1[0]['总交易额'];
        $content .= "\n咨询师数量：\t".$rs1[0]['咨询师数量'];
        $rs2 = Db::query("SELECT COUNT(*) AS 咨询师开档期数 FROM schedules WHERE start_time BETWEEN '".$start_time."' AND '".$end_time."' AND counselor_id IN ".$counselors." AND state > 0");
        $content .= "\n咨询师开档期数：\t".$rs2[0]['咨询师开档期数'];
        $rs3 = Db::query("SELECT COUNT(*) AS 房间数量 FROM rooms WHERE store_id IN ".$stores);
        $content .= "\n房间数量：\t".$rs3[0]['房间数量'];
        $content .= "\n房间使用数：\t".round(($rs1[0]['总订单数']/($rs3[0]['房间数量']*30)),2);
        $rs4 = Db::query("SELECT COUNT(DISTINCT(customer_id)) AS 新增客户数 FROM orders WHERE start_time BETWEEN '".$start_time."' AND '".$end_time."' AND counselor_id IN ".$counselors." AND state = 5 AND customer_orders = 1");
        $content .= "\n新增客户数：\t".$rs4[0]['新增客户数'];
        $rs5 = Db::query("SELECT COUNT(DISTINCT(customer_id)) AS 累计客户数 FROM orders WHERE start_time BETWEEN '".$start_time."' AND '".$end_time."' AND counselor_id IN ".$counselors." AND state = 5");
        $content .= "\n当月下单客户数：\t".$rs5[0]['累计客户数'];
        if ($rs5[0]['累计客户数'] == 0){
            $fkl = 0;
        }else{
            $fkl = round(100 - $rs4[0]['新增客户数']/$rs5[0]['累计客户数']*100,2);
        }
        $content .= "\n复客率：\t".$fkl."%";
        $rs6 = Db::query("SELECT COUNT(*) AS 预约数 FROM orders, orders_state WHERE orders.id = orders_state.order_id AND orders_state.inserted BETWEEN '".$start_time."' AND '".$end_time."' AND orders.counselor_id IN ".$counselors." AND orders_state.state = 0");
        $content .= "\n预约数：\t".$rs6[0]['预约数'];

        $rs7 = Db::query("SELECT COUNT(*) AS 取消数 FROM orders, orders_state WHERE orders.id = orders_state.order_id AND orders_state.inserted BETWEEN '".$start_time."' AND '".$end_time."' AND orders.counselor_id IN ".$counselors." AND orders_state.state = 4");
        $content .= "\n取消数：\t".$rs7[0]['取消数'];
        if($rs6[0]['预约数'] == 0){
            $qxl = 0;
        }else{
            $qxl = round($rs7[0]['取消数']/$rs6[0]['预约数']*100,2);
        }
        $content .= "\n取消率：\t".$qxl."%";
        $rs8 = Db::query("SELECT t1.month, COUNT(DISTINCT(t1.customer_id)), COUNT(t2.customer_id), COUNT(t2.customer_id) / COUNT(DISTINCT(t1.customer_id)) AS avgorders FROM (
				SELECT MIN(YEAR(start_time) * 100 + MONTH(start_time)) AS MONTH, customer_id FROM orders WHERE start_time < '".$end_time."' AND counselor_id IN ".$counselors." AND state = 5 AND customer_orders = 1 GROUP BY customer_id
			) AS t1 LEFT JOIN (
				SELECT YEAR(start_time) * 100 + MONTH(start_time) AS MONTH, customer_id FROM orders WHERE start_time BETWEEN '".$start_time."' AND '".$end_time."' AND counselor_id IN ".$counselors." AND state = 5
			) AS t2 ON t1.customer_id = t2.customer_id
			GROUP BY t1.month");
        
        foreach ($rs8 as $key=>$val){
            $content .= "\n".$val['month']."月客户当月平均下单数：\t".$val['avgorders']."";
        }
        $rs9 = Db::query("SELECT AVG(COUNT) AS avgcount FROM (SELECT orders.customer_id, COUNT(*) AS COUNT FROM (SELECT customer_id, YEAR(start_time) * 100 + MONTH(start_time) AS MONTH FROM orders WHERE start_time < '".$end_time."' AND counselor_id IN ".$counselors." AND state = 5 GROUP BY customer_id, YEAR(start_time) * 100 + MONTH(start_time)) AS orders GROUP BY orders.customer_id) AS orders");
        $content .= "\n客户平均下单月数：\t".$rs9[0]['avgcount'];
        $rs10 = Db::query("SELECT orders.month,
				SUM(CASE orders.num WHEN 1 THEN orders.count ELSE 0 END) AS num1,
				SUM(CASE orders.num WHEN 2 THEN orders.count ELSE 0 END) AS num2,
				SUM(CASE orders.num WHEN 3 THEN orders.count ELSE 0 END) AS num3,
				SUM(CASE orders.num WHEN 4 THEN orders.count ELSE 0 END) AS num4,
				SUM(CASE orders.num WHEN 5 THEN orders.count ELSE 0 END) AS num5,
				SUM(CASE orders.num WHEN 6 THEN orders.count ELSE 0 END) AS num6,
				SUM(CASE orders.num WHEN 7 THEN orders.count ELSE 0 END) AS num7,
				SUM(CASE orders.num WHEN 8 THEN orders.count ELSE 0 END) AS num8,
				SUM(CASE orders.num WHEN 9 THEN orders.count ELSE 0 END) AS num9,
				SUM(CASE WHEN orders.num > 9 THEN orders.count ELSE 0 END) AS num10,
				SUM(orders.count) AS num
			FROM (
				SELECT orders.month, orders.count AS num, COUNT(*) AS COUNT FROM (
					SELECT MIN(YEAR(start_time) * 100 + MONTH(start_time)) AS MONTH, customer_id, COUNT(*) AS COUNT FROM orders WHERE start_time < '".$end_time."' AND counselor_id IN ".$counselors." AND state = 5 GROUP BY customer_id
				) AS orders GROUP BY orders.month, orders.count
			) AS orders GROUP BY orders.month");
        $content .= "\n月份\t下单1次\t下单2次\t下单3次\t下单4次\t下单5次\t下单6次\t下单7次\t下单8次\t下单9次\t下单10次(含)以上\t月客户总数";
        foreach ($rs10 as $val) {
            $content .= "\n{$val['month']}\t{$val['num1']}\t{$val['num2']}\t{$val['num3']}\t{$val['num4']}\t{$val['num5']}\t{$val['num6']}\t{$val['num7']}\t{$val['num8']}\t{$val['num9']}\t{$val['num10']}\t{$val['num']}";
        }
        $content .= "\n\n\n门店：";
        $rs11 = Db::query("SELECT * FROM (SELECT id, NAME FROM stores WHERE id IN ".$counselors.") AS t0 
			LEFT JOIN (SELECT store_id, COUNT(*) AS 总订单数, SUM(minutes) / 60 AS 总时长, AVG(price) / 100 AS 平均单价, SUM(receivable) / 100 AS 总交易额, COUNT(DISTINCT(counselor_id)) AS 咨询师数量 FROM orders WHERE start_time BETWEEN '".$start_time."' AND '".$end_time."' AND counselor_id IN ".$counselors." AND state = 5 GROUP BY store_id) AS t1 ON t0.id = t1.store_id 
			LEFT JOIN (SELECT store_id, COUNT(*) AS 咨询师开档期数 FROM schedules WHERE start_time BETWEEN '".$start_time."' AND '".$end_time."' AND counselor_id IN ".$counselors." AND state > 0 GROUP BY store_id) AS t2 ON t0.id = t2.store_id 
			LEFT JOIN (SELECT store_id, COUNT(*) AS 房间数量 FROM rooms WHERE store_id IN ".$stores." GROUP BY store_id) AS t3 ON t0.id = t3.store_id 
			LEFT JOIN (SELECT store_id, COUNT(DISTINCT(customer_id)) AS 新增客户数 FROM orders WHERE start_time BETWEEN '".$start_time."' AND '".$end_time."' AND counselor_id IN ".$counselors." AND state = 5 AND customer_orders = 1 GROUP BY store_id) AS t4 ON t0.id = t4.store_id 
			LEFT JOIN (SELECT store_id, COUNT(DISTINCT(customer_id)) AS 当月下单客户数 FROM orders WHERE start_time BETWEEN '".$start_time."' AND '".$end_time."' AND counselor_id IN ".$counselors." AND state = 5 GROUP BY store_id) AS t5 ON t0.id = t5.store_id 
			LEFT JOIN (SELECT store_id, COUNT(*) AS 预约数 FROM orders, orders_state WHERE orders.id = orders_state.order_id AND orders_state.inserted BETWEEN '".$start_time."' AND '".$end_time."' AND counselor_id IN ".$counselors." AND orders_state.state = 0 GROUP BY store_id) AS t6 ON t0.id = t6.store_id 
			LEFT JOIN (SELECT store_id, COUNT(*) AS 取消数 FROM orders, orders_state WHERE orders.id = orders_state.order_id AND orders_state.inserted BETWEEN '".$start_time."' AND '".$end_time."' AND counselor_id IN ".$counselors." AND orders_state.state = 4 GROUP BY store_id) AS t7 ON t0.id = t7.store_id 
			LEFT JOIN (SELECT store_id, AVG(COUNT) FROM (SELECT orders.customer_id, store_id, COUNT(*) AS COUNT FROM (SELECT customer_id, store_id, YEAR(start_time) * 100 + MONTH(start_time) AS MONTH FROM orders WHERE start_time < '".$end_time."' AND counselor_id IN ".$counselors." AND state = 5 GROUP BY customer_id, store_id, YEAR(start_time) * 100 + MONTH(start_time)) AS orders GROUP BY orders.customer_id, store_id) AS orders GROUP BY store_id) AS t8 ON t0.id = t8.store_id");
        foreach ($rs11 as $val) {
            $content .="\n\n".$val['NAME'];
            $content .= "\n总订单数：\t".$val['总时长']."";
            $content .= "\n总时长：\t".$val['总订单数']."(h)";
            $content .= "\n平均单价：￥\t".round($val['平均单价'],2)."";
            $content .= "\n总交易额：￥\t".round($val['总交易额'],2)."";
            $content .= "\n咨询师数量：\t".$val['咨询师数量']."";
            $content .= "\n咨询师开档期数：\t".$val['咨询师开档期数']."";
            $content .= "\n房间数量：\t".$val['房间数量']."";
            $content .= "\n房间使用数：\t".$val['总订单数']."";
            $content .= "\n总订单数：\t".$val['总订单数']."";
            $content .= "\n总订单数：\t".$val['总订单数']."";




        }


    }
}