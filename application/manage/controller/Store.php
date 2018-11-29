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

class Store extends Base
{
    /**
     * 门店管理
     *
     * @return string
     */
    public function index()
    {
        $this->assign('site_title', '门店管理');
        $list = Db::name('stores')->order(['deleted'=>'asc','id'=>'asc'])->paginate(10)->each(function ($item,$key){
            $item['rooms'] = Db::name('rooms')->where(['store_id'=>$item['id'],'deleted'=>0])->count();
            if($item['type']==1){
                $item['type'] = '直营';
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

        return $this->fetch();


    }
    /**
     * 添加门店
     */
    public function add()
    {
        $data = [
            'type' => $this->request->param('type'),
            'name' => $this->request->param('name'),
            'city' => $this->request->param('city'),
            'address' => $this->request->param('address'),
            'phone' => $this->request->param('phone/s',''),
        ];
        $id = Db::name('stores')->insertGetId($data);
        if($id){
            //添加门店管理员
            $manager = [
                'store_id' => $id,
                'mobile' => $this->request->param('manager_mobile'),
                'password' => $this->request->param('manager_password'),
            ];
            Db::name('managers')->insert($manager);
            return $this->success('添加成功');
        }else{
            return $this->error('添加失败');
        }

    }
    /**
     * 编辑门店
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
//                $return = UserService::getSingleton()->getUser($id);
                $return = Db::name('stores')->find($id);
                $this->success('','',$return);
                break;
            case 'save':
                $data = [
                    'type' => $this->request->param('type'),
                    'name' => $this->request->param('name'),
                    'city' => $this->request->param('city'),
                    'address' => $this->request->param('address'),
                    'phone' => $this->request->param('phone')
                ];
                $return = Db::name('stores')->where('id',$id)->update($data);
                $this->success('编辑成功');
                break;
            default:
                $this->error('未知操作');
        }
    }
    /**
     * 更改门店
     */
    public function modify()
    {
        $id = $this->request->param('data_no');
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
        $rs = Db::name('stores')->where('id', $id)
            ->update([$field => $value]);
        if($rs){
            return $this->success('操作成功');
        }else{
            return $this->error('操作失败');
        }
    }
    /**
     * 删除门店
     */
    public function delete()
    {
        $id = $this->request->param('data_no');
        if (empty($id)) {
            $this->error('ID为空');
        }
        $rs = Db::name('stores')->where('id', $id)
            ->update(['deleted' => 1]);
        if($rs){
            return $this->success('删除成功');
        }else{
            return $this->error('删除失败');
        }
    }
    /*
     * 门店房间
     */
    public function room($store_id = 0){
        if (empty($store_id)) {
            $this->error('store_id为空');
        }

        $this->assign('site_title', '房间管理');
        $store = Db::name('stores')->find($store_id);
        $this->assign('store', $store);
        $this->assign('store_id', $store_id);
        $list = Db::name('rooms')->where('store_id',$store_id)->order(['deleted'=>'asc','id'=>'asc'])->paginate(10)->each(function ($item,$key){
            $item['store'] = Db::name('stores')->where('id',$item['store_id'])->value('name');
            if($item['type']==1){
                $item['type'] = '标准室';
            }elseif($item['type']==2){
                $item['type'] = '其他';
            }
            $widget = WidgetLogic::getSingleton()->getWidget();
            $modifyUrl = Url::build('roomModify');
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
            'add' => Url::build('roomAdd'),
            'edit' => Url::build('roomEdit'),
            'delete' => Url::build('roomDelete')
        ];
        $this->assign('action_list', $actionList);
        $this->assign('action_list_json', json_encode($actionList));

        return $this->fetch();
    }
    /*
     * 添加房间
     */
    public function roomAdd(){

        $data = [
            'store_id' => $this->request->param('store_id'),
            'type' => $this->request->param('type'),
            'name' => $this->request->param('name'),
            'counselor' => $this->request->param('counselor',''),
            'remark' => $this->request->param('remark',''),

        ];
        $rs = Db::name('rooms')->insert($data);
        if($rs){
            return $this->success('添加成功');
        }else{
            return $this->error('添加失败');
        }
    }
    /**
     * 编辑房间
     */
    public function roomEdit()
    {
        $id = $this->request->param('data_no');
        if (empty($id)) {
            $this->error('ID为空');
        }

        $action = $this->request->param('action');
        switch ($action) {
            case 'get':
//                $return = UserService::getSingleton()->getUser($id);
                $return = Db::name('rooms')->find($id);
                $this->success('','',$return);
                break;
            case 'save':
                $data = [
                    'type' => $this->request->param('type'),
                    'name' => $this->request->param('name'),
                    'counselor' => $this->request->param('counselor',''),
                    'remark' => $this->request->param('remark',''),
                ];
                $return = Db::name('rooms')->where('id',$id)->update($data);
                $this->success('编辑成功');
                break;
            default:
                $this->error('未知操作');
        }
    }
    /**
     * 删除门店
     */
    public function roomDelete()
    {
        $id = $this->request->param('data_no');
        if (empty($id)) {
            $this->error('ID为空');
        }
        $rs = Db::name('rooms')->where('id', $id)
            ->update(['deleted' => 1]);
        if($rs){
            return $this->success('删除成功');
        }else{
            return $this->error('删除失败');
        }
    }
}