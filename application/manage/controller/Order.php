<?php
/**
 * Created by PhpStorm.
 * User: mayn
 * Date: 2018/10/12
 * Time: 14:57
 */

namespace app\manage\controller;

use app\manage\logic\WidgetLogic;
use think\Db;
use think\facade\Url;


class Order extends Base
{
    public function index(){
        $this->assign('site_title', '订单管理');
        $map = [];

        $widget = WidgetLogic::getSingleton()->getWidget();

        // 时间段
        $dateRange = $this->request->param('date_range', '');
        if($dateRange){
            $rangeArr = explode(' - ',$dateRange);
            $end = date('Y-m-d',strtotime($rangeArr[1])+86400);
            $map[] = ['start_time','>',$rangeArr[0]];
            $map[] = ['start_time','<',$end];
        }
        $dateRangeHtml = $widget->search('date_range', [
            'name' => 'date_range',
            'value' => $dateRange,
            'holder' => '时间段'
        ]);
        $this->assign('dateRangeHtml', $dateRangeHtml);

        //查询老师列表
        $counselor = Db::name('counselors')->where('deleted',0)->select();
        $l[0]['name'] = '选择老师';
        $l[0]['value'] = '';
        foreach ($counselor as $key=>$item) {
            $l[$key+1]['name'] = $item['name'];
            $l[$key+1]['value'] = $item['id'];
        }

        // 老师
        $counselor_id = $this->request->param('counselor_id','');
        if ($counselor_id) {
            $counselor_id = intval($counselor_id);
            $map[] = ['counselor_id' ,'=', $counselor_id];
        }
        $counselorHtml = $widget->search('select', [
            'title' => '老师',
            'name' => 'counselor_id',
            'value' => $counselor_id,
            'list' => $l
        ]);
        $this->assign('counselorHtml', $counselorHtml);
        // 状态
        $status = $this->request->param('status',null);
        if (!is_null($status)) {
            $status = intval($status);
            $map[] = ['state' ,'=', $status];
        }
        $s[0]['name'] = '选择状态';
        $s[0]['value'] = '';
        $k = 1;
        foreach (config('config.order_status') as $key=>$item) {
            $s[$k]['name'] = $item;
            $s[$k]['value'] = $key;
            $k ++;
        }
        $statusHtml = $widget->search('select', [
            'title' => '状态',
            'name' => 'status',
            'value' => $status,
            'list' => $s
        ]);
        $this->assign('statusHtml', $statusHtml);
        // 关键字
        $keyword = $this->request->param('keyword','','trim');
        if ($keyword) {
            $map[] = ['mobile' ,'=', $keyword];
        }
        $keywordHtml = $widget->search('keyword', [
            'name' => 'keyword',
            'value' => $keyword,
            'holder' => '用户手机...'
        ]);

        $this->assign('keyword_html', $keywordHtml);


        $list = Db::name('orders')->where($map)->order(['deleted'=>'asc','id'=>'desc'])->paginate(20)->each(function ($item,$key){
            $item['counselor'] = Db::name('counselors')->where('id',$item['counselor_id'])->value('name');
            $item['schedule'] = Db::name('schedules')->where('id',$item['schedule_id'])->value('start_time');
            $item['status'] = config('config.order_status')[$item['state']];
            if($item['state'] == 1){
                $item['minutes'] = floor((time()-strtotime($item['start_time']))/60);
            }
            $item['store'] = Db::name('stores')->where('id',$item['store_id'])->value('name');
            return $item;
        });
        // 获取分页显示
        $page = $list->render();
        // 模板变量赋值
        $this->assign('list', $list);
        $this->assign('page', $page);

        // 操作
        $actionList = [
            'search' => Url::build('index'),
            'add' => Url::build('add'),
            'edit' => Url::build('edit'),
            'edit_price' => Url::build('edit_price'),
            'edit_receive' => Url::build('edit_receive'),
            'delete' => Url::build('delete')
        ];
        $this->assign('action_list', $actionList);
        $this->assign('action_list_json', json_encode($actionList));
        return $this->fetch();
    }
    /*
     * 订单取消
     */
    public function edit(){
        $id = $this->request->param('data_no');
        if (empty($id)) {
            $this->error('ID为空');
        }

        $action = $this->request->param('action');
        switch ($action) {
            case 'get':
                $return = Db::name('orders')->find($id);
                $return['counselor'] = Db::name('counselors')->where('id',$return['counselor_id'])->value('name');
                $return['type'] = 1;
                $this->success('','',$return);
                break;
            case 'save':
                $type = $this->request->param('type');
                $order = Db::name('orders')->find($id);
                if($type == 1){
                    $remark = '客户取消';
                }else{
                    $remark = '老师取消';
                }
                $data = ['state' => 4, 'remark' => $remark];
                Db::name('orders')->where('id',$id)->update($data);
                //订单状态表
                $data2 = ['order_id' => $id, 'state' =>4,];
                Db::name('orders_state')->data($data2)->insert();
                //档期表
                $data3 = ['state' => 1];
                Db::name('schedules')->where('id',$order['schedule_id'])->update($data3);
                //房间状态
                $room = Db::name('rooms')->where('order_id',$id)->find();
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
                break;
            default:
                $this->error('未知操作');
        }
    }
    /*
     * 订单改价
     */
    public function edit_price(){
        $id = $this->request->param('data_no');
        if (empty($id)) {
            $this->error('ID为空');
        }

        $action = $this->request->param('action');
        switch ($action) {
            case 'get':
                $return = Db::name('orders')->find($id);
                $return['receivable'] = '￥'.$return['receivable']/100;
                $return['counselor'] = Db::name('counselors')->where('id',$return['counselor_id'])->value('name');
                $return['price'] = '￥'.$return['price']/100;
                $this->success('','',$return);
                break;
            case 'save':
                $order = Db::name('orders')->find($id);
                $new_price = $this->request->param('new_price',0,'trim');
                if(!$new_price){
                    $this->error('填写修改价格');
                }
                if($order['minutes'] == 0){ // 待咨询
                    $data = [
                        'price' => $new_price * 100,
                    ];
                }else{  //已咨询
                    if(!$order['deducted'] && !$order['balance'] && !$order['cash_value']){  //使用现金
                        $receivable = $retainage = floor(($new_price/60)*$order['minutes'])*100;
                        $commision = ($receivable/100)*($order['rate']/100);
                        $data = [
                            'price' => $new_price * 100,
                            'receivable' => $receivable,
                            'retainage' => $retainage,
                            'commision' => $commision,
                        ];
                    }else{  //使用套餐
                        $receivable  = floor(($new_price/60)*$order['minutes'])*100;
                        $cash_value = floor(($receivable/100)*0.9*100);
                        $marketing_cost = floor(($receivable/100)*0.1*100);
                        $commision = ($receivable/100)*($order['rate']/100);
                        $data = [
                            'price' => $new_price * 100,
                            'receivable' => $receivable,
                            'cash_value' => $cash_value,
                            'marketing_cost' => $marketing_cost,
                            'commision' => $commision,
                        ];
                    }
                }

                $return = Db::name('orders')->where('id',$id)->update($data);

                $this->success('修改价格成功');
                break;
            default:
                $this->error('未知操作');
        }
    }
    /*
     * 订单收款
     */
    public function edit_receive(){
        $id = $this->request->param('data_no');
        if (empty($id)) {
            $this->error('ID为空');
        }

        $action = $this->request->param('action');
        switch ($action) {
            case 'get':
                $order = Db::name('orders')->find($id);
                $order['consult_time'] = substr($order['start_time'],5,11).' - '.substr($order['end_time'],5,11);
                $order['settle_minutes'] = $order['minutes'] - $order['deducted'];
                $order['price'] = $order['price']/100;
                $order['retainage'] = $order['retainage']/100;
                $this->success('','',$order);
                break;
            case 'save':
                $order = Db::name('orders')->find($id);
                if($order['state'] != 2){
                    $this->error('订单状态异常');
                }
                $room = Db::name('rooms')->where('order_id',$id)->find();
                if(!$room){
                    $this->error('房间状态异常');
                }
                $order['tradeno'] = $this->request->param('tradeno');
                if($order['state'] < 5){
                    $order['state'] = 5;
                    Db::name('orders_state')->where('order_id',$id)->update(['state'=>5,'inserted'=>date('Y-m-d H:i:s')]);
                }
                Db::startTrans();
                $rs_order = Db::name('orders')->update($order);
                $rs_customer = Db::name('customers')->where('id',$order['customer_id'])->inc('orders')->update();
                $rs_counselor = Db::name('counselors')->where('id',$order['counselor_id'])->inc('orders')->inc('minutes',$order['minutes'])->update();

                $rs_room = Db::name('rooms')->where('id',$room['id'])->update(['order_id'=>0,'order_state'=>0,'counselor'=>'']);

                $operation_data['store_id'] = $order['store_id'];
                $operation_data['manager_id'] = 9999;
                $operation_data['type'] = 6;
                $operation_data['target'] = $id;
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











                $new_price = $this->request->param('new_price',0,'trim');
                if(!$new_price){
                    $this->error('填写修改价格');
                }
                if($order['minutes'] == 0){ // 待咨询
                    $data = [
                        'price' => $new_price * 100,
                    ];
                }else{  //已咨询
                    if(!$order['deducted'] && !$order['balance'] && !$order['cash_value']){  //使用现金
                        $receivable = $retainage = floor(($new_price/60)*$order['minutes'])*100;
                        $commision = ($receivable/100)*($order['rate']/100);
                        $data = [
                            'price' => $new_price * 100,
                            'receivable' => $receivable,
                            'retainage' => $retainage,
                            'commision' => $commision,
                        ];
                    }else{  //使用套餐
                        $receivable  = floor(($new_price/60)*$order['minutes'])*100;
                        $cash_value = floor(($receivable/100)*0.9*100);
                        $marketing_cost = floor(($receivable/100)*0.1*100);
                        $commision = ($receivable/100)*($order['rate']/100);
                        $data = [
                            'price' => $new_price * 100,
                            'receivable' => $receivable,
                            'cash_value' => $cash_value,
                            'marketing_cost' => $marketing_cost,
                            'commision' => $commision,
                        ];
                    }
                }

                $return = Db::name('orders')->where('id',$id)->update($data);

                $this->success('修改价格成功');
                break;
            default:
                $this->error('未知操作');
        }
    }
    /*
     * 订单开票
     */
    public function ajaxInvoice(){
        if($this->request->isAjax()){
            $id = $this->request->param('id');
            $order = Db::name('orders')->find($id);
            if($order['state'] != 5){
                $this->error('状态不对');
            }
            if(!$order['deducted'] && !$order['balance'] && !$order['cash_value']) {  //使用现金
                $data = [
                    'amount' => $order['retainage']
                ];
            }else{  //使用套餐
                $data = [
                    'amount' => $order['cash_value']
                ];
            }
            $return = Db::name('orders')->where('id',$id)->update($data);
            return json(['status'=>1,'开票成功']);
        }

    }

}