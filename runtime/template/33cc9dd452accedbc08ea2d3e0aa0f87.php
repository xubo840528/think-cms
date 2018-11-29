<?php /*a:1:{s:60:"D:\project\think-cms\application/store/view\index\index.html";i:1542938481;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlentities($store['name']); ?> - 芒果心理</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://apps.bdimg.com/libs/jquerymobile/1.4.5/jquery.mobile-1.4.5.min.css">
    <script src="https://apps.bdimg.com/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://apps.bdimg.com/libs/jquerymobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <style type="text/css">
        @media screen and (min-width: 1201px) {
            .ui-page {max-width: 1000px;margin: 0 auto;position: static !important;}
        }
    </style>
</head>
<body>
<div data-role="page">
    <div data-role="header">
        <a href="javascript:;" onclick="location.href='<?php echo url('index/index'); ?>'" class="ui-btn ui-corner-all ui-shadow ui-icon-home ui-btn-icon-left">首页</a>
        <h1><?php echo htmlentities($store['name']); ?> - 芒果心理</h1>
        <a href="javascript:;" onclick="location.href='<?php echo url('login/logout'); ?>'" class="ui-btn ui-corner-all ui-shadow ui-icon-action ui-btn-icon-left">退出</a>
    </div>
    <div data-role="main" class="ui-content" style="padding: 0;">
        <div class="ui-grid-solo">
            <div class="ui-block-a">
                <a href="javascript:;" onclick="location.href='<?php echo url('index/rooms'); ?>'" class="ui-btn" style="height: 280px;margin: 0;line-height: 280px;color: #fff;background-color: #FBC300;font-size: 28px;">房 间</a><br>
            </div>
        </div>

        <div class="ui-grid-a">
            <div class="ui-block-a">
                <a href="javascript:;" onclick="location.href='<?php echo url('order/index'); ?>'" class="ui-btn" style="height: 280px;margin: 0;line-height: 280px;color: #fff;background-color: #4AC3A1;font-size: 26px;">订 单</a><br>
            </div>

            <div class="ui-block-b">
                <a href="javascript:;" onclick="location.href='<?php echo url('package/index'); ?>'" class="ui-btn"style="height: 280px;margin: 0;line-height: 280px;color: #fff;background-color: #F08115;font-size: 26px;">套 餐</a><br>
            </div>
        </div>

    </div>
</div>
</div>
</body>
</html>