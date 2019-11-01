<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
    <article class="article">
      <div class="entry-meta">
      <span class="meta-author"><label><?php echo $text_by; ?></label><b><?php echo $author; ?></b></span>
        <time><?php echo $date_added; ?></time>
      </div>
      <div class="article-large-image"><img src="<?php echo $image; ?>" alt="<?php echo $heading_title; ?>"></div>
      <div class="article-content"><?php echo $description; ?></div>
      <?php if ($filename) { ?>
      <div class="article-attachment"><div class="button"><a id="attachment-file" class="button"><?php echo $button_download; ?></a></div></div>
      <script type="text/javascript">$('a#attachment-file').click(function(e) {e.preventDefault(); window.location.href = '<?php echo $filename; ?>';});</script>
      <?php } ?>
      <div class="clear"></div>
		<div class="share">
			<!-- AddThis Button BEGIN -->
			<span class='st_facebook_hcount' displayText='Facebook'></span>
			<span class='st_twitter_hcount' displayText='Tweet'></span>
			<span class='st_googleplus_hcount' displayText='Google +'></span>
			<span class='st_pinterest_hcount' displayText='Pinterest'></span>
			<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
			<script type="text/javascript">stLight.options({publisher: "00fa5650-86c7-427f-b3c6-dfae37250d99", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
			<!-- AddThis Button END -->
		</div>
      <?php if ($prev || $next) { ?>
      <div class="clear"></div>
      <div class="article-nav">
        <?php if ($prev) { ?>
        <div class="article-prev"><b><?php echo $text_previous; ?></b>
          <a href="<?php echo $prev_url; ?>" rel="prev"><i></i><?php echo $prev; ?></a>
        </div>
        <?php } ?>
        <?php if ($next) { ?>
        <div class="article-next"><b><?php echo $text_next; ?></b>
          <a href="<?php echo $next_url; ?>" rel="next"><?php echo $next; ?><i></i></a>
        </div>
        <?php } ?>
      </div>
      <?php } ?>
      <div class="clear"></div>
      <?php if ($facebook_comment) { ?>
      <div id="fb-comment-box"><div class="fb-comments" data-href="<?php echo $current_url; ?>" data-width="763" data-numposts="5" data-colorscheme="light"></div>
      <div id="fb-root"></div>
					<script>
					(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) return;
					  js = d.createElement(s); js.id = id;
					  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=<?php echo $facebook_apikey; ?>";
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));
					$(window).bind("load resize", function(){    
					  var container_width = $('#fb-comment-box').width();    
					    $('#fb').html('<div class="fb-comments" ' + 
					    'data-href="<?php echo $current_url; ?>"' +
					    'data-width="' + container_width +
						'data-colorscheme="light"></div>');
					    FB.XFBML.parse();    
					});</script>
	  </div>
      <?php } ?>
    </article>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>
