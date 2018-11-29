<?php /*a:5:{s:61:"D:\project\think-cms\application/manage/view\order\index.html";i:1541128826;s:61:"D:\project\think-cms\application/manage/view\common\base.html";i:1537337441;s:63:"D:\project\think-cms\application/manage/view\common\header.html";i:1539743895;s:64:"D:\project\think-cms\application/manage/view\common\sidebar.html";i:1537337441;s:63:"D:\project\think-cms\application/manage/view\common\footer.html";i:1539743895;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo htmlentities($site_title); ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?php echo htmlentities($static_path); ?>/bootstrap/3.3.7/css/bootstrap.min.css?_=<?php echo htmlentities($site_version); ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo htmlentities($static_path); ?>/font-awesome/4.7.0/css/font-awesome.min.css?_=<?php echo htmlentities($site_version); ?>">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="<?php echo htmlentities($static_path); ?>/adminlte/2.4.2/css/AdminLTE.min.css?_=<?php echo htmlentities($site_version); ?>">
    <link rel="stylesheet" href="<?php echo htmlentities($static_path); ?>/adminlte/2.4.2/css/skins/skin-blue-light.min.css?_=<?php echo htmlentities($site_version); ?>">
    <!-- Pretty Checkbox -->
    <link rel="stylesheet" href="<?php echo htmlentities($static_path); ?>/pretty-checkbox/3.0.3/pretty-checkbox.min.css?_=<?php echo htmlentities($site_version); ?>">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="<?php echo htmlentities($static_path); ?>/sweetalert2/7.1.0/sweetalert2.min.css?_=<?php echo htmlentities($site_version); ?>">
    <!-- Jquery FancyTree -->
    <link rel="stylesheet" href="<?php echo htmlentities($static_path); ?>/jquery-fancytree/2.27.0/skin-win8-n/ui.fancytree.min.css?_=<?php echo htmlentities($site_version); ?>">
    <!-- Custom -->
    <link rel="stylesheet" href="<?php echo htmlentities($assets_path); ?>/manage/cms.css?_=<?php echo htmlentities($site_version); ?>">
    
<style type="text/css">
    .box-body>.table{color: #000000;}
    .box-body>.table tr{border-bottom: 6px solid #ffffff;}
</style>

</head>

<body class="hold-transition skin-blue-light sidebar-mini">
<div class="wrapper">
    
    <header class="main-header">
    <a class="logo">
        <span class="logo-mini"><b>X</b>B</span>
        <span class="logo-lg"><b>Mango</b>Cms</span>
    </a>

    <nav class="navbar navbar-static-top">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a id="dropdown-user-info" href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span>欢迎，<?php echo htmlentities($manage_user['user_nick']); ?></span>
                        <b><i class="fa fa-caret-down"></i></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="javascript:void(0);" data-toggle="modal" data-target="#modal-account">
                                <i class="ace-icon fa fa-cog"></i>
                                账号设置
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="<?php echo Url::build('manage/start/logout'); ?>">
                                <i class="ace-icon fa fa-power-off"></i>
                                安全退出
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

    </nav>
</header>

<div class="modal fade" id="modal-account">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">账号设置</h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo Url::build('manage/user/account'); ?>" method="post" class="account-form">
                    <div class="form-group">
                        <label>用户名</label>
                        <input type="text" class="form-control" value="<?php echo htmlentities($manage_user['user_name']); ?>" disabled/>
                    </div>
                    <div class="form-group">
                        <label>昵称</label>
                        <input type="text" class="form-control" name="user_nick" value="<?php echo htmlentities($manage_user['user_nick']); ?>"/>
                    </div>
                    <div class="form-group">
                        <label>密码</label>
                        <input type="password" class="form-control" name="user_password" placeholder="为空则不修改"/>
                    </div>
                    <div class="form-group">
                        <label>确认</label>
                        <input type="password" class="form-control" name="user_password_confirm" placeholder=""/>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-remove"></i> 取消</button>
                <button type="button" class="btn btn-primary ajax-post" target-form=".account-form"><i class="fa fa-save"></i> 保存</button>
            </div>
        </div>
    </div>
</div>
    

    
    <aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <?php function build_tree($menuTree){ if(is_array($menuTree) || $menuTree instanceof \think\Collection || $menuTree instanceof \think\Paginator): $i = 0; $__LIST__ = $menuTree;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if(isset($vo['children'])): ?>
            <li class="treeview <?php if($vo['menu_active']): ?>active<?php endif; ?>">
                <a href="javascript:void(0);">
                    <i class="<?php echo htmlentities($vo['menu_icon']); ?>"></i> <span><?php echo htmlentities($vo['menu_name']); ?></span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <?php echo build_tree($vo['children']); ?>
                </ul>
            </li>
            <?php else: ?>
            <li class="<?php if($vo['menu_active']): ?>active<?php endif; ?>">
                <a target="<?php echo htmlentities($vo['menu_target']); ?>" href="<?php echo htmlentities($vo['menu_link']); ?>">
                    <i class="<?php echo htmlentities($vo['menu_icon']); ?>"></i> <?php echo htmlentities($vo['menu_name']); ?>
                </a>
            </li>
            <?php endif; endforeach; endif; else: echo "" ;endif; } build_tree($menu_tree); ?>
        </ul>
    </section>
</aside>
    

    <div class="content-wrapper">
        <section class="content">
            
<div class="box">
    <div class="box-header with-border">
        <form class="nd-search-form" action="<?php echo htmlentities($action_list['search']); ?>">
            <div class="col-sm-4 nd-search-item">
                <!--<a class="btn btn-primary action-add" href="<?php echo url('add'); ?>"><i class="fa fa-plus"></i> 新增</a>-->
            </div>
            <div class="col-sm-2 nd-search-item">
                <?php echo $dateRangeHtml; ?>
            </div>
            <div class="col-sm-2 nd-search-item">
                <?php echo $counselorHtml; ?>
            </div>
            <div class="col-sm-2 nd-search-item">
                <?php echo $statusHtml; ?>
            </div>
            <div class="col-sm-2 nd-search-item">
                <?php echo $keyword_html; ?>
            </div>
        </form>
    </div>
    <div class="box-body table-responsive">
        <table id="menu-tree" class="table table-hover">
            <thead>
            <tr>
                <th>老师</th>
                <th>档期</th>
                <th>用户</th>
                <th>店名</th>
                <th>单价/H</th>
                <th>开始</th>
                <th>结束</th>
                <th>时长</th>
                <th>应收</th>
                <th>套餐</th>
                <th>抵扣</th>
                <th>剩余</th>
                <th>结算</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <tr <?php if($vo['state'] == 1): ?> class="bg-green" <?php endif; if($vo['state'] == 4): ?> class="bg-gray" <?php endif; ?>>
                <td><?php echo htmlentities($vo['counselor']); ?>(<?php echo htmlentities($vo['counselor_orders']); ?>)</td>
                <td><?php echo htmlentities(substr($vo['schedule'],5,8)); ?></td>
                <td><?php echo htmlentities($vo['name']); ?>(<?php echo htmlentities($vo['customer_orders']); ?>)</td>
                <td><?php echo htmlentities($vo['store']); ?></td>
                <td>￥<?php echo htmlentities($vo['price']/100); ?></td>
                <td><?php echo $vo['start_time']=='1970-01-01 08:00:01' ? '-- : --:--':substr($vo['start_time'],10); ?></td>
                <td><?php echo $vo['end_time']=='1970-01-01 08:00:01' ? '-- : --:--':substr($vo['end_time'],10); ?></td>
                <td><?php echo htmlentities($vo['minutes']); ?>’</td>
                <td>￥<?php echo htmlentities($vo['receivable']/100); ?></td>
                <td><?php echo htmlentities($vo['deducted']+$vo['balance']); ?>’</td>
                <td><?php echo htmlentities($vo['deducted']); ?>’</td>
                <td><?php echo htmlentities($vo['balance']); ?>’</td>
                <td>￥<?php echo htmlentities($vo['retainage']/100); ?></td>
                <td><?php echo $vo['status']; ?></td>
                <td>
                    <div class="nd-action" data-id="<?php echo htmlentities($vo['id']); ?>" data-title="订单">
                        <?php if($vo['state'] == 0||$vo['state'] == 1): ?>
                        <a class="text-danger action-edit"><i class="fa fa-trash-o"></i> 取消</a>
                        | <a class="text-danger action-edit_price"><i class="fa fa-jpy"></i> 改价</a>
                        <?php elseif($vo['state'] == 2): ?>
                        <a class="text-danger action-edit_receive"><i class="fa fa-jpy"></i> 收款</a>
                        <?php elseif($vo['state'] == 5): if(!$vo['amount']): ?>
                        <a class="text-danger action-invoice"><i class="fa fa-ticket"></i> 开票</a>
                        <?php endif; endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($page): ?>
    <div class="box-footer">
        <?php echo $page; ?>
    </div>
    <?php endif; ?>
</div>
<div class="modal fade" id="modal-edit"></div>
<script type="text/x-template" id="modal-edit-template">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">选择类型</h4>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <input type="hidden" name="id" v-model="form.id"/>
                    <div class="form-group">
                        <label>老师</label>
                        <input type="text" name="counselor" v-model="form.counselor" class="form-control" disabled/>
                    </div>
                    <div class="form-group">
                        <label>客户</label>
                        <input type="text" name="name" v-model="form.name" class="form-control" disabled/>
                    </div>
                    <div class="form-group">
                        <label>类型</label>
                        <div>
                            <div class="pretty p-default p-round p-smooth">
                                <input type="radio" v-model="form.type" name="type" value="1"/>
                                <div class="state p-primary">
                                    <label>客户取消</label>
                                </div>
                            </div>
                            <div class="pretty p-default p-round p-smooth">
                                <input type="radio" v-model="form.type" name="type" value="2"/>
                                <div class="state p-primary">
                                    <label>老师取消</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i
                        class="fa fa-remove"></i> 取消
                </button>
                <button type="button" class="btn btn-primary" @click="saveRecord"><i class="fa fa-save"></i> 确定</button>
            </div>
        </div>
    </div>
</script>
<div class="modal fade" id="modal-edit-price"></div>
<script type="text/x-template" id="modal-edit-price-template">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">填写修改价格</h4>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <input type="hidden" name="id" v-model="form.id"/>
                    <div class="form-group">
                        <label>老师</label>
                        <input type="text" name="counselor" v-model="form.counselor" class="form-control" disabled/>
                    </div>
                    <div class="form-group">
                        <label>客户</label>
                        <input type="text" name="name" v-model="form.name" class="form-control" disabled/>
                    </div>
                    <div class="form-group">
                        <label>原价</label>
                        <input type="text" name="price" v-model="form.price" class="form-control" disabled/>
                    </div>
                    <div class="form-group">
                        <label>修改价</label>
                        <input type="text" name="new_price" v-model="form.new_price" class="form-control"/>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i
                        class="fa fa-remove"></i> 取消
                </button>
                <button type="button" class="btn btn-primary" @click="saveRecord"><i class="fa fa-save"></i> 确定</button>
            </div>
        </div>
    </div>
</script>
<div class="modal fade" id="modal-edit-receive"></div>
<script type="text/x-template" id="modal-edit-receive-template">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">订单收款</h4>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <input type="hidden" name="id" v-model="form.id"/>
                    <div class="form-group">
                        <label>咨询时间</label>
                        <p></p>
                        <input type="text" name="consult_time" v-model="form.consult_time" class="form-control" disabled/>
                    </div>
                    <div class="form-group">
                        <label>有效时长(分钟)</label>
                        <input type="text" name="minutes" v-model="form.minutes" class="form-control" disabled/>
                    </div>
                    <div class="form-group">
                        <label>套餐抵扣(分钟)</label>
                        <input type="text" name="deducted" v-model="form.deducted" class="form-control" disabled/>
                    </div>
                    <div class="form-group">
                        <label>结算时长(分钟)</label>
                        <input type="text" name="settle_minutes" v-model="form.settle_minutes" class="form-control" disabled/>
                    </div>
                    <div class="form-group">
                        <label>咨询单价(元/小时)</label>
                        <input type="text" name="price" v-model="form.price" class="form-control" disabled/>
                    </div>
                    <div class="form-group">
                        <label>结算费用(元)</label>
                        <input type="text" name="retainage" v-model="form.retainage" class="form-control" disabled/>
                    </div>
                    <div class="form-group">
                        <label>POS交易单号</label>
                        <input type="text" name="tradeno" v-model="form.tradeno" placeholder="请填写POS交易单号" class="form-control"/>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i
                        class="fa fa-remove"></i> 取消
                </button>
                <button type="button" class="btn btn-primary" @click="saveRecord"><i class="fa fa-save"></i> 确定</button>
            </div>
        </div>
    </div>
</script>

        </section>
    </div>

    
    <footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 1.0.0
    </div>

    Copyright 2018 By Mango
</footer>
    
</div>
</body>

<!-- jQuery -->
<script src="<?php echo htmlentities($static_path); ?>/jquery/3.2.1/jquery.min.js?_=<?php echo htmlentities($site_version); ?>"></script>
<!-- Bootstrap -->
<script src="<?php echo htmlentities($static_path); ?>/bootstrap/3.3.7/js/bootstrap.min.js?_=<?php echo htmlentities($site_version); ?>"></script>
<!-- SlimScroll -->
<script src="<?php echo htmlentities($static_path); ?>/jquery-slimscroll/1.3.8/jquery.slimscroll.min.js?_=<?php echo htmlentities($site_version); ?>"></script>
<!-- FastClick -->
<script src="<?php echo htmlentities($static_path); ?>/fastclick/1.0.6/fastclick.js?_=<?php echo htmlentities($site_version); ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo htmlentities($static_path); ?>/adminlte/2.4.2/js/adminlte.min.js?_=<?php echo htmlentities($site_version); ?>"></script>
<!-- SweetAlert2 -->
<script src="<?php echo htmlentities($static_path); ?>/sweetalert2/7.1.0/sweetalert2.min.js?_=<?php echo htmlentities($site_version); ?>"></script>
<!-- Jquery FancyTree -->
<script src="<?php echo htmlentities($static_path); ?>/jquery-ui/1.12.1/jquery-ui.min.js?_=<?php echo htmlentities($site_version); ?>"></script>
<script src="<?php echo htmlentities($static_path); ?>/jquery-fancytree/2.27.0/jquery.fancytree-all.min.js?_=<?php echo htmlentities($site_version); ?>"></script>
<!-- Vue -->
<script src="<?php echo htmlentities($static_path); ?>/vue/2.5.13/vue.min.js?_=<?php echo htmlentities($site_version); ?>"></script>
<!-- Require Js -->
<script src="<?php echo htmlentities($static_path); ?>/require-js/2.3.5/require.min.js?_=<?php echo htmlentities($site_version); ?>"></script>
<!-- Custom -->
<script src="<?php echo htmlentities($assets_path); ?>/manage/cms.js?_=<?php echo htmlentities($site_version); ?>"></script>
<script>
    CMS.site = {
        'version': '<?php echo htmlentities($site_version); ?>',
        'static_path': '<?php echo htmlentities($static_path); ?>',
        'assets_path': '<?php echo htmlentities($assets_path); ?>'
    };
    CMS.api = {
        'upload': '<?php echo url("manage/upload/upload"); ?>',
        'upload_delete': '<?php echo url("manage/upload/delete"); ?>'
    };
    require.config({
        'baseUrl': CMS.site.static_path,
        'urlArgs': '_=' + CMS.site.version,
        'packages': [
            {
                'name': 'codemirror',
                'location': 'codemirror/5.33.0',
                'main': 'lib/codemirror'
            },
            {
                'name': 'moment',
                'location': 'moment/2.20.1',
                'main': 'moment'
            },
            {
                'name': 'beautify-html',
                'location': 'js-beautify/1.7.5',
                'main': 'beautify-html'
            }
        ],
        'paths': {
            'jquery': 'jquery/3.2.1/jquery.min',
            'jquery-loading-overlay': 'jquery-loading-overlay/1.5.4/loadingoverlay.min',
            'jquery-select2': 'select2/4.0.4/js/select2.full.min',
            'jquery-input-mask': 'jquery-inputmask/3.3.11/jquery.inputmask.bundle.min',
            'jquery-base64': 'jquery-base64/jquery.base64.min',

            'spark-md5': 'spark-md5/3.0.0/spark-md5.min',
            'js-cookie': 'js-cookie/2.2.0/js.cookie.min',

            'bootstrap-date-picker': 'bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.zh-CN.min',
            'bootstrap-date-range-picker': 'bootstrap-daterangepicker/2.1.27/daterangepicker.min',
            'bootstrap-color-picker': 'bootstrap-colorpicker/2.5.1/js/bootstrap-colorpicker.min',
            'bootstrap-tag-input': 'bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min',
            'bootstrap-file-input': ['bootstrap-fileinput/4.4.7/themes/explorer-fa/theme.min'],
            'bootstrap-file-input-lang': ['bootstrap-fileinput/4.4.7/js/zh'],

            'jsonlint': 'json-lint/1.6.0/jsonlint',
            'htmlhint': 'html-hint/0.9.12/htmlhint',
            'code-mirror': 'blank/code-mirror',
            'code-html': 'blank/code-html',
            'code-json': 'blank/code-json',
            'summer-note': 'summernote/0.8.9/summernote-zh-CN',
            'um-editor': 'umeditor/1.2.3/umeditor.config'
        },
        'shim': {
            'jquery-select2': [
                'css!select2/4.0.4/css/select2.min'
            ],
            'bootstrap-date-picker': [
                'css!bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min',
                'bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min'
            ],
            'bootstrap-date-range-picker': [
                'css!bootstrap-daterangepicker/2.1.27/daterangepicker.min',
                'moment/moment.min',
                'moment/zh-cn'
            ],
            'bootstrap-color-picker': [
                'css!bootstrap-colorpicker/2.5.1/css/bootstrap-colorpicker.min'
            ],
            'bootstrap-tag-input': [
                'css!bootstrap-tagsinput/0.8.0/bootstrap-tagsinput'
            ],
            'bootstrap-file-input': [
                'css!bootstrap-fileinput/4.4.7/css/fileinput',
                'css!bootstrap-fileinput/4.4.7/themes/explorer-fa/theme.min',
                'bootstrap-fileinput/4.4.7/js/fileinput'
            ],
            'code-mirror': [
                'css!codemirror/lib/codemirror',
                'css!codemirror/theme/eclipse.css',
                'css!codemirror/addon/lint/lint',
                'codemirror/addon/lint/lint'
            ],
            'code-html': [
                'code-mirror',
                'codemirror/mode/htmlmixed/htmlmixed',
                'codemirror/addon/lint/javascript-lint',
                'codemirror/addon/lint/html-lint'
            ],
            'code-json': [
                'code-mirror',
                'jsonlint',
                'codemirror/addon/lint/json-lint'
            ],
            'summer-note': [
                'css!summernote/0.8.9/summernote',
                'code-html',
                'summernote/0.8.9/summernote',
            ],
            'um-editor': [
                'css!umeditor/1.2.3/themes/default/css/umeditor',
                'umeditor/1.2.3/umeditor.min'
            ]
        },
        'map': {
            '*': {
                'css': 'require-css/0.1.10/css.min'
            }
        },
        'waitSeconds': 10
    });
    window.UMEDITOR_HOME_URL = CMS.site.static_path + '/umeditor/1.2.3/';
</script>

<script>
    var actionList = JSON.parse('<?php echo $action_list_json; ?>');


    $(function () {

        $(document).on('click', '.action-add', function () {
            CMS.app.recordAdd(actionList.add, {
                deleted: 0
            });
        });

        $(document).on('click', '.action-edit', function () {
            var id = $(this).parent().attr('data-id');
            CMS.app.recordEdit(actionList.edit, id);
        });
        $(document).on('click', '.action-edit_price', function () {
            var id = $(this).parent().attr('data-id');
            CMS.app.recordEdit(actionList.edit_price, id,'#modal-edit-price');
        });
        $(document).on('click', '.action-edit_receive', function () {
            var id = $(this).parent().attr('data-id');
            CMS.app.recordEdit(actionList.edit_receive, id,'#modal-edit-receive');
        });
        $(document).on('click', '.action-delete', function () {
            var id = $(this).parent().attr('data-id'),
                dataTitle = $(this).parent().attr('data-title');
            CMS.app.recordDelete(actionList.delete, id, dataTitle);
        });
        $(".action-invoice").click(function () {
            if(confirm('是否确认开票？')){
                var id = $(this).parent().attr('data-id');
                $.ajax({
                    type: 'post',
                    url: "<?php echo url('ajaxInvoice'); ?>",
                    data: {id: id},
                    dataType: "json",
                    success: function (data) {
                        if(data.status==1){
                            alert('开票成功');
                            location.href = location.href;
                        }
                    }
                });
            }
        })
    });
</script>

</html>