<?php
/**
 * Created by PhpStorm.
 * User: mayn
 * Date: 2018/10/9
 * Time: 14:49
 */

namespace app\manage\controller;


use app\manage\logic\WidgetLogic;
use think\Db;
use think\facade\Url;

class Counselor extends Base
{
    /**
     * 老师管理
     *
     * @return string
     */
    public function index()
    {
        $this->assign('site_title', '老师管理');
        $map = [];
        //查询门店列表
        $store = Db::name('stores')->where('deleted',0)->select();
        $l[0]['name'] = '选择门店';
        $l[0]['value'] = '';
        foreach ($store as $key=>$item) {
            $l[$key+1]['name'] = $item['name'];
            $l[$key+1]['value'] = $item['id'];
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
            'list' => $l
        ]);
        $this->assign('storeHtml', $storeHtml);
        // 关键字
        $keyword = $this->request->param('keyword','','trim');
        if ($keyword) {
            $map[] = ['name' ,'like', '%'.$keyword.'%'];
        }
        $keywordHtml = $widget->search('keyword', [
            'name' => 'keyword',
            'value' => $keyword,
            'holder' => '老师昵称...'
        ]);

        $this->assign('keyword_html', $keywordHtml);

        $list = Db::name('counselors')->where($map)->order(['deleted'=>'asc','sort'=>'desc','id'=>'asc'])->paginate(20)->each(function ($item,$key){
            $item['store'] = Db::name('stores')->where('id',$item['store_id'])->value('name');
            if($item['type']==0){
                $item['type'] = '认证';
            }elseif($item['type']==1){
                $item['type'] = '签约';
            }elseif($item['type']==2){
                $item['type'] = '共享';
            }
            $widget = WidgetLogic::getSingleton()->getWidget();
            $modifyUrl = Url::build('modify');
            $item['status_html'] = $widget->table('switch', [
                'value' => $item['deleted'],
                'on' => 0,
                'off' => 1,
                'field' => 'deleted',
                'url' => $modifyUrl,
                'id' => $item['id']
            ]);

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
        cookie('return_url',$this->request->url());

        return $this->fetch();


    }
    /**
     * 添加老师
     */
    public function add()
    {
        if($this->request->isPost()){
            $data = $this->request->param();
//            $avatar = $data['avatar'];
//            unset($data['avatar']);
            $data['salary'] = $data['salary'] * 100;
            $data['rate'] = $data['rate'] * 100;
            $data['price'] = $data['price'] * 100;
            $id = Db::name('counselors')->insertGetId($data);
            if($id){
//                if($avatar)
//                    copy('.'.$avatar,'./upload/avatar/'.$id.'.'. pathinfo($avatar)['extension']);
                //添加一个老师，自动给老师添加老师日程
                $this->addSchedule($id);
                return $this->success('添加成功',cookie('return_url'));
            }else{
                return $this->error('添加失败');
            }
        }else{
            $this->assign('site_title', '添加老师');
            //查询门店列表
            $store = Db::name('stores')->where('deleted',0)->select();
            foreach ($store as $key=>$item) {
                $l[$key]['name'] = $item['name'];
                $l[$key]['value'] = $item['id'];
            }
            $widget = WidgetLogic::getSingleton()->getWidget();
            // 门店
            $selectHtml = $widget->form('select', [
                'title' => '门店',
                'name' => 'store_id',
                'value' => '',
                'list' => $l
            ]);
            $this->assign('select_html', $selectHtml);
            // 类型
            $typeHtml = $widget->form('select', [
                'title' => '类型',
                'name' => 'type',
                'value' => '',
                'list' => [
                    ['name' => '认证','value'=>0],
                    ['name' => '签约','value'=>1],
                    ['name' => '共享','value'=>2],
                ]
            ]);
            $this->assign('typeHtml', $typeHtml);
            //昵称
            $nameHtml = $widget->form('text', ['title' => '昵称', 'name' => 'name', 'value' => '']);
            $this->assign('nameHtml', $nameHtml);
            //照片
            $imageHtml = $widget->form('image', [
                'title' => '照片',
                'name' => 'avatar'
            ]);
            $this->assign('imageHtml', $imageHtml);
            //排序
            $sortHtml = $widget->form('text', ['title' => '排序', 'name' => 'sort', 'value' => '0']);
            $this->assign('sortHtml', $sortHtml);
            //手机
            $mobileHtml = $widget->form('text', ['title' => '手机', 'name' => 'mobile', 'value' => '']);
            $this->assign('mobileHtml', $mobileHtml);
            //密码
            $passwordHtml = $widget->form('text', ['title' => '密码', 'name' => 'password', 'value' => '']);
            $this->assign('passwordHtml', $passwordHtml);
            //编码
            $numHtml = $widget->form('text', ['title' => '编码', 'name' => 'num', 'value' => '']);
            $this->assign('numHtml', $numHtml);
            //底薪
            $salaryHtml = $widget->form('text', ['title' => '底薪', 'name' => 'salary', 'value' => '']);
            $this->assign('salaryHtml', $salaryHtml);
            //单价
            $priceHtml = $widget->form('text', ['title' => '单价', 'name' => 'price', 'value' => '']);
            $this->assign('priceHtml', $priceHtml);
            //提成(%)
            $rateHtml = $widget->form('text', ['title' => '提成(%)', 'name' => 'rate', 'value' => '']);
            $this->assign('rateHtml', $rateHtml);
            //title
            $titleHtml = $widget->form('text', ['title' => 'title', 'name' => 'title', 'value' => '']);
            $this->assign('titleHtml', $titleHtml);
            //擅长领域
            $tagsHtml = $widget->form('text', ['title' => '擅长领域', 'name' => 'tags', 'value' => '','placeholder'=>'逗号分隔']);
            $this->assign('tagsHtml', $tagsHtml);
            //职称
            $infoHtml = $widget->form('textarea', ['title' => '职称', 'name' => 'info', 'value' => '']);
            $this->assign('infoHtml', $infoHtml);
            //一封信
            $descrHtml = $widget->form('textarea', ['title' => '一封信', 'name' => 'descr', 'value' => '']);
            $this->assign('descrHtml', $descrHtml);
            //详情
            $detailHtml = $widget->form('textarea', ['title' => '详情', 'name' => 'detail', 'value' => '']);
            $this->assign('detailHtml', $detailHtml);
            //培训经历
            $remarkHtml = $widget->form('textarea', ['title' => '培训经历', 'name' => 'remark', 'value' => '']);
            $this->assign('remarkHtml', $remarkHtml);




            return $this->fetch();
        }


    }
    /**
     * 编辑老师
     */
    public function edit($id)
    {
        if($this->request->isPost()){
            $id = $this->request->param('id');
            $old_price = Db::name('counselors')->where('id',$id)->value('price');
            $data = $this->request->param();
            $data['salary'] = $data['salary'] * 100;
            $data['rate'] = $data['rate'] * 100;
            $data['price'] = $data['price'] * 100;
            $rs = Db::name('counselors')->data($data)->update();
            //如果修改价格，则修改后面档期里面没有被预约的档期价格
            if($old_price != $data['price']){
                Db::name('schedules')->where('counselor_id',$id)->where('start_time','>',date('Y-m-d H:i:s'))->where('state','<',2)->setField('price',$data['price']);
            }
            if($rs){
                return $this->success('编辑成功',cookie('return_url'));
            }else{
                return $this->error('编辑失败');
            }
        }else {
            $this->assign('site_title', '编辑老师');
            $info = Db::name('counselors')->find($id);
            $this->assign('info',$info);
            //查询门店列表
            $store = Db::name('stores')->where('deleted',0)->select();
            foreach ($store as $key=>$item) {
                $l[$key]['name'] = $item['name'];
                $l[$key]['value'] = $item['id'];
            }
            $widget = WidgetLogic::getSingleton()->getWidget();
            $this->assign('widget', $widget);
            // 门店
            $selectHtml = $widget->form('select', [
                'title' => '门店',
                'name' => 'store_id',
                'value' => $info['store_id'],
                'list' => $l
            ]);
            $this->assign('select_html', $selectHtml);
            //照片
            $imageHtml = $widget->form('image', [
                'title' => '照片',
                'name' => 'avatar',
                'value' => $info['avatar']
            ]);
            $this->assign('imageHtml', $imageHtml);
            // 类型
            $typeHtml = $widget->form('select', [
                'title' => '类型',
                'name' => 'type',
                'value' => $info['type'],
                'list' => [
                    ['name'=>'认证','value'=>0],
                    ['name'=>'签约','value'=>1],
                    ['name'=>'共享','value'=>2],
                ]
            ]);
            $this->assign('typeHtml', $typeHtml);
            return $this->fetch();
        }
    }
    /**
     * 更改老师
     */
    public function modify()
    {
        $id = $this->request->param('id');
        if (empty($id)) {
            $this->error('ID为空');
        }

        $field = $this->request->param('field');
        if (empty($field)) {
            $this->error('字段名为空');
        }

        $value = $this->request->param('value');
        if (is_null($value)) {
            $this->error('值为空');
        }
        $rs = Db::name('counselors')->where('id', $id)
            ->update([$field => $value]);
        if($rs){
            return $this->success('操作成功');
        }else{
            return $this->error('操作失败');
        }
    }
    /**
     * 删除老师
     */
    public function delete()
    {
        $id = $this->request->param('id');
        if (empty($id)) {
            $this->error('ID为空');
        }
        $rs = Db::name('counselors')->where('id', $id)
            ->update(['deleted' => 1]);
        if($rs){
            return $this->success('删除成功','counselor/index');
        }else{
            return $this->error('删除失败');
        }
    }

    /**
     * 给老师添加15天日程
     * @param $counselor_id
     */
    private function addSchedule($counselor_id){
        $counselor = Db::name('counselors')->find($counselor_id);
        $today = date('Y-m-d');
        for ($i=0;$i<15;$i++){
            $date = date('Y-m-d',strtotime($today)+$i*86400);
            $data = ['counselor_id'=>$counselor_id,'store_id'=>$counselor['store_id'],'start_time'=>$date.' 09:00:00','end_time'=>$date.' 10:00:00','price'=>$counselor['price']];
            Db::name('schedules')->data($data)->insert();
            $data = ['counselor_id'=>$counselor_id,'store_id'=>$counselor['store_id'],'start_time'=>$date.' 10:00:00','end_time'=>$date.' 11:30:00','price'=>$counselor['price']];
            Db::name('schedules')->data($data)->insert();
            $data = ['counselor_id'=>$counselor_id,'store_id'=>$counselor['store_id'],'start_time'=>$date.' 13:00:00','end_time'=>$date.' 14:30:00','price'=>$counselor['price']];
            Db::name('schedules')->data($data)->insert();
            $data = ['counselor_id'=>$counselor_id,'store_id'=>$counselor['store_id'],'start_time'=>$date.' 15:00:00','end_time'=>$date.' 16:30:00','price'=>$counselor['price']];
            Db::name('schedules')->data($data)->insert();
            $data = ['counselor_id'=>$counselor_id,'store_id'=>$counselor['store_id'],'start_time'=>$date.' 17:00:00','end_time'=>$date.' 18:30:00','price'=>$counselor['price']];
            Db::name('schedules')->data($data)->insert();
            $data = ['counselor_id'=>$counselor_id,'store_id'=>$counselor['store_id'],'start_time'=>$date.' 19:00:00','end_time'=>$date.' 20:00:00','price'=>$counselor['price']];
            Db::name('schedules')->data($data)->insert();
            $data = ['counselor_id'=>$counselor_id,'store_id'=>$counselor['store_id'],'start_time'=>$date.' 20:00:00','end_time'=>$date.' 21:30:00','price'=>$counselor['price']];
            Db::name('schedules')->data($data)->insert();
        }
        return;
    }
}