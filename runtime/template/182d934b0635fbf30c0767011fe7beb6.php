<?php /*a:1:{s:63:"D:\project\think-cms\application/store/view\index\check_in.html";i:1540343449;}*/ ?>
<?php if($orders): if(is_array($orders) || $orders instanceof \think\Collection || $orders instanceof \think\Paginator): $i = 0; $__LIST__ = $orders;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
<li><label><input type="radio" name="order_id" value="<?php echo htmlentities($val['id']); ?>" class="radio"/><i class="fa fa-user"> <?php echo htmlentities($val['counselor']); ?></i> <i class="fa fa-calendar"> <?php echo htmlentities(substr($val['start_time'],11,5)); ?>-<?php echo htmlentities(substr($val['end_time'],11,5)); ?></i> <i class="fa fa-user-circle"> <?php echo htmlentities($val['customer']); ?></i></label></li>

<?php endforeach; endif; else: echo "" ;endif; ?>
<li><input type="button" value="签入" class="submitBtn" onclick="check_in()"/></li>
<?php else: ?>
<li><label>没有预约订单</label></li>
<?php endif; ?>

