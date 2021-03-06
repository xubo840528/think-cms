<?php /*a:1:{s:60:"D:\project\think-cms\application/store/view\index\rooms.html";i:1542938481;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlentities($store['name']); ?> - 芒果心理</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://apps.bdimg.com/libs/jquerymobile/1.4.5/jquery.mobile-1.4.5.min.css">
    <script src="https://apps.bdimg.com/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://apps.bdimg.com/libs/jquerymobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <link rel='stylesheet' id='font-awesome-css'  href='/static/font-awesome/4.7.0/css/font-awesome.min.css' type='text/css' media='all' />
    <style type="text/css">
        @media screen and (min-width: 1201px) {
            .ui-page {max-width: 1000px;margin: 0 auto;position: static !important;}
        }
    </style>
    <style>
        .room{display: inline-block;float:left;width: 30%;max-width:250px;min-width:150px;height: 300px;margin: 10px;border-radius:3px;font-weight: bold;text-align: center;background-color:
        #ffffff;}
        .room h2{color: #FB6F00;margin-top: 100px;}
        .room h3{color: #FB6F00;font-weight: normal;}

        a,button,input{-webkit-tap-highlight-color:rgba(255,0,0,0);}
        ul,p{padding:0;margin:0;}
        ul,li{list-style:none;}
        #dialogBg{width:100%;height:100%;background-color:#000000;opacity:.8;filter:alpha(opacity=60);position:fixed;top:0;left:0;z-index:9999;display:none;}
        #dialog{ width: 375px; height: 300px; margin: 0 auto;font-size: 12px; display: none; background-color: #ffffff; position: fixed; top: 40%; left: 40%; margin: -120px 0 0 -150px; z-index: 10000; border: 1px solid #ccc; border-radius: 10px; -webkit-border-radius: 10px; box-shadow: 3px 2px 4px rgba(0,0,0,0.2); -webkit-box-shadow: 3px 2px 4px rgba(0,0,0,0.2); }
        .closeDialogBtn{text-decoration: none;color: #2a6496;}
        .dialogTop{width:90%;margin:0 auto;border-bottom:1px dotted #ccc;letter-spacing:1px;padding:10px 0;overflow: hidden;}
        .dialogTop div{display: inline-block;float: left;font-size: 18px;font-weight: bold;margin-left: 30%;}
        .dialogTop a{float: right;display: inline-block;}
        .editInfos li{width:90%;margin:8px auto auto;}
        .editInfos li .radio{width: 15px;height: 15px;vertical-align: top;}
        .submitBtn{width:90px;height:30px;line-height:30px;border:0;margin-right:10px;font-family:"微软雅黑","microsoft yahei";cursor:pointer;margin-top:10px;display:inline-block;border-radius:5px;-webkit-border-radius:5px;text-align:center;background-color:#428bca;color:#fff;box-shadow: 0 -3px 0 #2a6496 inset;-webkit-box-shadow: 0 -3px 0 #2a6496 inset;}


    </style>
</head>
<body>
<div data-role="page" style="background-color: #FBC300;">
    <div data-role="header">
        <a href="javascript:;" onclick="location.href='<?php echo url('index/index'); ?>'" class="ui-btn ui-corner-all ui-shadow ui-icon-home ui-btn-icon-left">首页</a>
        <h1><?php echo htmlentities($store['name']); ?> - 芒果心理</h1>
        <a href="javascript:;" onclick="location.href='<?php echo url('login/logout'); ?>'" class="ui-btn ui-corner-all ui-shadow ui-icon-action ui-btn-icon-left">退出</a>
    </div>
    <div data-role="main" class="ui-content">
        <?php if(is_array($rooms) || $rooms instanceof \think\Collection || $rooms instanceof \think\Paginator): $i = 0; $__LIST__ = $rooms;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
        <div class="room" data-id="<?php echo htmlentities($val['id']); ?>" data-name="<?php echo htmlentities($val['name']); ?>" data-state="<?php echo htmlentities($val['status']); ?>">
            <h2><?php echo htmlentities($val['name']); ?></h2>
            <h3><?php echo htmlentities($val['counselor']); ?>&nbsp;</h3>
            <h3 style="color: #000000;"><?php echo htmlentities($val['status']); ?></h3>
        </div>
        <?php endforeach; endif; else: echo "" ;endif; ?>

    </div>
</div>
<div id="dialogBg"></div>
<div id="dialog" class="animated">
    <div class="dialogTop">
        <div id="room_name"></div>
        <a href="javascript:;" class="closeDialogBtn">关闭</a>
    </div>
    <form method="post" id="form">
        <input type="hidden" id="room_id" name="room_id" value="">
        <input type="hidden" id="order_id" name="order_id" value="">
        <ul class="editInfos" id="content">

        </ul>
    </form>
</div>
<script type="text/javascript">
    //签入房间
    function check_in(){
        $("#form").attr('action',"<?php echo url('index/checkIn'); ?>").submit();
    }
    //签出房间
    function check_out(order_id){
        $("#order_id").val(order_id);
        $("#form").attr('action',"<?php echo url('index/checkOut'); ?>").submit();
    }
    //咨询开始
    function check_start(order_id){
        $("#order_id").val(order_id);
        $("#form").attr('action',"<?php echo url('index/checkStart'); ?>").submit();
    }
    //咨询结束
    function check_end(order_id){
        $("#order_id").val(order_id);
        $("#form").attr('action',"<?php echo url('index/checkEnd'); ?>").submit();
    }
    //收款
    function check_pay(order_id){
        $("#order_id").val(order_id);
        $("#form").attr('action',"<?php echo url('index/checkPay'); ?>").submit();
    }
    $(function () {
        $("#form").submit(function () {
            var self = $(this);
            $.post(self.attr("action"), self.serialize(), success, "json");
            return false;

            function success(data) {
                if (data.code == 1) {
                    alert(data.msg);
                    window.location.reload();
                } else {
                    alert(data.msg);
                }
            }
        });
    })
</script>
<script type="text/javascript">
    var w,h;
    function getSrceenWH(){
        w = $(window).width();
        h = $(window).height();
        $('#dialogBg').width(w).height(h);
    }

    window.onresize = function(){
        getSrceenWH();
    };
    $(window).resize();

    $(function(){
        getSrceenWH();

        //显示弹框
        $('.room').click(function(){
            $('#dialogBg').fadeIn(300);
            $('#dialog').removeAttr('class').addClass('animated bounceIn').fadeIn();
            var id = $(this).attr('data-id');
            $("#room_id").val(id);
            $.ajax({
                type: 'post',
                url: "<?php echo url('room'); ?>",
                data: {id: id},
                dataType: "json",
                success: function (data) {
                    if(data.status==1){
                        $("#room_name").html(data.title);
                        $("#content").html(data.html);

                    }
                }
            });
        });

        //关闭弹窗
        $('.closeDialogBtn').click(function(){
            $('#dialogBg').fadeOut(300,function(){
                $('#dialog').addClass('bounceOutUp').fadeOut();
            });
        });
    });
</script>
</body>
</html>