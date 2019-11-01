<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <p><?php echo $text_heading; ?></p>
  <?php if ($articles) { ?>
  <article class="article">
	<ul class="article-list">
		<?php $i=0; foreach ($articles as $article) { $i++; ?>
		<?php if ($i%2==1) { $a='first'; } elseif ($i%2==0) { $a='last'; } else { $a=''; } ?>
		<li class="<?php echo $a?>">
        <div class="article-inner">
		<div class="meta"><div class="meta-time"><time><span><?php echo $article['date_added1']; ?></span><?php echo $article['date_added2']; ?></time></div>
		<div class="title"><a href="<?php echo $article['href']; ?>"><h3><?php echo $article['title']; ?></h3></a><span class="author"><i class="glyphicon glyphicon-user"></i><?php echo $text_by; ?> <b><?php echo $article['author']; ?></b></span></div></div>
		<?php if ($article['thumb']) { ?>
		<div class="image"><a href="<?php echo $article['href']; ?>"><img src="<?php echo $article['thumb']; ?>" alt="<?php echo $article['title']; ?>" /></a></div>
	  <?php } ?>
		<div class="description">
        <p><?php echo $article['description']; ?></p>
        <a class="button readmore" href="<?php echo $article['href']; ?>"><?php echo $button_readmore; ?></a>
        </div>
        </div>
	</li>
	<?php } ?>
</ul>
</article>
  <div class="pagination"><?php echo $pagination; ?></div>
  <?php } ?>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>
