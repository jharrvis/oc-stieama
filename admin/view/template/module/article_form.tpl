<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
       <a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
       <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
      </div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs">
       <a href="#tab-general"><?php echo $tab_general; ?></a>
       <a href="#tab-data"><?php echo $tab_data; ?></a>
      </div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
          <div id="languages" class="htabs">
            <?php foreach ($languages as $language) { ?>
            <a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
            <?php } ?>
          </div>
          <?php foreach ($languages as $language) { ?>
              <div id="language<?php echo $language['language_id']; ?>">
                <table class="form">
                  <tr>
                    <td><span class="required">*</span> <?php echo $entry_title; ?></td>
                    <td><input type="text" name="article_description[<?php echo $language['language_id']; ?>][title]" size="100" maxlength="250" value="<?php echo isset($article_description[$language['language_id']]) ? $article_description[$language['language_id']]['title'] : ''; ?>" />
                      <?php if (isset($error_title[$language['language_id']])) { ?>
                        <span class="error"><?php echo $error_title[$language['language_id']]; ?></span>
                      <?php } ?></td>
                  </tr>
                  <tr>
                    <td><?php echo $entry_description; ?></td>
                    <td><textarea name="article_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($article_description[$language['language_id']]) ? $article_description[$language['language_id']]['description'] : ''; ?></textarea>
                      <?php if (isset($error_description[$language['language_id']])) { ?>
                        <span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
                      <?php } ?></td>
                  </tr>
                </table>
              </div>
          <?php } ?>
        </div>
        <div id="tab-data">
          <table class="form">
            <tr>
              <td><?php echo $entry_store; ?></td>
              <td><div class="scrollbox">
                  <?php $class = 'even'; ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array(0, $article_store)) { ?>
                        <input type="checkbox" name="article_store[]" value="0" checked="checked" />
                        <?php echo $text_default; ?>
                    <?php } else { ?>
                        <input type="checkbox" name="article_store[]" value="0" />
                        <?php echo $text_default; ?>
                    <?php } ?>
                  </div>
                  <?php foreach ($stores as $store) { ?>
					  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                      <div class="<?php echo $class; ?>">
                        <?php if (in_array($store['store_id'], $article_store)) { ?>
                        	<input type="checkbox" name="article_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                        	<?php echo $store['name']; ?>
                        <?php } else { ?>
                        	<input type="checkbox" name="article_store[]" value="<?php echo $store['store_id']; ?>" />
                        	<?php echo $store['name']; ?>
                        <?php } ?>
                      </div>
                      <?php } ?>
                </div></td>
            </tr>
            
            <tr>
              <td><?php echo $entry_keyword; ?></td>
              <td><input type="text" name="keyword" value="<?php echo $keyword; ?>" style="width:200px;" onfocus="this.value=DefaultKeyword(this.value);" onblur="this.value=string_to_url(this.value);" /></td>
            </tr>

            <tr>
              <td><?php echo $entry_date_added; ?></td>
              <td>
                <input type="input" class="datepicker" id="date_added" name="date_added" value="<?php echo $date_added;?>" size="10" maxlength="10" placeholder="YYYY-MM-DD" />
                <span onclick="$('#date_added').val('');" style="cursor:pointer; text-decoration:underline;">clear</span>
                <?php if (isset($error_date_added)) { ?>
                	<span class="error"><?php echo $error_date_added;?></span>
                <?php } ?>
              </td>
            </tr>
            
            <tr>
              <td><?php echo $entry_author; ?></td>
              <td>
              	<select name="author_id">
                 <option value="">None</option>
                 <?php
				 foreach($authors as $author) {
					 echo '<option value="'.$author['author_id'].'"';
					 if($author['author_id']==$author_id) echo ' selected="selected"';
					 echo '>'.$author['name'].'</option>';
                 }
				 ?>
                </select>
                <a href="<?php echo $url_authors;?>" target="author"><?php echo $button_view_authors;?></a>
              </td>
            </tr>
            
            <tr>
              <td><?php echo $entry_image; ?></td>
              <td><div class="image"><img src="<?php echo $thumb; ?>" alt="" id="thumb" /><br />
                  <input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
                  <a onclick="image_upload('image', 'thumb');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
            </tr>
            
            <tr>
              <td><?php echo $entry_contextual; ?></td>
              <td>
               <label><input type="radio" name="contextual" value="1"<?php if($contextual=='1') echo ' checked="checked"';?> /> yes</label><br />
               <label><input type="radio" name="contextual" value="0"<?php if($contextual=='0') echo ' checked="checked"';?> /> no</label>
              </td>
            </tr>
            
            <tr>
              <td><?php echo $entry_sort_order; ?></td>
              <td><input type="text" name="sort_order" value="<?php echo $sort_order;?>" size="3" maxlength="5" /></td>
            </tr>
            
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="status">
                  <?php if ($status) { ?>
                      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                      <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                      <option value="1"><?php echo $text_enabled; ?></option>
                      <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
          </table>
        </div>
        
        <!-- leave hidden submit button to allow enter key to submit -->
        <input type="submit" style="display:none;" />
      </form>
    </div>
  </div>
  
  <p style="float:right; color:#666; font-size:11px; text-align:right;"><?php echo $text_author_credit;?></p>
</div>



<script type="text/javascript">
jQuery(function($){ //on document.ready
	$('.datepicker').datepicker({dateFormat:'yy-mm-dd'});
})

function DefaultKeyword(curValue) {
	if(curValue!='') {
		//no change
		return curValue;
	} else {
		//Still blank. Get the default keyword (alpha-numeric hyphenated characters from the main title description text)
		var title = $('input[name="article_description[1][title]"]').val();
		return string_to_url(title)
	}
}
</script>

<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('description<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>
//--></script> 

<script type="text/javascript"><!--
function image_upload(field, thumb) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(text) {
						$('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script> 

<script type="text/javascript"><!--
$('#tabs a').tabs(); 
$('#languages a').tabs(); 
//--></script> 
<?php echo $footer; ?>