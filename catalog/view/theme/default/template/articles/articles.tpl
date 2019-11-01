<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  
  <?php
  if(count($articles)>0) {
	  echo '<ul>';
	  foreach($articles as $article) {
		  ?>
          <li><a href="<?php echo $article['url'];?>"><?php echo $article['title'];?></a></li>
          <?php
	  }
	  echo '</ul>';
  }
  ?>
  
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>