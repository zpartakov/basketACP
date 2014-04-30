<?php
if (!defined('WEB_ROOT')) {
	exit;
}
?>
<div class="footer">

   <p><?php echo decoder($shopConfig['name']); ?>
   <br><?php echo decoder($shopConfig['address']); ?><br>
    <?php echo decoder($shopConfig['phone']); ?><br>
    <a href="mailto:<?php echo $shopConfig['email']; ?>"><?php echo $shopConfig['email']; ?></a></p>

</div>
</div>
</body>
</html>
