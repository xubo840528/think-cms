<?php
/**
 * Created by PhpStorm.
 * User: mayn
 * Date: 2018/10/18
 * Time: 8:56
 */

namespace app\manage\controller;

use app\manage\logic\WidgetLogic;
use think\Db;
use think\facade\Url;
class Operation extends Base
{
    public function index(){

        $this->assign('site_title', '操作记录');
        $map = [];

        $widget = WidgetLogic::getSingleton()->getWidget();

        // 时间段
        $dateRange = $this->request->param('date_range', '');
        if($dateRange){
            $rangeArr = explode(' - ',$dateRange);
            $end = date('Y-m-d',strtotime($rangeArr[1])+86400);
            $map[] = ['inserted','>',$rangeArr[0]];
            $map[] = ['inserted','<',$end];
        }
        $dateRangeHtml = $widget->search('date_range', [
            'name' => 'date_range',
            'value' => $dateRange,
            'holder' => '时间段'
        ]);
        $this->assign('dateRangeHtml', $dateRangeHtml);
        // 类型
        $type = $this->request->param('type',null);
        if (!is_null($type)) {
            $type = intval($type);
            $map[] = ['type' ,'=', $type];
        }
        $s[0]['name'] = '选择类型';
        $s[0]['value'] = '';
        $k = 1;
        foreach (config('config.operate_type') as $key=>$item) {
            $s[$k]['name'] = $item;
            $s[$k]['value'] = $key;
            $k ++;
        }
        $typeHtml = $widget->search('select', [
            'title' => '类型',
            'name' => 'type',
            'value' => $type,
            'list' => $s
        ]);
        $this->assign('typeHtml', $typeHtml);
        // 关键字
        $keyword = $this->request->param('keyword','','trim');
        if ($keyword) {
            $map[] = ['target' ,'=', $keyword];
        }
        $keywordHtml = $widget->search('keyword', [
            'name' => 'keyword',
            'value' => $keyword,
            'holder' => '订单ID...'
        ]);

        $this->assign('keyword_html', $keywordHtml);
        //查询门店管理员列表
        $manager = Db::name('managers')->where('deleted',0)->select();
        $l[0]['name'] = '门店管理员';
        $l[0]['value'] = '';
        foreach ($manager as $key=>$item) {
            $l[$key+1]['name'] = $item['mobile'];
            $l[$key+1]['value'] = $item['id'];
        }

        // 门店管理员
        $manager_id = $this->request->param('manager_id','');
        if ($manager_id) {
            $manager_id = intval($manager_id);
            $map[] = ['manager_id' ,'=', $manager_id];
        }
        $managerHtml = $widget->search('select', [
            'title' => '门店',
            'name' => 'manager_id',
            'value' => $manager_id,
            'list' => $l
        ]);
        $this->assign('managerHtml', $managerHtml);

        $list = Db::name('operations')->where($map)->order(['deleted'=>'asc','id'=>'desc'])->paginate(20)->each(function ($item,$key){
            $item['manager'] = Db::name('managers')->where('id',$item['manager_id'])->value('mobile');
            $item['store'] = Db::name('stores')->where('id',$item['store_id'])->value('name');
            $item['type'] = config('config.operate_type')[$item['type']];

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
            'delete' => Url::build('delete')
        ];
        $this->assign('action_list', $actionList);
        $this->assign('action_list_json', json_encode($actionList));
        return $this->fetch();
    }
}