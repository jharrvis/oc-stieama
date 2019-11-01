<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/module.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><?php echo $entry_limit; ?></td>
          <td><input type="text" name="links_limit" value="<?php echo $links_limit; ?>" size="1" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_position; ?></td>
          <td><select name="links_position">
              <?php foreach ($positions as $position) { ?>
              <?php if ($links_position == $position['position']) { ?>
              <option value="<?php echo $position['position']; ?>" selected="selected"><?php echo $position['title']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $position['position']; ?>"><?php echo $position['title']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="links_status">
              <?php if ($links_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="links_sort_order" value="<?php echo $links_sort_order; ?>" size="1" /></td>
        </tr>
        
        <?php                                     
          for($i = 0; $i < $links_limit; $i++) { ?>
	<tr>

            <td><?php echo($entry_url.' '.($i + 1)); ?></td>
            <td> <input type="text" size="50" name="<?php echo ('links_url'.$i); ?>" value="<?php echo (isset($links_url[$i]) ? $links_url[$i]: '') ; ?>" id="<?php echo 'url'.$i; ?>" /> </td>
            <td><?php echo($entry_alt.' '.($i + 1)); ?></td>
            <td> <input type="text" size="50" name="<?php echo ('links_alt'.$i); ?>" value="<?php echo (isset($links_alt[$i]) ? $links_alt[$i]: '') ; ?>" id="<?php echo 'alt'.$i; ?>" /> </td>
          </tr>	
        <?php } ?>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.draggable.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.resizable.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.dialog.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/external/bgiframe/jquery.bgiframe.js"></script>

<?php echo $footer; ?>
