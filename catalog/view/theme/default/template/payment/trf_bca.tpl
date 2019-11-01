<h2><?php echo $text_instruction; ?></h2>
<p><?php echo $text_detail1; ?></p>
<p>
<?php echo $text_logo; ?><br/>
<b><?php echo $text_accountno; ?></b><?php echo $accountno; ?><br/>
<b><?php echo $text_accountname; ?></b><?php echo $accountname; ?>
</p>
<p><?php echo $text_detail2; ?></p>
<div class="buttons">
  <div class="right"><a id="button-confirm" class="button"><span><?php echo $button_confirm; ?></span></a></div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	$.ajax({ 
		type: 'GET',
		url: 'index.php?route=payment/trf_bca/confirm',
		success: function() {
			location = '<?php echo $continue; ?>';
		}		
	});
});
//--></script> 
