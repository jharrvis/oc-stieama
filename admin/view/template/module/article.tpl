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
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
  </div>
  <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
          <table class="form">
            <tr>
              <td><?php echo $entry_auto_seo; ?></td>
              <td><?php if ($auto_seo) { ?>
                <input type="radio" name="auto_seo" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="auto_seo" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="auto_seo" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="auto_seo" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_in_menubar; ?></td>
              <td><?php if ($menubar) { ?>
                <input type="radio" name="menubar" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="menubar" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="menubar" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="menubar" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span><?php echo $entry_image_article; ?></td>
              <td><input type="text" name="image_article_width" value="<?php echo $image_article_width; ?>" size="3" />
                x
                <input type="text" name="image_article_height" value="<?php echo $image_article_height; ?>" size="3" />
                <?php if ($error_image_article) { ?>
                <span class="error"><?php echo $error_image_article; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span><?php echo $entry_image_related; ?></td>
              <td><input type="text" name="article_related_width" value="<?php echo $article_related_width; ?>" size="3" />
                x
                <input type="text" name="article_related_height" value="<?php echo $article_related_height; ?>" size="3" />
                <?php if ($error_article_related) { ?>
                <span class="error"><?php echo $error_article_related; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_facebook_comment; ?></td>
              <td><?php if ($facebook_comment) { ?>
                <input type="radio" name="facebook_comment" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="facebook_comment" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="facebook_comment" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="facebook_comment" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_facebook_apikey; ?></td>
              <td><input type="text" name="facebook_apikey" value="<?php echo $facebook_apikey; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_facebook_apisecret; ?></td>
              <td><input type="text" name="facebook_apisecret" value="<?php echo $facebook_apisecret; ?>" /></td>
            </tr>
          </table>
      </form>
    </div>
</div>
<?php echo $footer; ?>