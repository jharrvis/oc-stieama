<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
       <a href="http://www.highqualitywriting.com/?track=opencart" class="button" target="_blank">Get Articles from $1.90</a>
      </div>
    </div>
    <div class="content">
        
        <p>Browse the SEO tips below. Click on the title to view the full article:</p>
        
        <ul>
         <?php
		 foreach($tips as $tip) {
			 $title	= $tip[1];
			 $link	= $tip[2];
			 echo '<li><a href="http://www.highqualitywriting.com/articles/'.$link.'/?track=opencart" target="hqw">'.$title.'</a></li>';
		 }
		 ?>
        </ul>
        
    </div>
  </div>
  <p style="float:right; color:#666; font-size:11px; text-align:right;"><?php echo $text_author_credit;?></p>
</div>


<?php echo $footer; ?>