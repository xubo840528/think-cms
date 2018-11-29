<?php /*a:1:{s:66:"D:\project\think-cms\application/store/view\index\check_start.html";i:1540366891;}*/ ?>
<li><label>当前咨询室待开始</label></li>
<li><label><i class="fa fa-user"></i> 咨询师：<?php echo htmlentities($counselor['name']); ?></label></li>
<li><label><i class="fa fa-user-circle" style="font-size: 12px"></i> 来访者：<?php echo htmlentities($order['name']); ?></label></li>
<li><input type="button" value="开始咨询" class="submitBtn" onclick="check_start('<?php echo htmlentities($order['id']); ?>')"/><input type="button" value="签出" class="submitBtn" onclick="check_out('<?php echo htmlentities($order['id']); ?>')"/></li>
