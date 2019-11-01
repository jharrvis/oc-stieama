<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  
  <h1><?php echo $heading_title; ?></h1>
  
  <?php if($image!='') { ?>
  	<img id="image" src="<?php echo $image;?>" alt="" align="<?php echo $img_pos;?>" />
  <?php } ?>
  
  <?php if(is_numeric($date_added)) { ?>
  	<h4 id="date"><?php echo date("l jS F Y",$date_added);?></h4>
  <?php } ?>
  
  <?php echo $description; ?>
  
  <div style="clear:both;"></div>
  
  
  <!-- author -->
  <?php if(isset($author_name)) { ?>
      <div class="box" id="author">
       <div class="box-heading">
	   	 <?php
		 echo $text_about;
		 if($author_bio_url!='')	echo '<a href="'.$author_bio_url.'" rel="author">'.$author_name.'</a>';
		 else						echo $author_name;
		 ?>
       </div>
       <div class="box-content">
		   <?php if($author_image!='') { ?>
               <div class="image">
               	  <?php
				  if($author_bio_url!='') echo '<a href="'.$author_bio_url.'" rel="author">';
                  echo '<img src="'.$author_image.'" alt="'.$author_name.'" />';
               	  if($author_bio_url!='') echo '</a>';
				  ?>
               </div>
           <?php } ?>
           <div class="description"<?php if($author_image=='') echo ' style="width:100%;"';?>>
              <div><?php echo $author_short_bio;?></div>
              <?php if(is_numeric($author_googleid) || count($links)>0) { ?>
                  <div class="profiles">
                   <span><?php echo $text_connect;?></span>
                   <ul>
                    <?php if(is_numeric($author_googleid)) { ?>
                        <li><a href="https://plus.google.com/<?php echo $author_googleid;?>?rel=author">Google+</a></li>
                    <?php } ?>
                    <?php foreach($links as $link) { ?>
                        <li><a href="<?php echo $link['href'];?>"><?php echo $link['name'];?></a></li>
                    <?php } ?>
                   </ul>
                  </div><!-- END .profiles -->
              <?php } ?>
           </div><!-- END .description -->
           <div style="clear:both;"></div>
       </div><!-- END .box-content -->
       <div class="bottom"></div>
      </div>
   <?php } ?>
  
  
  
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>