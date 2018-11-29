<?php
/**
 * Created by PhpStorm.
 * User: mayn
 * Date: 2018/10/17
 * Time: 10:20
 */

namespace app\manage\controller;

use think\Db;
use app\manage\logic\WidgetLogic;
use think\facade\Url;

class Sms extends Base
{
    public function index(){
        $this->assign('site_title', '验证码管理');
        $map[] = ['deleted' ,'=' ,0];
        // 关键字
        $keyword = $this->request->param('keyword','','trim');
        if ($keyword) {
            $map[] = ['mobile' ,'=', $keyword];
        }
        $widget = WidgetLogic::getSingleton()->getWidget();
        $keywordHtml = $widget->search('keyword', [
            'name' => 'keyword',
            'value' => $keyword,
            'holder' => '用户手机...'
        ]);

        $this->assign('keyword_html', $keywordHtml);
        if($keyword){
            $list = Db::name('sm_code')->where($map)->order(['id'=>'desc'])->paginate(10);
            // 获取分页显示
            $page = $list->render();
            // 模板变量赋值
            $this->assign('list', $list);
            $this->assign('page', $page);
        }else{
            $this->assign('list', '');
            $this->assign('page', '');
        }
        // 操作
        $actionList = [
            'search' => Url::build('index'),
        ];
        $this->assign('action_list', $actionList);
        $this->assign('action_list_json', json_encode($actionList));
        return $this->fetch();
    }
}