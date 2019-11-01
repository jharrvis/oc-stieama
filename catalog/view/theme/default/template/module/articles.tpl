<?php if(is_array($articles)) { ?>
    <div class="box">
      <div class="box-heading"><?php echo $preferences['title']; ?></div>
      <div class="box-content">
        <?php
		if($position=='content_top' || $position=='content_bottom') {
			//horizontal layout - multiple column(s)
			$rows = ceil(count($articles)/$columns);
			//echo '<br />articles:'.count($articles);
			//echo '<br />columns:'.$columns;
			//echo '<br />rows:'.$rows;
			?>
            <table id="tblArticles" cellspacing="0" border="0">
             <tr>
              <td>
               <ul>
                <?php
			    foreach($articles as $key=>$article) {
					$mod = $key%$rows;
					echo '<li><a href="'.$article['url'].'">'.$article['title'].'</a></li>';
					//add column separator after each $rows rows, but not after the very last article
					if($mod==($rows-1) && $key<(count($articles)-1)) echo '</ul></td><td><ul>';
			    }
			    ?>
               </ul>
              </td>
             </tr>
            </table>
            <?php
		}
		else {
			//vertical layout - single column
			if(count($articles)>0) {
				echo '<ul>';
				foreach($articles as $article) {
					echo '<li><a href="'.$article['url'].'">'.$article['title'].'</a></li>';
				}
				echo '</ul>';
			}
		}
        ?>
        <div style="clear:left;"></div>
      </div>
      <div class="bottom">&nbsp;</div>
    </div>
<?php } ?>