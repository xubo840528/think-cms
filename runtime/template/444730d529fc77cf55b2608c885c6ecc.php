<?php /*a:2:{s:61:"D:\project\think-cms\application/manage/view\start\index.html";i:1542695105;s:61:"D:\project\think-cms\application/manage/view\common\base.html";i:1537337441;}*/ ?>
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
    .login-page {
        background-image: linear-gradient(180deg, rgba(255, 255, 255, 0) 60%, #fff), linear-gradient(70deg, #e0f1ff 32%, #fffae3);
    }

    .login-box {
        width: 450px;
        max-width: 90%;
        margin: 0 auto;
        padding-top: 50vh;
        transform: translateY(-25%);
    }

    .login-box .login-box-msg {
        margin: 15px;
        font-weight: bold;
        font-size: 40px;
    }

    .login-box .form-group {
        margin-bottom: 20px;
    }

    .login-box .form-group .form-control {
        height: 40px;
    }

    .login-box .form-group .form-control-feedback {
        height: 40px;
        line-height: 40px;
    }

    .login-box .verify-code-img {
        height: 40px;
    }

    .login-box .verify-code-text {
        font-size: 15px;
        padding-top: 12px;
        padding-right: 8px;
        cursor: pointer;
    }

    .login-box .row-space {
        width: 100%;
        height: 4px;
    }
</style>

</head>

<body class="hold-transition login-page">
<div class="login-box" id="login-box">

    <div class="login-box-body">
        <h3 class="login-box-msg">用户登录</h3>
        <form action="<?php echo htmlentities($login_url); ?>" method="post" class="ajax-form">
            <div class="form-group has-feedback">
                <input type="text" name="user_name" class="form-control" placeholder="用户名" v-model="user_name" required/>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="user_password" class="form-control" placeholder="密码"/>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <!--<div class="form-group has-feedback">-->
                <!--<input type="text" name="verify_code" class="form-control" placeholder="验证码" maxlength="4"/>-->
                <!--<span class="glyphicon glyphicon-eye-open form-control-feedback"></span>-->
            <!--</div>-->
            <!--<div class="form-group has-feedback">-->
                <!--<p>-->
                    <!--<img class="verify-code-img" :src="captcha_src"/>-->
                    <!--<a class="pull-right verify-code-text" @click="changeCaptchaSrc">看不清？换一张</a>-->
                <!--</p>-->
            <!--</div>-->
            <div class="row-space"></div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="pretty p-default">
                        <input type="checkbox" v-model="is_remember"/>
                        <div class="state p-primary">
                            <label>记住用户名</label>
                        </div>
                    </div>
                </div>
                <div class="col-xs-4">
                    <a class="btn btn-primary btn-block ajax-post" target-form=".ajax-form">登 录</a>
                </div>
            </div>
        </form>
    </div>

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
    var captcha_src = '<?php echo htmlentities($captcha_src); ?>';
    $(function () {
        require(['js-cookie'], function (Cookies) {
            new Vue({
                el: '#login-box',
                data: {
                    user_name: '',
                    is_remember: false,
                    captcha_src: ''
                },
                created: function () {
                    this.initData();
                    this.changeCaptchaSrc();
                },
                watch: {
                    user_name: function (value) {
                        if (value) {
                            Cookies.set('user_name', value, {expires: 30});
                        }
                        else {
                            Cookies.remove('user_name');
                        }
                    },
                    is_remember: function (value) {
                        if (value) {
                            Cookies.set('is_remember', value, {expires: 30});
                        }
                        else {
                            Cookies.remove('is_remember');
                        }
                    }
                },
                methods: {
                    initData: function () {
                        this.is_remember = Cookies.get('is_remember');
                        if (this.is_remember) {
                            this.user_name = Cookies.get('user_name');
                            this.user_name = this.user_name !== 'undefined' ? this.user_name : '';
                        }
                        else {
                            Cookies.remove('user_name');
                        }
                    },
                    changeCaptchaSrc: function () {
                        return this.captcha_src = captcha_src + '?_=' + Math.random();
                    }
                }
            });
        });
    });
</script>

</html>