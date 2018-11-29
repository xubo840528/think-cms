<?php /*a:1:{s:60:"D:\project\think-cms\application/store/view\order\index.html";i:1542938481;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>订单 - 芒果心理</title>
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
        .submitBtn{width:90px;height:30px;line-height:30px;border:0;margin-right:10px;font-size:12px;font-family:"微软雅黑","microsoft yahei";cursor:pointer;margin-top:10px;display:inline-block;border-radius:5px;-webkit-border-radius:5px;text-align:center;background-color:#428bca;color:#fff;box-shadow: 0 -3px 0 #2a6496 inset;-webkit-box-shadow: 0 -3px 0 #2a6496 inset;}

    </style>
</head>
<body>
<div data-role="page" style="background-color: #4AC3A1;">
    <div data-role="header">
        <a href="javascript:;" onclick="location.href='<?php echo url('index/index'); ?>'" class="ui-btn ui-corner-all ui-shadow ui-icon-home ui-btn-icon-left">首页</a>
        <h1>订单 - 芒果心理</h1>
        <a href="javascript:;" onclick="location.href='<?php echo url('login/logout'); ?>'" class="ui-btn ui-corner-all ui-shadow ui-icon-action ui-btn-icon-left">退出</a>
    </div>
    <div data-role="main" class="ui-content">
            <input type="date" name="start_time" id="start_time" value="<?php echo htmlentities($start_time); ?>">
            <input type="date" name="end_time" id="end_time" value="<?php echo htmlentities($end_time); ?>">

            <select name="counselor_id" id="counselor_id">
                <option value="">全部老师</option>
                <?php if(is_array($counselors) || $counselors instanceof \think\Collection || $counselors instanceof \think\Paginator): $i = 0; $__LIST__ = $counselors;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo htmlentities($val['id']); ?>" <?php if($val['id']==$counselor_id): ?>selected<?php endif; ?>><?php echo htmlentities($val['name']); ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <select name="state" id="state">
                <option value="">全部状态</option>
                <option value="-4" <?php if($state==-4): ?>selected<?php endif; ?>>未取消</option>
                <option value="0" <?php if($state===0): ?>selected<?php endif; ?>>待咨询</option>
                <option value="1" <?php if($state==1): ?>selected<?php endif; ?>>咨询中</option>
                <option value="2" <?php if($state==2): ?>selected<?php endif; ?>>待付款</option>
                <option value="3" <?php if($state==3): ?>selected<?php endif; ?>>等待客服介入</option>
                <option value="4" <?php if($state==4): ?>selected<?php endif; ?>>已取消</option>
                <option value="5" <?php if($state==5): ?>selected<?php endif; ?>>咨询结束</option>
            </select>
            <input type="text" name="mobile" id="mobile" value="<?php echo htmlentities($mobile); ?>" placeholder="输入客户手机">
            <input type="button" id="searchBtn" value="搜索" class="ui-btn ui-shadow ui-corner-all ui-icon-search ui-btn-icon-left">
        <p>订单列表</p>
        <?php if($list): ?>
        <table data-role="table" class="ui-responsive  ui-shadow" style="background-color: #ffffff;">
            <thead>
            <tr>
                <th>咨询师</th>
                <th>档期</th>
                <th>客户</th>
                <th>单价</th>
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
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
            <tr <?php if($val['state']==4): ?> style="background-color: #ccc"<?php elseif($val['state']==1): ?> style="background-color: #3ba084;color: #ffffff;"<?php endif; ?>>
                <td><?php echo htmlentities($val['counselor']); ?></td>
                <td><?php echo htmlentities(substr($val['start_time'],5,11)); ?></td>
                <td><?php echo htmlentities($val['customer']); ?></td>
                <td>￥<?php echo htmlentities($val['price']/100); ?></td>
                <td><?php echo $val['st']=='1970-01-01 08:00:01' ? '-- : --:--':substr($val['st'],10); ?></td>
                <td><?php echo $val['et']=='1970-01-01 08:00:01' ? '-- : --:--':substr($val['et'],10); ?></td>
                <td><?php echo htmlentities($val['minutes']); ?>'</td>
                <td>￥<?php echo htmlentities($val['receivable']/100); ?></td>
                <td><?php echo htmlentities($val['deducted']+$val['balance']); ?>’</td>
                <td><?php echo htmlentities($val['deducted']); ?>’</td>
                <td><?php echo htmlentities($val['balance']); ?>’</td>
                <td>￥<?php echo htmlentities($val['retainage']/100); ?></td>
                <td><?php echo htmlentities($order_state[$val['state']]); ?></td>
                <td><?php if(in_array(($val['state']), explode(',',"0,1"))): ?><a href="javascript:;" data-id="<?php echo htmlentities($val['id']); ?>" id="cancelOrder">取消</a><?php endif; ?> </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>目前没有订单</p>
        <?php endif; ?>
    </div>
</div>
<div id="dialogBg"></div>
<div id="dialog" class="animated">
    <div class="dialogTop">
        <div>取消订单</div>
        <a href="javascript:;" class="closeDialogBtn">关闭</a>
    </div>
    <form method="post" action="<?php echo url('order/cancel'); ?>" id="form">
        <input type="hidden" name="customer_id" value="<?php echo !empty($customer['id']) ? htmlentities($customer['id']) : ''; ?>">
        <div class="editInfos" id="content">
            <input type="hidden" name="order_id" id="order_id" value="">
            <div>
                <label style="float: left"><input type="radio" name="type" value="1"> 客户取消</label>
                <label style="float:left;"><input type="radio" name="type" value="2"> 老师取消</label>
            </div>
            <div style="clear: both">
                <input type="submit" value="确定取消" class="submitBtn"/>
            </div>

        </div>
    </form>
</div>
<script type="text/javascript">
    $(function () {
        $("#searchBtn").click(function () {
            var start_time = $.trim($("#start_time").val());
            var end_time = $.trim($("#end_time").val());
            var counselor_id = $.trim($("#counselor_id").val());
            var state = $.trim($("#state").val());
            var mobile = $.trim($("#mobile").val());
            location.href = "<?php echo url('order/index'); ?>"+"?start_time="+start_time+"&end_time="+end_time+"&counselor_id="+counselor_id+"&state="+state+"&mobile="+mobile;
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
        $('#cancelOrder').click(function(){
            $('#dialogBg').fadeIn(300);
            $('#dialog').removeAttr('class').addClass('animated bounceIn').fadeIn();
            $("#order_id").val($(this).attr('data-id'));
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