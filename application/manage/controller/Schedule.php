<?php
/**
 * Created by PhpStorm.
 * User: mayn
 * Date: 2018/10/15
 * Time: 11:48
 */

namespace app\manage\controller;

use app\manage\logic\WidgetLogic;
use think\Db;
use think\facade\Url;

class Schedule extends Base
{
    public function index(){
        $this->assign('site_title', '档期管理');
        $map = [];
        //查询门店列表
        $store = Db::name('stores')->where('deleted',0)->select();
        $s[0]['name'] = '选择门店';
        $s[0]['value'] = '';
        foreach ($store as $key=>$item) {
            $s[$key+1]['name'] = $item['name'];
            $s[$key+1]['value'] = $item['id'];
        }

        // 门店
        $store_id = $this->request->param('store_id','');
        if ($store_id) {
            $store_id = intval($store_id);
            $map[] = ['store_id' ,'=', $store_id];
        }
        $widget = WidgetLogic::getSingleton()->getWidget();
        $storeHtml = $widget->search('select', [
            'title' => '门店',
            'name' => 'store_id',
            'value' => $store_id,
            'list' => $s
        ]);
        $this->assign('storeHtml', $storeHtml);


        //查询老师列表
        $l[0]['name'] = '选择老师';
        $l[0]['value'] = '';
        if($store_id){
            $counselor = Db::name('counselors')->where(['deleted'=>0,'store_id'=>$store_id])->select();

            foreach ($counselor as $key=>$item) {
                $l[$key+1]['name'] = $item['name'];
                $l[$key+1]['value'] = $item['id'];
            }
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
        // 时间段
        $dateRange = $this->request->param('date_range', '');
        if($dateRange){
            $rangeArr = explode(' - ',$dateRange);
            $end = date('Y-m-d',strtotime($rangeArr[1])+86400);
            $map[] = ['start_time','>',$rangeArr[0]];
            $map[] = ['start_time','<',$end];
        }else{
            $dateRange = date('Y-m-d').' - '.date('Y-m-d',time()+10*86400);
            $map[] = ['start_time','>',date('Y-m-d')];
            $map[] = ['start_time','<',date('Y-m-d',time()+10*86400)];
        }
        $dateRangeHtml = $widget->search('date_range', [
            'name' => 'date_range',
            'value' => $dateRange,
            'holder' => '时间段'
        ]);
        $this->assign('dateRangeHtml', $dateRangeHtml);
        // 关键字
        $keyword = $this->request->param('keyword','','trim');
        if ($keyword) {
            $map[] = ['mobile' ,'=', $keyword];
        }
        $keywordHtml = $widget->search('keyword', [
            'name' => 'keyword',
            'value' => $keyword,
        ]);

        $this->assign('keyword_html', $keywordHtml);
        if($counselor_id){
            $list = Db::name('schedules')->field('counselor_id,LEFT(start_time,10) as date')->where($map)->order(['deleted'=>'asc','id'=>'asc'])->group('LEFT(start_time,10)')->paginate(30)->each(function ($item,$key){
                $item['date_schedules'] = Db::name('schedules')->where(['counselor_id'=>$item['counselor_id'],'LEFT(schedules.start_time,10)'=>$item['date']])->order(['deleted'=>'asc','id'=>'asc'])->select();
                $item['week'] = config('config.week')[date('w',strtotime($item['date']))];
                return $item;
            });
            // 获取分页显示
            $page = $list->render();
            // 模板变量赋值
            $this->assign('list', $list);
            $this->assign('page', $page);
        }else{
            $this->assign('list', null);
            $this->assign('page', null);
        }


        // 操作
        $actionList = [
            'search' => Url::build('index'),
//            'add' => Url::build('add'),
            'edit' => Url::build('edit'),
//            'delete' => Url::build('delete')
        ];
        $this->assign('action_list', $actionList);
        $this->assign('action_list_json', json_encode($actionList));
        return $this->fetch();
    }
    public function ajaxCounselorList(){
        $store_id = $this->request->param('store_id','');
        $counselor = Db::name('counselors')->where(['deleted'=>0,'store_id'=>$store_id])->select();
        $html = '<option value="" selected="">选择老师</option>';
        foreach ($counselor as $key=>$item) {
            $html .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
        }
        return json(['status'=>1,'html'=>$html]);
    }
    /**
     * 创单
     */
    public function edit()
    {
        $id = $this->request->param('data_no');
        if (empty($id)) {
            $this->error('ID为空');
        }

        $action = $this->request->param('action');
        switch ($action) {
            case 'get':
                $return = Db::name('schedules')->find($id);
                $return['time'] = substr($return['start_time'],0,16).' - '.substr($return['end_time'],11,5);
                $return['price'] = (Db::name('counselors')->where('id',$return['counselor_id'])->value('price'))/100;
                $return['status'] = config('config.schedule_status')[$return['state']];
                $this->success('','',$return);
                break;
            case 'save':
                $schedule = Db::name('schedules')->find($id);
                $mobile = $this->request->param('mobile','','trim');
                if($schedule['state'] != 1){
                    $this->error('状态不对');
                }
                if(!$mobile){
                    $this->error('请输入客户手机');
                }
                $customer = Db::name('customers')->where('mobile',$mobile)->find();
                if(!$customer){
                    $this->error('客户不存在');
                }
                $counselor = Db::name('counselors')->where('id',$schedule['counselor_id'])->find();
                $store = Db::name('stores')->find($counselor['store_id']);
                // 启动事务
                Db::startTrans();
                //创建订单
                $orderno = str_pad($schedule['counselor_id'],3,"0",STR_PAD_LEFT).substr(strtotime($schedule['start_time']),0,7);
                $count = Db::name('orders')->where(['deleted'=>0,'customer_id'=>$customer['id'],'counselor_id'=>$schedule['counselor_id']])->count();
                $data = [
                    'orderno' => $orderno,
                    'customer_id' => $customer['id'],
                    'name' => $customer['name'],
                    'mobile' => $customer['mobile'],
                    'customer_orders' => $count+1,
                    'counselor_orders' => $count+1,
                    'schedule_id' => $id,
                    'counselor_id' => $schedule['counselor_id'],
                    'store_id'=> $schedule['store_id'],
                    'price' => $schedule['price'],
                    'rate' => $counselor['rate']
                ];
                $order_id = Db::name('orders')->insertGetId($data);

                $data2 = ['order_id'=>$order_id];
                $rs1 = Db::name('orders_state')->data($data2)->insert();
                $rs2 = Db::name('schedules')->where('id',$id)->update(['state'=>2]);
                //发微信给客户
                send_wx_msg('customer_access_token',[
                    'touser' => $customer['openId'], // openid是发送消息的基础
                    'template_id' => config('config.wx_msg.customers.create_order'), // 模板id
                    'url' => config('config.main_url') . config('config.h5.customers.schedules'), // 点击跳转地址
                    'topcolor' => '#FF0000', // 顶部颜色
                    'data' => [
                        'first' => array('value' => "预约号：{$orderno}\n您已成功预约芒果心理咨询。",'color'=>"#173177"),
                        'keyword1' => array('value' => "{$counselor['name']}心理咨询。",'color'=>"#173177"),
                        'keyword2' => array('value' => "预约成功，请准时到达。",'color'=>"#173177"),
                        'remark' => array('value' => "预约档期：".substr($schedule['start_time'],0,16)."-".substr($schedule['end_time'],0,16)."\n".$store['name']."：".$store['address']."\n温馨提示：若首次预约该老师，请提前15分钟到达。按行业规则需与您签署一份《知情同意书》。门店客服会先向您详细解释。",'color'=>"#173177"),
                    ]
                ]);
                //发微信给咨询师
                send_wx_msg('counselor_access_token',[
                    'touser' => $counselor['openId'], // openid是发送消息的基础
                    'template_id' => config('config.wx_msg.counselors.create_order'), // 模板id
                    'url' => config('config.main_url') . config('config.h5.counselors.schedules'), // 点击跳转地址
                    'topcolor' => '#FF0000', // 顶部颜色
                    'data' => [
                        'first' => array('value' => "预约号：{$orderno}\n又有用户预约您的档期。",'color'=>"#173177"),
                        'keyword1' => array('value' => "{$counselor['name']}心理咨询。",'color'=>"#173177"),
                        'keyword2' => array('value' => "预约成功，请准时到达。",'color'=>"#173177"),
                        'remark' => array('value' => "预约档期：".substr($schedule['start_time'],0,16)."-".substr($schedule['end_time'],0,16)."\n".$store['name']."：".$store['address']."\n温馨提示：若首次预约该老师，请提前15分钟到达。按行业规则需与您签署一份《知情同意书》。门店客服会先向您详细解释。",'color'=>"#173177"),
                    ]
                ]);
                //发短信给客户
                send_sms($mobile,"您已成功预约芒果心理[{$store['name']}] {$counselor['name']} ".substr($schedule['start_time'],0,16)."-".substr($schedule['end_time'],0,16)." ".$store['address']);
                //发短信给咨询师
                send_sms('13262274715',$counselor['name'].'('.($count+1).') '.$schedule['start_time'].' '.$customer['name'].'('.($count+1).') '.$mobile);

                if($order_id && $rs1 && $rs2){
                    // 提交事务
                    Db::commit();
                    $this->success('创单成功');
                }else{
                    // 回滚事务
                    Db::rollback();
                    $this->error('创单失败1');
                }
                break;
            default:
                $this->error('未知操作');
        }
    }
}