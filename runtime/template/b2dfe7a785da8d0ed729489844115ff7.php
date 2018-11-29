<?php /*a:1:{s:60:"D:\project\think-cms\application/store/view\login\login.html";i:1542938395;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>芒果门店登录</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://apps.bdimg.com/libs/jquerymobile/1.4.5/jquery.mobile-1.4.5.min.css">
    <script src="https://apps.bdimg.com/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://apps.bdimg.com/libs/jquerymobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <style type="text/css">
        @media screen and (min-width: 1201px) {
            .ui-page {max-width: 1000px;margin: 0 auto;position: static !important;}
        }
        .login-bg{background-color: #F08115;}
        .login-title{color: #ffffff;text-align: center;margin-top: 20%;}
    </style>
</head>
<body>

<div data-role="page" class="login-bg">
    <div data-role="main" class="ui-content">
        <form method="post" action="<?php echo url('login'); ?>" id="login_form">
            <h1 class="login-title" style="margin-bottom: 10%;">芒果心理门店系统</h1>
            <input type="text" name="login_name" id="login_name" placeholder="请输入用户名" style="text-align: center;">
            <input type="text" name="login_password" id="login_password" placeholder="请输入密码" style="text-align: center;">
            <button class="ui-btn" style="color: #F08115;font-weight: bold;">登 录</button>
        </form>
    </div>

</div>
<script type="text/javascript">
    $(function () {
        $("#login_form").submit(function () {
            var self = $(this);
            $.post(self.attr("action"), self.serialize(), success, "json");
            return false;

            function success(data) {
                if (data.code == 1) {
                    window.location.href = "<?php echo url('index/index'); ?>";
                } else {
                    alert(data.msg);
                }
            }
        });
    });
</script>
</body>
</html>