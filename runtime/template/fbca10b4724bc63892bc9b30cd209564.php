<?php /*a:1:{s:61:"D:\project\think-cms\application/manage/view\common\jump.html";i:1537337441;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1"/>
    <meta name="renderer" content="webkit">
    <title><?php echo htmlentities($msg); ?></title>
    <link rel="stylesheet" href="<?php echo htmlentities($static_path); ?>/bootstrap/3.3.7/css/bootstrap.min.css?_=<?php echo htmlentities($site_version); ?>">
    <link rel="stylesheet" href="<?php echo htmlentities($static_path); ?>/font-awesome/4.7.0/css/font-awesome.min.css?_=<?php echo htmlentities($site_version); ?>">
    <link rel="stylesheet" href="<?php echo htmlentities($static_path); ?>/adminlte/2.4.2/css/AdminLTE.min.css?_=<?php echo htmlentities($site_version); ?>">
    <style type="text/css">
        body {
            padding: 20px;
            background-image: linear-gradient(180deg, rgba(255, 255, 255, 0) 60%, #fff), linear-gradient(70deg, #e0f1ff 32%, #fffae3)
        }

        .jump-content {
            width: 450px;
            max-width: 90%;
            margin-top: -2rem;
            margin-left: auto;
            margin-right: auto;
            padding-top: 50vh;
        }

        .jump-content .jump-area {
            width: 100%;
            padding: 2rem;
            background: #fff;
            text-align: center;
            border-radius: .5rem;
            transform: translateY(-50%)
        }

        .jump-content .jump-area .jump-icon {
            font-size: 100px;
            margin-top: 30px;
        }

        .jump-content .jump-area .jump-tip {
            font-size: 24px;
            margin-top: 15px;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .jump-content .jump-area .jump-link {
            font-size: 15px;
            margin-top: 15px;
            margin-bottom: 20px;
        }

    </style>
</head>
<body>
<div class="jump-content">
    <div class="jump-area">
        <p class="jump-icon">
            <?php if($code == 1): ?>
            <i class="fa fa-check-square text-green"></i>
            <?php else: ?>
            <i class="fa fa-times-circle text-red"></i>
            <?php endif; ?>
        </p>
        <p class="jump-tip" title=";">
            <?php echo htmlentities($msg); ?>
        </p>
        <p class="jump-link">
            <a href="javascript:jumpUrl();">
                <span class="jump-wait" id="jump-wait"><?php echo htmlentities($wait); ?></span> 秒后自动跳转链接
            </a>
        </p>
    </div>
</div>
<script>
    var wait = '<?php echo htmlentities($wait); ?>';
    var url = '<?php echo htmlentities($url); ?>';
    var waitSpan = document.getElementById('jump-wait');
    function countDown() {
        waitSpan.innerHTML = wait;
        wait--;
        if (wait <= 0) {
            setTimeout(jumpUrl, 990);
        } else {
            setTimeout(countDown, 990);
        }
    }
    function jumpUrl() {
        if (url) {
            location.href = url;
        } else {
            history.go(-1);
        }
    }
    countDown();
</script>
</body>
</html>