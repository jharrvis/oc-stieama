<?php
class ModelModuleArticles extends Model {
	private $arPatterns;
	private $arReplace;
	
	public function getArticle($article_id) {
		$sql = "SELECT DISTINCT *, UNIX_TIMESTAMP(a.date_added) AS date_added "
			 . "FROM ".DB_PREFIX."articles a "
			 . "LEFT JOIN " . DB_PREFIX . "articles_description ad ON (a.article_id = ad.article_id) "
			 . "LEFT JOIN " . DB_PREFIX . "articles_to_store a2s ON (a.article_id = a2s.article_id) "
			 . "WHERE a.article_id = '" . (int)$article_id . "' "
			 . "AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' "
			 . "AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "' "
			 . "AND a.status = '1'";
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	public function getAuthor($author_id) {
		$sql = "SELECT aa.author_id, aa.name, aa.googleid, aa.image, aad.short_bio, aad.long_bio "
			 . "FROM ".DB_PREFIX."articles_authors aa "
			 . "LEFT JOIN ".DB_PREFIX."articles_authors_description aad ON (aa.author_id = aad.author_id) "
			 . "WHERE aa.author_id = '" . (int)$author_id . "' "
			 . "AND aad.language_id = '" . (int)$this->config->get('config_language_id') . "' "
			 . "AND aa.status = '1' "
			 . "LIMIT 1";
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	public function getLinks($author_id) {
		$arLinks = array();
		if(is_numeric($author_id)) {
			$sql = "SELECT `link_id`, `name`, `href`, `new_window`, `nofollow` "
				 . "FROM ".DB_PREFIX."articles_author_links aal "
				 . "WHERE aal.author_id = '" . (int)$author_id . "' "
				 . "AND aal.status = '1'"
				 . "ORDER BY aal.sort_order;";
			$query = $this->db->query($sql);
			
			$arLinks = $query->rows;
		}
		return $arLinks;
	}
	
	public function getArticles($preferences=NULL, $random='0') {
		
		$dir = $this->getDir($preferences);
		
		//determin sort field/order
		$orderby = "a.sort_order ASC";//default
		if($random=='1') $orderby = "RAND()";
		elseif(isset($preferences['sort_by']) && $preferences['sort_by']=="RAND()") {
			$orderby = "RAND()";
		}
		elseif(isset($preferences['sort_by']) && isset($preferences['sort_dir'])) {
			$orderby = $preferences['sort_by']." ".$preferences['sort_dir'];
		}
		
		$sql = "SELECT a.article_id, UNIX_TIMESTAMP(a.date_added) AS date_added, ad.title "
			 . "FROM ".DB_PREFIX."articles a "
			 . "LEFT JOIN " . DB_PREFIX . "articles_description ad ON (a.article_id = ad.article_id) "
			 . "LEFT JOIN " . DB_PREFIX . "articles_to_store a2s ON (a.article_id = a2s.article_id) "
			 . "WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "' "
			 . "AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "' "
			 . "AND a.status = '1' "
			 . "ORDER BY ".$orderby;
		
		$query = $this->db->query($sql);
		
		$articles = $query->rows;
		foreach($articles as $key=>$article) {
			$articles[$key]['url'] = $this->url->link('articles/article', $dir.'article_id=' . $article['article_id']);
		}
		
		return $articles;
	}
	
	public function getDir($preferences=NULL) {
		//this should really be in /system/library/url.php, but I don't want to replace any more system files than need be
		$dir = '';
		if($this->config->get('config_seo_url') && isset($preferences['dir']) && $preferences['dir']!='') {
			//SEO URLs are enabled and article directory preferences is specified
			$dir = 'dir='.$preferences['dir'].'&';
		}
		return $dir;
	}
	
	public function setContextualLinks($article_id='', $dir='') {
		$this->arPatterns	= array();
		$this->arReplace	= array();
		
		//it is vital that the pattern/replacement arrays are stored in order of matching string length (longest to shortest).
		//This prevents shorter strings from being matched twice by longer matches (eg: "write" and "writer").
		$arSort = array();
		
		//get SEO article patterns/replacements
		$sql = "SELECT DISTINCT a.article_id, ad.title "
			 . "FROM ".DB_PREFIX."articles a "
			 . "LEFT JOIN ".DB_PREFIX."articles_description ad ON (a.article_id = ad.article_id) "
			 . "LEFT JOIN ".DB_PREFIX."articles_to_store a2s ON (a.article_id = a2s.article_id) "
			 . "WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "' "
			 . "AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "' "
			 . "AND a.status = '1' "
			 . "AND ad.title NOT LIKE '%|%' "
			 . "AND a.contextual=1 "
			 . "AND a.article_id IN (SELECT article_id FROM ".DB_PREFIX."articles WHERE contextual=1) "
			 . "AND a.article_id!='$article_id' ";
		$query = $this->db->query($sql);;
		foreach($query->rows as $row) {
			$arSort[] = array('length'	=> strlen($row['title']),
							  'pattern'	=> "|(\s)(".$row['title'].")|i",
							  'replace'	=> '\1<a href="'.$this->url->link('articles/article', $dir.'article_id=' . $row['article_id']).'">\2</a>');
		}
		
		//get SEO product patterns/replacements
		$sql = "SELECT DISTINCT p.product_id, pd.name "
			 . "FROM ".DB_PREFIX."product p "
			 . "LEFT JOIN ".DB_PREFIX."product_description pd ON (p.product_id = pd.product_id) "
			 . "LEFT JOIN ".DB_PREFIX."product_to_store p2s ON (p.product_id = p2s.product_id) "
			 . "WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' "
			 . "AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' "
			 . "AND p.status = '1' "
			 . "AND pd.name NOT LIKE '%|%' ";
		$query = $this->db->query($sql);;
		foreach($query->rows as $row) {
			$arSort[] = array('length'	=> strlen($row['name']),
							  'pattern'	=> "|(\s)(".$row['name'].")|i",
							  'replace'	=> '\1<a href="'.$this->url->link('product/product', 'product_id=' . $row['product_id']).'">\2</a>');
		}
		
		//SORT ALL BY STRING MATCH LENGTH
		//@usort($arSort, array("ModelModuleArticles", "sortRegEx"));
		@usort($arSort, array($this, "sortRegEx"));
		/*
		usort($arSort, function($a, $b) {
			return $b['length'] - $a['length'];
		});
		*/
		
		//build new pattern/replacement arrays in correct order
		foreach($arSort as $sort) {
			$this->arPatterns[]	= $sort['pattern'];
			$this->arReplace[]	= $sort['replace'];
		}
		
		//var_dump($arSort);
		//var_dump($this->arPatterns);
		//var_dump($this->arReplace);
	}
	private function sortRegEx($a, $b) {
		return $b['length'] - $a['length'];
	}
	
	public function addContextualLinks($text) {
		//return $text;
		return preg_replace($this->arPatterns, $this->arReplace, $text);
	}
}
?>