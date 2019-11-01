<?php
class ModelModuleArticles extends Model {
	
	public function addArticle($data) {
		if($data['date_added']!='')	$date = "'".$data['date_added']."'";
		else						$date = "NULL";
		$sql = "INSERT INTO " . DB_PREFIX . "articles SET "
			 . "`date_added`=".$date.", "
			 . "`author_id`='" . (int)$data['author_id'] . "', "
			 . "`image`='".$this->db->escape($data['image'])."', "
			 . "`contextual`='" . (int)$data['contextual'] . "', "
			 . "`sort_order`='" . (int)$data['sort_order'] . "', "
			 . "`status`='".(int)$data['status']."'";
		$this->db->query($sql);
		
		$article_id = $this->db->getLastId(); 
		
		foreach ($data['article_description'] as $language_id => $value) {
			$sql = "INSERT INTO ".DB_PREFIX."articles_description SET "
				 . "article_id='".(int)$article_id."', "
				 . "language_id='".(int)$language_id."', "
				 . "title='".$this->db->escape($value['title'])."', "
				 . "description='".$this->db->escape($value['description'])."'";
			$this->db->query($sql);
		}
		
		if (isset($data['article_store'])) {
			foreach ($data['article_store'] as $store_id) {
				$sql = "INSERT INTO " . DB_PREFIX . "articles_to_store SET "
					 . "article_id='".(int)$article_id."', "
					 . "store_id='".(int)$store_id."'";
				$this->db->query($sql);
			}
		}
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'article_id=" . (int)$article_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->cache->delete('articles');
	}
	
	public function editArticle($article_id, $data) {
		if($data['date_added']!='')	$date = "'".$data['date_added']."'";
		else						$date = "NULL";
		$sql = "UPDATE ".DB_PREFIX."articles SET "
			 . "`date_added`=".$date.", "
			 . "`author_id`='" . (int)$data['author_id'] . "', "
			 . "`image`='".$this->db->escape($data['image'])."', "
			 . "`contextual`='" . (int)$data['contextual'] . "', "
			 . "`sort_order`='" . (int)$data['sort_order'] . "', "
			 . "`status`='".(int)$data['status']."'"
			 . "WHERE article_id = '".(int)$article_id."'";
		$this->db->query($sql);
		
		
		$this->db->query("DELETE FROM ".DB_PREFIX."articles_description WHERE article_id='".(int)$article_id."'");
					
		foreach ($data['article_description'] as $language_id => $value) {
			$sql = "INSERT INTO ".DB_PREFIX."articles_description SET "
				 . "article_id='".(int)$article_id."', "
				 . "language_id='".(int)$language_id."', "
				 . "title='".$this->db->escape($value['title'])."', "
				 . "description='".$this->db->escape($value['description'])."'";
			$this->db->query($sql);
		}
		
		
		$this->db->query("DELETE FROM ".DB_PREFIX."articles_to_store WHERE article_id = '".(int)$article_id."'");
		
		if (isset($data['article_store'])) {
			foreach ($data['article_store'] as $store_id) {
				$sql = "INSERT INTO " . DB_PREFIX . "articles_to_store SET "
					 . "article_id='".(int)$article_id."', "
					 . "store_id='".(int)$store_id."'";
				$this->db->query($sql);
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'article_id=" . (int)$article_id. "'");
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'article_id=" . (int)$article_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		
		$this->cache->delete('articles');
	}
	
	public function deleteArticle($article_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "articles WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "articles_description WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "articles_to_store WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'article_id=" . (int)$article_id . "'");
		
		$this->cache->delete('articles');
	}	
	
	public function getArticle($article_id) {
		$sql = "SELECT DISTINCT *, "
			 . "(SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'article_id=" . (int)$article_id . "') AS keyword "
			 . "FROM " . DB_PREFIX . "articles "
			 ."WHERE article_id = '" . (int)$article_id. "'";
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	public function getArticles($data = array()) {
		if ($data) {
			$sql = "SELECT a.article_id, ad.title, a.date_added, a.author_id, aa.name AS author_name, a.image, a.contextual, a.sort_order, a.status "
				 . "FROM " . DB_PREFIX . "articles a "
				 . "LEFT JOIN ".DB_PREFIX."articles_description ad ON (a.article_id = ad.article_id) "
				 . "LEFT JOIN ".DB_PREFIX."articles_authors aa ON (a.author_id = aa.author_id) "
				 . "WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
			$sort_data = array(
				'ad.title',
				'a.date_added',
				'aa.name',
				'a.image',
				'a.contextual',
				'a.sort_order',
				'a.status'
			);
			
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY ad.title";
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
		
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}		

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}	
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		} else {
			$articles_data = $this->cache->get('articles.' . (int)$this->config->get('config_language_id'));
			
			if (!$articles_data) {
				$sql = "SELECT * "
					 . "FROM " . DB_PREFIX . "articles a "
					 . "LEFT JOIN ".DB_PREFIX."articles_description ad ON (a.article_id = ad.article_id) "
					 . "WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "' "
					 . "ORDER BY ad.title";
				$query = $this->db->query($sql);
				
				$articles_data = $query->rows;
				
				$this->cache->set('articles.' . (int)$this->config->get('config_language_id'), $articles_data);
			}	
			
			return $articles_data;			
		}
	}
	
	public function getArticleDescriptions($article_id) {
		$article_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "articles_description WHERE article_id = '" . (int)$article_id . "'");

		foreach ($query->rows as $result) {
			$article_description_data[$result['language_id']] = array(
				'title'       => $result['title'],
				'description' => $result['description']
			);
		}
		
		return $article_description_data;
	}
	
	public function getArticleStores($article_id) {
		$article_store_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "articles_to_store WHERE article_id = '" . (int)$article_id . "'");

		foreach ($query->rows as $result) {
			$article_store_data[] = $result['store_id'];
		}
		
		return $article_store_data;
	}
	
	public function getTotalArticles() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "articles");
		
		return $query->row['total'];
	}	
	
	//authors
	public function addAuthor($data) {
		if($data['googleid']!='')	$googleid = "'" . $data['googleid'] . "'";
		else						$googleid = "NULL";
		$sql = "INSERT INTO " . DB_PREFIX . "articles_authors SET "
			 . "`name`='".$this->db->escape($data['name'])."', "
			 . "`googleid`=".$googleid.", "
			 . "`image`='".$this->db->escape($data['image'])."', "
			 . "`status`='".(int)$data['status']."'";
		$this->db->query($sql);
		
		$author_id = $this->db->getLastId(); 
		
		foreach ($data['author_description'] as $language_id => $value) {
			$sql = "INSERT INTO ".DB_PREFIX."articles_authors_description SET "
				 . "author_id='".(int)$author_id."', "
				 . "language_id='".(int)$language_id."', "
				 . "short_bio='".$this->db->escape($value['short_bio'])."', "
				 . "long_bio ='".$this->db->escape($value['long_bio'])."'";
			$this->db->query($sql);
		}
		
		$this->UpdateAuthorLinks($author_id, $data);
		
		$this->cache->delete('articles_authors');
	}
	
	public function editAuthor($author_id, $data) {
		if($data['googleid']!='')	$googleid = "'" . $data['googleid'] . "'";
		else						$googleid = "NULL";
		$sql = "UPDATE ".DB_PREFIX."articles_authors SET "
			 . "`name`='".$this->db->escape($data['name'])."', "
			 . "`googleid`=".$googleid.", "
			 . "`image`='".$this->db->escape($data['image'])."', "
			 . "`status`='".(int)$data['status']."'"
			 . "WHERE author_id = '".(int)$author_id."'";
		$this->db->query($sql);
		
		$this->db->query("DELETE FROM ".DB_PREFIX."articles_authors_description WHERE author_id='".(int)$author_id."'");
					
		foreach ($data['author_description'] as $language_id => $value) {
			$sql = "INSERT INTO ".DB_PREFIX."articles_authors_description SET "
				 . "author_id='".(int)$author_id."', "
				 . "language_id='".(int)$language_id."', "
				 . "short_bio='".$this->db->escape($value['short_bio'])."', "
				 . "long_bio ='".$this->db->escape($value['long_bio'])."'";
			$this->db->query($sql);
		}
		
		$this->UpdateAuthorLinks($author_id, $data);
		
		$this->cache->delete('articles_authors');
	}
	
	private function UpdateAuthorLinks($author_id, $data) {
		//clear any previous links
		$sql = "DELETE FROM `".DB_PREFIX."articles_author_links` WHERE author_id='".(int)$author_id."'";
		$this->db->query($sql);
		
		//add new links
		if(isset($data['author_links']) && is_array($data['author_links'])) {
			foreach($data['author_links'] as $link) {
				//check if checkboxes where checked
				if(isset($link['new_window']))	$new_window	= '1';
				else							$new_window	= '0';
				if(isset($link['nofollow']))	$nofollow	= '1';
				else							$nofollow	= '0';
				//build insert query
				$sql = "INSERT INTO `".DB_PREFIX."articles_author_links` SET "
					. "`author_id`='".(int)$author_id."', "
					. "`name`='".$this->db->escape($link['name'])."', "
					. "`href`='".$this->db->escape($link['href'])."', "
					. "`new_window`='".$new_window."', "
					. "`nofollow`='".$nofollow."', "
					. "`sort_order`='" . (int)$link['sort_order'] . "', "
					. "`status`='".(int)$link['status']."'";
				$this->db->query($sql);
			}
			//var_dump($data['author_links']);
		}
	}
	
	public function deleteAuthor($author_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "articles_authors WHERE author_id = '" . (int)$author_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "articles_authors_description WHERE author_id = '" . (int)$author_id . "'");
		
		$this->cache->delete('articles_authors');
	}	
	
	public function getAuthors($data = array()) {
		if ($data) {
			$sql = "SELECT *, (SELECT COUNT(link_id) FROM `".DB_PREFIX."articles_author_links` aal WHERE aal.author_id=aa.author_id) AS links "
				 . "FROM ".DB_PREFIX."articles_authors aa "
				 . "LEFT JOIN ".DB_PREFIX."articles_authors_description aad ON (aa.author_id = aad.author_id) "
				 . "WHERE aad.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
			$sort_data = array(
				'aa.name',
				'aa.googleid',
				'aa.image',
				'aa.status',
				'aad.short_bio',
				'links'
			);		
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY aa.name";
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
		
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}		

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		} else {
			$authors_data = $this->cache->get('articles_authors.' . (int)$this->config->get('config_language_id'));
			
			if (!$authors_data) {
				$sql = "SELECT *, (SELECT COUNT(link_id) FROM `".DB_PREFIX."articles_author_links` aal WHERE aal.author_id=aa.author_id) AS links "
					 . "FROM " . DB_PREFIX . "articles_authors aa "
					 . "LEFT JOIN ".DB_PREFIX."articles_authors_description aad ON (aa.author_id = aad.author_id) "
					 . "WHERE aad.language_id = '" . (int)$this->config->get('config_language_id') . "' "
					 . "ORDER BY aa.name";
				$query = $this->db->query($sql);
				
				$authors_data = $query->rows;
				
				$this->cache->set('articles_authors.' . (int)$this->config->get('config_language_id'), $authors_data);
			}	
			
			return $authors_data;			
		}
	}
	
	public function getAuthor($author_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "articles_authors WHERE author_id = '" . (int)$author_id. "'");
		$author = $query->row;
		
		//get author links
		$sql	= "SELECT DISTINCT * "
				. "FROM ".DB_PREFIX."articles_author_links "
				. "WHERE author_id = '" . (int)$author_id. "' "
				. "ORDER BY sort_order;";
		$query = $this->db->query($sql);
		$author['links'] = $query->rows;
		
		return $author;
	}
	
	public function getTotalAuthors() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "articles_authors");
		
		return $query->row['total'];
	}	
	
	public function getAuthorDescriptions($author_id) {
		$author_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "articles_authors_description WHERE author_id = '" . (int)$author_id . "'");

		foreach ($query->rows as $result) {
			$author_description_data[$result['language_id']] = array(
				'short_bio'	=> $result['short_bio'],
				'long_bio'	=> $result['long_bio']
			);
		}
		
		return $author_description_data;
	}
	
	//init
	public function checkArticles() {
		// - CREATE TABLES -
		
		//create articles table
		$sql = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."articles` ("
			 . "`article_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,"
			 . "`date_added` DATE NULL ,"
			 . "`author_id` INT NULL DEFAULT NULL ,"
			 . "`image` VARCHAR(255) NULL ,"
			 . "`contextual` BOOLEAN NOT NULL DEFAULT '1', "
			 . "`sort_order` INT NOT NULL DEFAULT '0', "
			 . "`status` TINYINT NOT NULL DEFAULT '1'"
			 . ") ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
		$this->db->query($sql);
		
		//create article descriptions table
		$sql = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."articles_description` ("
			 . "`article_id` INT NOT NULL ,"
			 . "`language_id` INT NOT NULL ,"
			 . "`title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,"
			 . "`description` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,"
			 . "PRIMARY KEY ( `article_id` , `language_id` )"
			 . ") ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
		$this->db->query($sql);
		
		//create articles stores table
		$sql = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."articles_to_store` ("
			 . "`article_id` int(11) NOT NULL,"
			 . "`store_id` int(11) NOT NULL,"
			 . "PRIMARY KEY (`article_id`,`store_id`)"
			 . ") ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
		$this->db->query($sql);
		
		//create authors table
		$sql = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."articles_authors` ("
			 . "`author_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,"
			 . "`name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,"
			 . "`googleid` VARCHAR( 32 ) NULL DEFAULT NULL ,"
			 . "`image` VARCHAR(255) NULL ,"
			 . "`status` TINYINT NOT NULL DEFAULT '1'"
			 . ") ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
		$this->db->query($sql);
		
		//create author descriptions table
		$sql = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."articles_authors_description` ("
			 . "`author_id` INT NOT NULL ,"
			 . "`language_id` INT NOT NULL ,"
			 . "`short_bio` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,"
			 . "`long_bio` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,"
			 . "PRIMARY KEY ( `author_id` , `language_id` )"
			 . ") ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
		$this->db->query($sql);
		
		//create author links table
		$sql = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."articles_author_links` ("
			 . "`link_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,"
			 . "`author_id` INT NOT NULL ,"
			 . "`name` VARCHAR( 50 ) NOT NULL ,"
			 . "`href` VARCHAR( 255 ) NOT NULL ,"
			 . "`new_window` BOOLEAN NOT NULL DEFAULT '0', "
			 . "`nofollow` BOOLEAN NOT NULL DEFAULT '0', "
			 . "`sort_order` INT NOT NULL DEFAULT '0',"
			 . "`status` TINYINT NOT NULL DEFAULT '1'"
			 . ") ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
		$this->db->query($sql);
		
		// - UPDATE -
		//make sure author_id foreign key exists in articles table (wasn't included in version 1)
		$sql = "SHOW COLUMNS FROM `".DB_PREFIX."articles`;";
		$query = $this->db->query($sql);
		//var_dump($query->rows);
		$blFieldFound = false;
		foreach($query->rows as $field) {
			if(isset($field['Field']) && $field['Field']=='author_id') {
				$blFieldFound = true;
				break;
			}
		}
		if(!$blFieldFound) {
			//author_id field hasn't been added/upgraded to the articles table
			$sql = "ALTER TABLE `".DB_PREFIX."articles` ADD `author_id` INT NULL DEFAULT NULL AFTER `date_added`;";
			$this->db->query($sql);
		}
		
		
		// - create LAYOUT records for the articles section -
		
		//check layout id
		$sql = "SELECT layout_id FROM `" . DB_PREFIX . "layout` WHERE `name` LIKE 'Articles' LIMIT 1;";
		$query = $this->db->query($sql);
		if($query->num_rows==0) {
			//articles layout has not been added yet
			$sql = "INSERT INTO `" . DB_PREFIX . "layout` SET `name`='Articles';";
			$this->db->query($sql);
		}
		
		//get stores
		$stores = array('0');//default store
		$sql = "SELECT `store_id` FROM `".DB_PREFIX."store;";
		$query = $this->db->query($sql);
		foreach($query->rows as $store) {
			$stores[] = $store['store_id'];
		}
		//var_dump($stores);
		
		//check layout route
		$newRoutes = array('articles/article');
		foreach($newRoutes as $newRoute) {
			foreach ($stores as $store_id) {
				$sql = "SELECT layout_id "
					 . "FROM `" . DB_PREFIX . "layout_route` "
					 . "WHERE `store_id`='".(int)$store_id."' "
					 . "AND `route` LIKE '".$newRoute."' "
					 . "LIMIT 1;";
				$query = $this->db->query($sql);
				if($query->num_rows==0) {
					//new layout route has not been added yet
					$sql = "INSERT INTO `".DB_PREFIX."layout_route` SET "
						 . "`layout_id`=(SELECT layout_id FROM `".DB_PREFIX."layout` WHERE `name` LIKE 'Articles' LIMIT 1), "
						 . "`store_id`='".(int)$store_id."', "
						 . "`route`='".$newRoute."'";
					$this->db->query($sql);
					//echo $sql.'<br />';
				}
			}
		}
	}
}
?>