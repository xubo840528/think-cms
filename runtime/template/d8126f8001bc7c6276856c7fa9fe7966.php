<?php /*a:1:{s:62:"D:\project\think-cms\application/store/view\package\index.html";i:1542938481;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>套餐 - 芒果心理</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://apps.bdimg.com/libs/jquerymobile/1.4.5/jquery.mobile-1.4.5.min.css">
    <script src="https://apps.bdimg.com/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://apps.bdimg.com/libs/jquerymobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <style type="text/css">
        @media screen and (min-width: 1201px) {
            .ui-page {max-width: 1000px;margin: 0 auto;position: static !important;}
        }
    </style>
    <style>
        tr {border-bottom: 1px solid lightgray;}
        a,button,input{-webkit-tap-highlight-color:rgba(255,0,0,0);}
        /*ul,p{padding:0;margin:0;}*/
        ul,li{list-style:none;}
        #dialogBg{width:100%;height:100%;background-color:#000000;opacity:.8;filter:alpha(opacity=60);position:fixed;top:0;left:0;z-index:9999;display:none;}
        #dialog{ width: 375px; height: 300px; margin: 0 auto;display: none; background-color: #ffffff; position: fixed; top: 40%; left: 40%; margin: -120px 0 0 -150px; z-index: 10000; border: 1px solid #ccc; border-radius: 10px; -webkit-border-radius: 10px; box-shadow: 3px 2px 4px rgba(0,0,0,0.2); -webkit-box-shadow: 3px 2px 4px rgba(0,0,0,0.2); }
        .closeDialogBtn{text-decoration: none;color: #2a6496;}
        .dialogTop{width:90%;margin:0 auto;border-bottom:1px dotted #ccc;letter-spacing:1px;padding:10px 0;overflow: hidden;}
        .dialogTop div{display: inline-block;float: left;font-size: 18px;font-weight: bold;margin-left: 30%;}
        .dialogTop a{float: right;display: inline-block;}
        .editInfos{padding: 10px;}
        .editInfos li{margin:10px auto auto;}
        .editInfos li select{width: 100%;height: 30px;}
        .editInfos li .pos{width: 100%;height: 25px;}
        .submitBtn{width:90px;height:30px;line-height:30px;border:0;margin-right:10px;font-size:12px;font-family:"微软雅黑","microsoft yahei";cursor:pointer;margin-top:10px;display:inline-block;border-radius:5px;-webkit-border-radius:5px;text-align:center;background-color:#428bca;color:#fff;box-shadow: 0 -3px 0 #2a6496 inset;-webkit-box-shadow: 0 -3px 0 #2a6496 inset;}

    </style>
</head>
<body>
<div data-role="page" style="background-color: #F08115;">
    <div data-role="header">
        <a href="javascript:;" onclick="location.href='<?php echo url('index/index'); ?>'" class="ui-btn ui-corner-all ui-shadow ui-icon-home ui-btn-icon-left">首页</a>
        <h1>套餐 - 芒果心理</h1>
        <a href="javascript:;" onclick="location.href='<?php echo url('login/logout'); ?>'" class="ui-btn ui-corner-all ui-shadow ui-icon-action ui-btn-icon-left">退出</a>
    </div>
    <div data-role="main" class="ui-content">
        <input type="text" name="mobile" id="mobile" data-type="search" value="<?php echo htmlentities($mobile); ?>" placeholder="输入客户手机">
        <div class="ui-grid-a">
            <div class="ui-block-a">
                <a href="javascript:;" id="search" class="ui-btn  ui-shadow ui-corner-all  ui-icon-search ui-btn-icon-left">搜索</a>

            </div>
            <div class="ui-block-b">
                <a href="javascript:;" id="add" class="ui-btn  ui-shadow ui-corner-all  ui-icon-plus ui-btn-icon-left">添加套餐</a>

            </div>
        </div>
        <?php if($customer): ?>
        <table data-role="table" class="ui-responsive ui-shadow" style="background-color: #ffffff;">
            <thead>
            <tr>
                <th>ID</th>
                <th>昵称</th>
                <th>手机</th>
                <th>订单数</th>
                <th>添加时间</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?php echo htmlentities($customer['id']); ?></td>
                <td><?php echo htmlentities($customer['name']); ?></td>
                <td><?php echo htmlentities($customer['mobile']); ?></td>
                <td><?php echo htmlentities($customer['orders']); ?></td>
                <td><?php echo htmlentities($customer['inserted']); ?></td>
            </tr>
            </tbody>
        </table>
        <?php else: ?>
        <p>当前客户不存在</p>
        <?php endif; if($packages): ?>
        <p>套餐列表</p>
        <table data-role="table" class="ui-responsive  ui-shadow" style="background-color: #ffffff;">
            <thead>
            <tr>
                <th>咨询师</th>
                <th>套餐价格</th>
                <th>套餐时间</th>
                <th>剩余时间</th>
                <th>套餐金额</th>
                <th>备注</th>
                <th>时间</th>
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($packages) || $packages instanceof \think\Collection || $packages instanceof \think\Paginator): $i = 0; $__LIST__ = $packages;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
            <tr>
                <td><?php echo Db::name('counselors')->where('id',$val['counselor_id'])->value('name'); ?></td>
                <td>￥<?php echo htmlentities($val['price']/100); ?></td>
                <td><?php echo htmlentities($val['minutes']); ?>'</td>
                <td><?php echo htmlentities($val['balance']); ?>'</td>
                <td>￥<?php echo htmlentities($val['amount']/100); ?></td>
                <td><?php echo htmlentities($val['remark']); ?></td>
                <td><?php echo htmlentities($val['inserted']); ?></td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>目前没有套餐</p>
        <?php endif; ?>
    </div>
</div>
<div id="dialogBg"></div>
<div id="dialog" class="animated">
    <div class="dialogTop">
        <div>添加套餐</div>
        <a href="javascript:;" class="closeDialogBtn">关闭</a>
    </div>
    <form method="post" action="<?php echo url('package/add'); ?>" id="form">
        <input type="hidden" name="customer_id" value="<?php echo !empty($customer['id']) ? htmlentities($customer['id']) : ''; ?>">
        <ul class="editInfos" id="content">
            <li>
                <select name="counselor_id">
                    <option value="">选择咨询师套餐</option>
                    <?php if(is_array($counselors) || $counselors instanceof \think\Collection || $counselors instanceof \think\Paginator): $i = 0; $__LIST__ = $counselors;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
                    <option value="<?php echo htmlentities($val['id']); ?>"><?php echo htmlentities($val['name']); ?> 600分钟套餐:￥<?php echo htmlentities($val['price']*0.09); ?>(单价￥<?php echo htmlentities($val['price']/100); ?>/时)</option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </li>
            <li>
                <input type="text" name="tradeno" class="pos" placeholder="请输入POS交易单号">
            </li>
            <li>
                <select name="coupon">
                    <option value="0">优惠活动</option>
                    <option value="5000">￥50优惠券</option>
                    <option value="10000">￥100优惠券</option>
                </select>
            </li>
            <li>
                <input type="submit" value="保存套餐" class="submitBtn" />
            </li>

        </ul>
    </form>
</div>
<script type="text/javascript">
    $(function () {
        $("#search").click(function () {
            var mobile = $.trim($("#mobile").val());
            if(!mobile){
                alert('请输入客户手机号');
                return false;
            }
            location.href = "<?php echo url('package/index'); ?>"+"?mobile="+mobile;
        });
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
        $('#add').click(function(){
            $('#dialogBg').fadeIn(300);
            $('#dialog').removeAttr('class').addClass('animated bounceIn').fadeIn();
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