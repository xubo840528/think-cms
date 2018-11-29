<?php /*a:1:{s:64:"D:\project\think-cms\application/store/view\index\check_pay.html";i:1540455115;}*/ ?>
<li><label>当前咨询已结束</label></li>
<!--<li><label><i class="fa fa-user"></i> 咨询师：<?php echo htmlentities($counselor['name']); ?></label></li>-->
<!--<li><label><i class="fa fa-user-circle" style="font-size: 12px"></i> 来访者：<?php echo htmlentities($order['name']); ?></label></li>-->
<li><label><i class="fa fa-calendar" style="font-size: 12px"></i> 咨询时间：<?php echo htmlentities(substr($order['start_time'],5,11)); ?> - <?php echo htmlentities(substr($order['end_time'],11,5)); ?></label></li>
<li><label><i class="fa fa-calculator" style="font-size: 12px"></i> 有效时长：<?php echo htmlentities($order['minutes']); ?>'</label></li>
<li><label><i class="fa fa-calculator" style="font-size: 12px"></i> 套餐抵扣：<?php echo htmlentities($order['deducted']); ?>'</label></li>
<li><label><i class="fa fa-calculator" style="font-size: 12px"></i> 结算时长：<?php echo htmlentities($order['minutes']-$order['deducted']); ?>'</label></li>
<li><label><i class="fa fa-jpy"></i>  咨询单价：￥<?php echo htmlentities($order['price']/100); ?>/小时</label></li>
<li><label><i class="fa fa-jpy"></i>  结算费用：￥<?php echo htmlentities($order['retainage']/100); ?></label></li>
<li><input type="text" name="tradeno" placeholder="请输入POS交易单号" style="margin-right: 10px;width: 60%;height: 30px;"><input type="button" value="立即收款" class="submitBtn" onclick="check_pay('<?php echo htmlentities($order['id']); ?>')"/></li>
