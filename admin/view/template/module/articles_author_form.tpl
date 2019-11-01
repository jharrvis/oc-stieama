<?php
//print_r(get_defined_vars());
//die();
?>
<?php echo $header; ?>
<style type="text/css">
.txtLinkHref {width:400px;}
</style>
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
                    <td><span class="required">*</span> <?php echo $entry_author_short_bio; ?></td>
                    <td><textarea name="author_description[<?php echo $language['language_id']; ?>][short_bio]" id="short_bio<?php echo $language['language_id']; ?>"><?php echo isset($author_description[$language['language_id']]) ? $author_description[$language['language_id']]['short_bio'] : ''; ?></textarea>
                  </tr>
                  <tr>
                    <td><?php echo $entry_author_long_bio; ?></td>
                    <td><textarea name="author_description[<?php echo $language['language_id']; ?>][long_bio]" id="long_bio<?php echo $language['language_id']; ?>"><?php echo isset($author_description[$language['language_id']]) ? $author_description[$language['language_id']]['long_bio'] : ''; ?></textarea>
                  </tr>
                </table>
              </div>
          <?php } ?>
        </div>
        <div id="tab-data">
          <table class="form">
            
            <tr>
              <td><?php echo $entry_author_name; ?></td>
              <td>
               <input type="text" name="name" value="<?php echo $name;?>" maxlength="250" />
               <?php if (isset($error_author_name)) { ?>
                	<span class="error"><?php echo $error_author_name;?></span>
               <?php } ?>
              </td>
            </tr>
            
            <tr>
              <td><?php echo $entry_author_googleid; ?></td>
              <td>
			   <?php echo $text_author_google_url; ?><input type="number" name="googleid" value="<?php echo $googleid;?>" maxlength="25" size="25" />
               <?php if (isset($error_googleid)) { ?>
                	<span class="error"><?php echo $error_googleid;?></span>
               <?php } ?>
              </td>
            </tr>
            
            <tr>
              <td><?php echo $entry_author_photo; ?></td>
              <td><div class="image"><img src="<?php echo $thumb; ?>" alt="" id="thumb" /><br />
                  <input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
                  <a onclick="image_upload('image', 'thumb');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
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
          
          
          
          <h3>Connect to the author links<span class="help">eg. twitter, facebook, linkedin, email, etc. These links will appear beneath the biography text. Enter Google+ account above.</span></h3>
            <table id="module" class="list">
              <thead>
                <tr>
                  <td class="left"><?php echo $entry_link_name; ?></td>
                  <td class="left"><?php echo $entry_link_href; ?></td>
                  <td class="left"><?php echo $entry_new_window; ?></td>
                  <td class="left"><?php echo $entry_nofollow; ?></td>
                  <td class="left"><?php echo $entry_status; ?></td>
                  <td class="right"><?php echo $entry_sort_order; ?></td>
                  <td></td>
                </tr>
              </thead>
              <?php $link_row = 0; ?>
              <?php if(is_array($links)) { ?>
				  <?php foreach ($links as $link) { ?>
                      <tbody id="module-row<?php echo $link_row; ?>">
                        <tr>
                          <td class="left"><input type="text" name="author_links[<?php echo $link_row; ?>][name]" value="<?php echo $link['name'];?>" /></td>
                          <td class="left"><input type="text" name="author_links[<?php echo $link_row; ?>][href]" value="<?php echo $link['href'];?>" class="txtLinkHref" /></td>
                          <td class="left"><input type="checkbox" name="author_links[<?php echo $link_row; ?>][new_window]"<?php if($link['new_window']) echo ' checked="checked"';?> /></td>
                          <td class="left"><input type="checkbox" name="author_links[<?php echo $link_row; ?>][nofollow]"<?php if($link['nofollow']) echo ' checked="checked"';?> /></td>
                          <td class="left">
                            <select name="author_links[<?php echo $link_row; ?>][status]">
                              <?php if ($link['status']) { ?>
                                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                  <option value="0"><?php echo $text_disabled; ?></option>
                              <?php } else { ?>
                                  <option value="1"><?php echo $text_enabled; ?></option>
                                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                              <?php } ?>
                            </select>
                          </td>
                          <td class="right"><input type="text" name="author_links[<?php echo $link_row; ?>][sort_order]" value="<?php echo $link['sort_order']; ?>" size="3" /></td>
                          <td class="left"><a onclick="$('#module-row<?php echo $link_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
                        </tr>
                      </tbody>
                      <?php $link_row++; ?>
                  <?php } ?>
              <?php } ?>
              <tfoot>
                <tr>
                  <td colspan="6"></td>
                  <td class="left"><a onclick="addLink();" class="button"><?php echo $button_add_link; ?></a></td>
                </tr>
              </tfoot>
            </table>
          
          
          
          
        </div>
        
        <!-- leave hidden submit button to allow enter key to submit -->
        <input type="submit" style="display:none;" />
      </form>
    </div>
  </div>
  
  <p style="float:right; color:#666; font-size:11px; text-align:right;"><?php echo $text_author_credit;?></p>
</div>



<script type="text/javascript"><!--
var link_row = <?php echo $link_row; ?>;

function addLink() {
	html  = '<tbody id="module-row' + link_row + '">';
	html += '  <tr>';
	//name
	html += '    <td class="left"><input type="text" name="author_links[' + link_row + '][name]" value="" /></td>';
	//href
	html += '    <td class="left"><input type="text" name="author_links[' + link_row + '][href]" value="" class="txtLinkHref" /></td>';
	//new_window
	html += '    <td class="left"><input type="checkbox" name="author_links[' + link_row + '][new_window]" /></td>';
	//nofollow
	html += '    <td class="left"><input type="checkbox" name="author_links[' + link_row + '][nofollow]" /></td>';
	//status
	html += '    <td class="left"><select name="author_links[' + link_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	//sort
	html += '    <td class="right"><input type="text" name="author_links[' + link_row + '][sort_order]" value="" size="3" /></td>';
	//buttons
	html += '    <td class="left"><a onclick="$(\'#module-row' + link_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	link_row++;
}

$(function() {
	//validate link urls
	$("input.txtLinkHref").blur(function() {
		ValidateUrl($(this));
	});
});
function ValidateUrl(txt) {
	var url = txt.val();
	console.log('validate:'+url);
	//add http://
	if(url.substring(0,4)=='www.') {
		url = 'http://'+url;
	}
	else if(url.substring(0,4)!='http') {
		//link isn't a "http" URL
		//check if we need to add "mailto:"
		if(ValidateEmail(url) && url.substring(0,7)!='mailto:') {
			url = 'mailto:'+url;
		}
	}
	txt.val(url);
}
function ValidateEmail(elementValue){        
    var emailPattern = /^[a-zA-Z0-9._]+[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,4}$/;  
    return emailPattern.test(elementValue);   
}
//--></script> 

<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
	CKEDITOR.replace('short_bio<?php echo $language['language_id']; ?>', {
		filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
	});
	CKEDITOR.replace('long_bio<?php echo $language['language_id']; ?>', {
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