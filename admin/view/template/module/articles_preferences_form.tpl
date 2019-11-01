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
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
            <tr>
              <td><?php echo $entry_title; ?></td>
              <td>
               <input type="text" name="articles_preferences[title]" value="<?php echo $articles_preferences['title'];?>" />
			   <?php if (isset($error_title)) { ?>
               	 <span class="error"><?php echo $error_title;?></span>
               <?php } ?>
              </td>
            </tr>
            
            <tr>
              <td><?php echo $entry_dir; ?></td>
              <td>
               <input type="text" name="articles_preferences[dir]" value="<?php echo $articles_preferences['dir'];?>" onblur="this.value=string_to_url(this.value, true);" />
			   <?php if (isset($error_dir)) { ?>
               	 <span class="error"><?php echo $error_dir;?></span>
               <?php } ?>
              </td>
            </tr>
            
            <tr>
              <td><?php echo $entry_auto_contextual; ?></td>
              <td>
               <label><input type="radio" name="articles_preferences[contextual]" value="1"<?php if($articles_preferences['contextual']=='1') echo ' checked="checked"';?> /> Yes</label><br />
               <label><input type="radio" name="articles_preferences[contextual]" value="0"<?php if($articles_preferences['contextual']=='0') echo ' checked="checked"';?> /> No</label>
              </td>
            </tr>
            
            <tr>
              <td><?php echo $entry_img_pos; ?></td>
              <td>
               <label><input type="radio" name="articles_preferences[img_pos]" value="left"<?php if($articles_preferences['img_pos']=='left') echo ' checked="checked"';?> /> Left</label><br />
               <label><input type="radio" name="articles_preferences[img_pos]" value="right"<?php if($articles_preferences['img_pos']=='right') echo ' checked="checked"';?> /> Right</label>
              </td>
            </tr>
            
            <tr>
              <td><?php echo $entry_img_width; ?></td>
              <td><input type="input" name="articles_preferences[img_width]" value="<?php echo $articles_preferences['img_width'];?>" />
			   <?php if (isset($error_img_width)) { ?>
               	 <span class="error"><?php echo $error_img_width;?></span>
               <?php } ?>
              </td>
            </tr>
            
            <tr>
              <td><?php echo $entry_img_height; ?></td>
              <td>
               <input type="input" name="articles_preferences[img_height]" value="<?php echo $articles_preferences['img_height'];?>" />
			   <?php if (isset($error_img_height)) { ?>
               	 <span class="error"><?php echo $error_img_height;?></span>
               <?php } ?>
              </td>
            </tr>
            
            <tr>
              <td><?php echo $entry_sort_by; ?></td>
              <td>
               <select name="articles_preferences[sort_by]">
                <?php
				$arSort = array('a.sort_order'	=>'Sort Order',
								'ad.title'		=>'Title',
								'a.date_added'	=>'Date',
								'RAND()'		=>'Random');
				foreach($arSort as $key=>$value) {
					echo '<option value="'.$key.'"';
					if($articles_preferences['sort_by']==$key) echo ' selected="selected"';
                    echo '>'.$value.'</option>';
				}
				?>
               </select>
               
               <select name="articles_preferences[sort_dir]">
                <option value="ASC"<?php if($articles_preferences['sort_dir']=='ASC') echo ' selected="selected"';?>>Ascending</option>
                <option value="DESC"<?php if($articles_preferences['sort_dir']=='DESC') echo ' selected="selected"';?>>Descending</option>
               </select>
              </td>
            </tr>
            
        </table>
          
          
        <table id="module" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_layout; ?></td>
              <td class="left"><?php echo $entry_position; ?></td>
              <td class="left"><?php echo $entry_columns; ?></td>
              <td class="left"><?php echo $entry_random; ?></td>
              <td class="left"><?php echo $entry_status; ?></td>
              <td class="right"><?php echo $entry_sort_order; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $module_row = 0; ?>
          <?php foreach ($modules as $module) { ?>
          <tbody id="module-row<?php echo $module_row; ?>">
            <tr>
              <td class="left"><select name="articles_module[<?php echo $module_row; ?>][layout_id]">
                  <?php foreach ($layouts as $layout) { ?>
                  <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                  <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td class="left">
                <select name="articles_module[<?php echo $module_row; ?>][position]">
                  <?php if ($module['position'] == 'content_top') { ?>
                  	<option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                  <?php } else { ?>
                  	<option value="content_top"><?php echo $text_content_top; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'content_bottom') { ?>
                  	<option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                  <?php } else { ?>
                  	<option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'column_left') { ?>
                  	<option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                  <?php } else { ?>
                  	<option value="column_left"><?php echo $text_column_left; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'column_right') { ?>
                  	<option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                  <?php } else { ?>
                  	<option value="column_right"><?php echo $text_column_right; ?></option>
                  <?php } ?>
                </select>
              </td>
              <td class="left">
                <select name="articles_module[<?php echo $module_row; ?>][columns]">
                  <?php
				  for($col=1; $col<=6; $col++) {
					  echo '<option value="'.$col.'"';
					  if($module['columns']==$col) echo ' selected="selected"';
					  echo '>'.$col.'</option>';
				  }
				  ?>
                </select>
              </td>
              <td class="left">
                <select name="articles_module[<?php echo $module_row; ?>][random]">
                  <?php if ($module['random']) { ?>
                      <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                      <option value="0"><?php echo $text_no; ?></option>
                  <?php } else { ?>
                      <option value="1"><?php echo $text_yes; ?></option>
                      <option value="0" selected="selected"><?php echo $text_no; ?></option>
                  <?php } ?>
                </select>
              </td>
              <td class="left">
                <select name="articles_module[<?php echo $module_row; ?>][status]">
                  <?php if ($module['status']) { ?>
                      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                      <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                      <option value="1"><?php echo $text_enabled; ?></option>
                      <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </td>
              <td class="right"><input type="text" name="articles_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
              <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
            </tr>
          </tbody>
          <?php $module_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="6"></td>
              <td class="left"><a onclick="addModule();" class="button"><?php echo $button_add_module; ?></a></td>
            </tr>
          </tfoot>
        </table>
        
        <!-- leave hidden submit button to allow enter key to submit -->
        <input type="submit" style="display:none;" />
      </form>
    </div>
  </div>
  <p style="float:right; color:#666; font-size:11px; text-align:right;"><?php echo $text_author_credit;?></p>
</div>

<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	//layout
	html += '    <td class="left"><select name="articles_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
	<?php } ?>
	html += '    </select></td>';
	//position
	html += '    <td class="left"><select name="articles_module[' + module_row + '][position]">';
	html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    </select></td>';
	//columns
	html += '    <td class="left"><select name="articles_module[' + module_row + '][columns]">';
	html += '      <option value="1">1</option>';
	html += '      <option value="2">2</option>';
	html += '      <option value="3" selected="selected">3</option>';
	html += '      <option value="4">4</option>';
	html += '      <option value="5">5</option>';
	html += '      <option value="6">6</option>';
	html += '    </select></td>';
	//random
	html += '    <td class="left"><select name="articles_module[' + module_row + '][random]">';
    html += '      <option value="1"><?php echo $text_yes; ?></option>';
    html += '      <option value="0" selected="selected"><?php echo $text_no; ?></option>';
    html += '    </select></td>';
	//status
	html += '    <td class="left"><select name="articles_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	//sort
	html += '    <td class="right"><input type="text" name="articles_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	//buttons
	html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
}
//--></script> 

<?php echo $footer; ?>