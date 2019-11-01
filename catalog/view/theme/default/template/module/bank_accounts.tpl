<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
	<?php if ($accounts) { ?>
	
		<br/>
		<?php foreach($accounts as $key => $value) { ?>
			<p style="text-align: center">
			<img src="/image/bank/<?php echo $value['image']; ?>" alt="<?php echo $value['title']; ?>"><br/>
			<?php echo $value['info']; ?>
			</p>
		<?php } ?>

		<?php } else { ?>
	
		<p style="text-align: center"><br/><?php echo $text_error; ?></p>
	
	<?php } ?>
	
  </div>
</div>
