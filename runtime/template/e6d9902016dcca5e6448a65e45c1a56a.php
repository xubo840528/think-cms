<?php /*a:1:{s:66:"D:\project\think-cms\application/store/view\index\check_using.html";i:1540452885;}*/ ?>
<li><label>当前咨询室咨询中</label></li>
<li><label><i class="fa fa-user"></i> 咨询师：<?php echo htmlentities($counselor['name']); ?></label></li>
<li><label><i class="fa fa-user-circle" style="font-size: 12px"></i> 来访者：<?php echo htmlentities($order['name']); ?></label></li>
<li><label><i class="fa fa-calendar" style="font-size: 12px"></i> 开始时间：<?php echo htmlentities($order['start_time']); ?></label></li>
<li><label><i class="fa fa-calculator" style="font-size: 12px"></i> 已经开始：<?php echo htmlentities($order['latest_minutes']); ?>'</label></li>
<li><label><i class="fa fa-jpy"></i>  预计费用：￥<?php echo floor($order['price']/100/60*$order['latest_minutes']); ?></label></li>
<li><input type="button" value="结束咨询" class="submitBtn" onclick="check_end('<?php echo htmlentities($order['id']); ?>')"/><input type="button" value="签出" class="submitBtn" onclick="check_out('<?php echo htmlentities($order['id']); ?>')"/></li>
