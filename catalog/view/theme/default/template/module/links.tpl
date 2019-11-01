<div class="box">
  <div class="top"><?php echo $heading_title; ?></div>
  <div class="middle" id="information">
    <?php if (isset($url)) { ?>
	<ul>    
      <?php for($i = 0; isset($url[$i]); $i++) { ?>
		<li>
        <a href="<?php echo str_replace('&', '&amp;', $url[$i]); ?>" target="_blank">
		<?php echo $alt[$i]; ?></a>
      </li>
      <?php } ?>
    </ul>
    <?php } ?>
  </div>
  <div class="bottom">&nbsp;</div>
</div>
