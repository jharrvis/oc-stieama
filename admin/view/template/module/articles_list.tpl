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

      <a href="<?php echo $url_tips;?>" class="button"><?php echo $button_tips;?></a>

      <a href="<?php echo $url_live;?>" class="button" target="_blank"><?php echo $button_live; ?></a>

      <a href="<?php echo $url_authors;?>" class="button"><?php echo $button_authors; ?></a>

      <a onclick="location = '<?php echo $url_preferences; ?>'" class="button"><?php echo $button_preferences; ?></a>

      <a onclick="location = '<?php echo $insert; ?>'" class="button"><?php echo $button_insert; ?></a>

      <a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>

    </div>

    <div class="content">

      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">

        

        <table class="list">

          <thead>

            <tr>

              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>

              <td class="left"><?php if($sort=='ad.title') { ?>

                	<a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_title; ?></a>

                <?php } else { ?>

                	<a href="<?php echo $sort_title; ?>"><?php echo $column_title; ?></a>

                <?php } ?></td>

              <td class="right"><?php if ($sort=='a.date_added') { ?>

                	<a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>

                <?php } else { ?>

                	<a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>

                <?php } ?></td>

              <td class="right"><?php if ($sort=='aa.name') { ?>

                	<a href="<?php echo $sort_author_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_author_name; ?></a>

                <?php } else { ?>

                	<a href="<?php echo $sort_author_name; ?>"><?php echo $column_author_name; ?></a>

                <?php } ?></td>

              <td class="center"><?php if ($sort=='a.image') { ?>

                	<a href="<?php echo $sort_image; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_image; ?></a>

                <?php } else { ?>

                	<a href="<?php echo $sort_image; ?>"><?php echo $column_image; ?></a>

                <?php } ?></td>

              <td class="left"><?php if ($sort=='a.contextual') { ?>

                	<a href="<?php echo $sort_contextual; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_contextual; ?></a>

                <?php } else { ?>

                	<a href="<?php echo $sort_contextual; ?>"><?php echo $column_contextual; ?></a>

                <?php } ?></td>

              <td class="right"><?php if ($sort=='a.sort_order') { ?>

                	<a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>

                <?php } else { ?>

                	<a href="<?php echo $sort_order; ?>"><?php echo $column_sort_order; ?></a>

                <?php } ?></td>

              <td class="left"><?php if ($sort=='a.status') { ?>

                	<a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>

                <?php } else { ?>

                	<a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>

                <?php } ?></td>

              <td class="right"><?php echo $column_action; ?></td>

            </tr>

          </thead>

          <tbody>

            <?php if ($articles) { ?>

				<?php foreach ($articles as $article) { ?>

                <tr>

                  <td style="text-align: center;"><?php if ($article['selected']) { ?>

                    <input type="checkbox" name="selected[]" value="<?php echo $article['article_id']; ?>" checked="checked" />

                    <?php } else { ?>

                    <input type="checkbox" name="selected[]" value="<?php echo $article['article_id']; ?>" />

                    <?php } ?></td>

                  <td class="left"><?php

                    if(isset($article['action'][0]['href']))	echo '<a href="'.$article['action'][0]['href'].'">'.$article['title'].'</a>';

                    else										echo $article['title'];

                  ?></td>

                  <td class="right"><?php echo $article['date_added']; ?></td>

                  <td class="right"><a href="<?php echo $article['author_href'];?>"><?php echo $article['author_name'];?></a></td>

                  <td class="center">

				   <?php if($article['image']!='') { ?>

				   	   <img src="<?php echo $article['image']?>" alt="" />

				   <?php } ?>

                  </td>

                  <td class="left"><?php echo $arYesNo[$article['contextual']]; ?></td>

                  <td class="right"><?php echo $article['sort_order']; ?></td>

                  <td class="left"><?php echo $arStatus[$article['status']]; ?></td>

                  <td class="right"><?php foreach ($article['action'] as $action) { ?>

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