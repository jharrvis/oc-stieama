<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  
  <h1><?php echo $text_about.$author_name; ?></h1>
  
  <?php if($image!='') { ?>
  	<img id="image" src="<?php echo $image;?>" alt="<?php echo $author_name;?>" align="right" />
  <?php } ?>
  
  <?php echo $long_bio; ?>
  
  <div style="clear:both;"></div>
  
  <h4><?php echo $text_connect;?></h4>
  <ul>
   <?php if(is_numeric($googleid)) { ?>
       <li><a href="https://plus.google.com/<?php echo $googleid;?>" rel="me">Google+</a></li>
   <?php } ?>
   <?php
   foreach($links as $link) {
	   echo '<li><a href="'.$link['href'].'"';
	   if($link['new_window'])	echo ' target="_blank"';
	   if($link['nofollow'])	echo ' rel="nofollow"';
       echo '>'.$link['name'].'</a></li>';
   }
   ?>
  </ul>
  
  
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>