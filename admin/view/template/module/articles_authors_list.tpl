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
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
      <a href="http://www.highqualitywriting.com/?track=opencart" class="button" target="_blank">Get Articles from $1.90</a>
      <a onclick="location = '<?php echo $insert; ?>'" class="button"><?php echo $button_insert; ?></a>
      <a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
    </div>
    <div class="content">
      
      <p>Don&rsquo;t forget to add your website&rsquo;s URL as a <a href="http://plus.google.com/me/about/edit/co" target="_blank">contributor in your Google+ account</a>. Read here for <a href="http://www.highqualitywriting.com/articles/authors-picture-in-search-results/" target="_blank">more information on authorship</a>.</p>
      
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if($sort=='aa.name') { ?>
                	<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_author_name; ?></a>
                <?php } else { ?>
                	<a href="<?php echo $sort_name; ?>"><?php echo $column_author_name; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort=='aa.googleid') { ?>
                	<a href="<?php echo $sort_googleid; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_author_googleid; ?></a>
                <?php } else { ?>
                	<a href="<?php echo $sort_googleid; ?>"><?php echo $column_author_googleid; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort=='links') { ?>
                	<a href="<?php echo $sort_links; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_author_links; ?></a>
                <?php } else { ?>
                	<a href="<?php echo $sort_links; ?>"><?php echo $column_author_links; ?></a>
                <?php } ?></td>
              <td class="center"><?php if ($sort=='aa.image') { ?>
                	<a href="<?php echo $sort_image; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_author_photo; ?></a>
                <?php } else { ?>
                	<a href="<?php echo $sort_image; ?>"><?php echo $column_author_photo; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort=='aad.short_bio') { ?>
                	<a href="<?php echo $sort_bio; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_author_bio; ?></a>
                <?php } else { ?>
                	<a href="<?php echo $sort_bio; ?>"><?php echo $column_author_bio; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort=='aa.status') { ?>
                	<a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                <?php } else { ?>
                	<a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($authors) { ?>
				<?php foreach ($authors as $author) { ?>
                <tr>
                  <td style="text-align: center;"><?php if ($author['selected']) { ?>
                    	<input type="checkbox" name="selected[]" value="<?php echo $author['author_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    	<input type="checkbox" name="selected[]" value="<?php echo $author['author_id']; ?>" />
                    <?php } ?></td>
                  <td class="left"><?php
                    if(isset($author['action'][0]['href']))	echo '<a href="'.$author['action'][0]['href'].'">'.$author['name'].'</a>';
                    else									echo $author['name'];
                  ?></td>
                  <td class="right"><?php echo $author['googleid']; ?></td>
                  <td class="right"><?php echo $author['links']; ?></td>
                  <td class="center">
				   <?php if($author['image']!='') { ?>
				   	   <img src="<?php echo $author['image']?>" alt="" />
				   <?php } ?>
                  </td>
                  <td class="left"><?php echo $author['short_bio']; ?></td>
                  <td class="left"><?php echo $arStatus[$author['status']]; ?></td>
                  <td class="right"><?php foreach ($author['action'] as $action) { ?>
                    [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                    <?php } ?></td>
                </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                  <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
                </tr>
            <?php } ?>
          </tbody>
        </table>
        <p style="text-align:center;"><strong>Get high quality articles written from as little as $1.90 at <a href="http://www.highqualitywriting.com/?track=opencart" target="_blank">highqualitywriting.com</a>.</strong></p>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
  <p style="float:right; color:#666; font-size:11px; text-align:right;"><?php echo $text_author_credit;?></p>
</div>


<?php echo $footer; ?>