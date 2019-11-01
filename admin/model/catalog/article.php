<?php
class ModelCatalogArticle extends Model {
	public function addArticle($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "article SET sort_order = '" . (int)$data['sort_order'] . "', top = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', bottom = '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "', filename = '" . $this->db->escape($data['filename']) . "', mask = '" . $this->db->escape($data['mask']) . "', author = '" . $this->db->escape($data['author']) . "', date_available = '" . $this->db->escape($data['date_available']) . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");

		$article_id = $this->db->getLastId(); 
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "article SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE article_id = '" . (int)$article_id . "'");
		}
		
		foreach ($data['article_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "article_description SET article_id = '" . (int)$article_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', subject = '" . $this->db->escape($value['subject']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "'");
		}
		
		// Article Store
		if (isset($data['article_store'])) {
			foreach ($data['article_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "article_to_store SET article_id = '" . (int)$article_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		// Article Layout
		if (isset($data['article_layout'])) {
			foreach ($data['article_layout'] as $store_id => $layout) {
				if ($layout) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "article_to_layout SET article_id = '" . (int)$article_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}
				
		// Article Related
		if (isset($data['article_related'])) {
			foreach ($data['article_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "article_related WHERE article_id = '" . (int)$article_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "article_related SET article_id = '" . (int)$article_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "article_related WHERE article_id = '" . (int)$related_id . "' AND related_id = '" . (int)$article_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "article_related SET article_id = '" . (int)$related_id . "', related_id = '" . (int)$article_id . "'");
			}
		}

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'article_id=" . (int)$article_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->cache->delete('article');
	}
	
	public function editArticle($article_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "article SET sort_order = '" . (int)$data['sort_order'] . "', top = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', bottom = '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "', filename = '" . $this->db->escape($data['filename']) . "', mask = '" . $this->db->escape($data['mask']) . "', author = '" . $this->db->escape($data['author']) . "', date_available = '" . $this->db->escape($data['date_available']) . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE article_id = '" . (int)$article_id . "'");
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "article SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE article_id = '" . (int)$article_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "article_description WHERE article_id = '" . (int)$article_id . "'");
					
		foreach ($data['article_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "article_description SET article_id = '" . (int)$article_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', subject = '" . $this->db->escape($value['subject']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "article_to_store WHERE article_id = '" . (int)$article_id . "'");
		
		if (isset($data['article_store'])) {
			foreach ($data['article_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "article_to_store SET article_id = '" . (int)$article_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		
		// Article Related
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_related WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_related WHERE related_id = '" . (int)$article_id . "'");

		if (isset($data['article_related'])) {
			foreach ($data['article_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "article_related WHERE article_id = '" . (int)$article_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "article_related SET article_id = '" . (int)$article_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "article_related WHERE article_id = '" . (int)$related_id . "' AND related_id = '" . (int)$article_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "article_related SET article_id = '" . (int)$related_id . "', related_id = '" . (int)$article_id . "'");
			}
		}
		
		// Article Layout
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_to_layout WHERE article_id = '" . (int)$article_id . "'");

		if (isset($data['article_layout'])) {
			foreach ($data['article_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "article_to_layout SET article_id = '" . (int)$article_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}
				
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'article_id=" . (int)$article_id. "'");
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'article_id=" . (int)$article_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->cache->delete('article');
	}
	
	public function copyArticle($article_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "article a LEFT JOIN " . DB_PREFIX . "article_description ad ON (a.article_id = ad.article_id) WHERE a.article_id = '" . (int)$article_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		if ($query->num_rows) {
			$data = array();
			
			$data = $query->row;
			
			$data['viewed'] = '0';
			$data['keyword'] = '';
			$data['status'] = '0';
						
			$data = array_merge($data, array('article_description' => $this->getArticleDescriptions($article_id)));			
			$data = array_merge($data, array('article_related' => $this->getArticleRelated($article_id)));
			$data = array_merge($data, array('article_layout' => $this->getArticleLayouts($article_id)));
			$data = array_merge($data, array('article_store' => $this->getArticleStores($article_id)));
			$this->addArticle($data);
		}
	}
	
	public function deleteArticle($article_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "article WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_description WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_related WHERE article_id = '" . (int) $article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_related WHERE related_id = '" . (int) $article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_to_store WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_to_layout WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'article_id=" . (int)$article_id . "'");

		$this->cache->delete('article');
	}	

	public function getArticle($article_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'article_id=" . (int)$article_id . "') AS keyword FROM " . DB_PREFIX . "article WHERE article_id = '" . (int)$article_id . "'");
		
		return $query->row;
	}

	public function getArticles($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "article a LEFT JOIN " . DB_PREFIX . "article_description ad ON (a.article_id = ad.article_id)";

			$sql .= " WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'"; 

			$implode = array();

			if (isset($data['filter_title']) && !is_null($data['filter_title'])) {
				$implode[] = "ad.title = '" . (int)$data['filter_title'] . "'";
			}

			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$implode[] = "a.status = '" . (int)$data['filter_status'] . "'";
			}

			if (!empty($data['filter_date_added'])) {
				$implode[] = "DATE(a.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
			}

			if (!empty($data['filter_date_modified'])) {
				$implode[] = "DATE(a.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
			}

			if ($implode) {
				$sql .= " AND " . implode(" AND ", $implode);
			}

			$sort_data = array(
				'ad.title',
				'a.status',
				'a.date_added',
				'a.date_modified',
				'a.sort_order'
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
			$article_data = $this->cache->get('article.' . (int)$this->config->get('config_language_id'));

			if (!$article_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article a LEFT JOIN " . DB_PREFIX . "article_description ad ON (a.article_id = ad.article_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ad.title");

				$article_data = $query->rows;

				$this->cache->set('article.' . (int)$this->config->get('config_language_id'), $article_data);
			}

			return $article_data;			
		}
	}
	
	public function getArticleDescriptions($article_id) {
		$article_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_description WHERE article_id = '" . (int)$article_id . "'");

		foreach ($query->rows as $result) {
			$article_description_data[$result['language_id']] = array(
				'title'            => $result['title'],
				'subject'          => $result['subject'],
				'meta_keyword'     => $result['meta_keyword'],
				'meta_description' => $result['meta_description'],
				'description'      => $result['description'],
				'tag'              => $result['tag']
			);
		}

		return $article_description_data;
	}

	public function getArticleFilters($article_id) {
		$article_filter_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_filter WHERE article_id = '" . (int)$article_id . "'");
		
		foreach ($query->rows as $result) {
			$article_filter_data[] = $result['filter_id'];
		}
				
		return $article_filter_data;
	}
	
	public function getArticleImages($article_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_image WHERE article_id = '" . (int)$article_id . "'");
		
		return $query->rows;
	}
	
	public function getArticleStores($article_id) {
		$article_store_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_to_store WHERE article_id = '" . (int)$article_id . "'");

		foreach ($query->rows as $result) {
			$article_store_data[] = $result['store_id'];
		}
		
		return $article_store_data;
	}

	public function getArticleLayouts($article_id) {
		$article_layout_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_to_layout WHERE article_id = '" . (int)$article_id . "'");
		
		foreach ($query->rows as $result) {
			$article_layout_data[$result['store_id']] = $result['layout_id'];
		}
		
		return $article_layout_data;
	}
		
	public function getArticleRelated($article_id) {
		$article_related_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_related WHERE article_id = '" . (int)$article_id . "'");
		
		foreach ($query->rows as $result) {
			$article_related_data[] = $result['related_id'];
		}
		
		return $article_related_data;
	}

	public function getTotalArticles($data = array()) {
		$sql = "SELECT COUNT(DISTINCT a.article_id) AS total FROM " . DB_PREFIX . "article a LEFT JOIN " . DB_PREFIX . "article_description ad ON (a.article_id = ad.article_id)";
		 
		$sql .= " WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		 			
		if (!empty($data['filter_title'])) {
			$sql .= " AND ad.title LIKE '" . $this->db->escape($data['filter_title']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND a.status = '" . (int)$data['filter_status'] . "'";
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}	
	
	public function getTotalArticlesByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "article_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}	

	public function createDatabaseTables() {
		$sql  = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."article` ( ";
		$sql .= "`article_id` int(11) NOT NULL AUTO_INCREMENT, ";
		$sql .= "`image` varchar(255) DEFAULT NULL, ";
		$sql .= "`filename` varchar(128) NOT NULL, "; 
		$sql .= "`mask` varchar(128) NOT NULL, ";
		$sql .= "`author` varchar(64) NOT NULL, ";
		$sql .= "`top` int(1) NOT NULL DEFAULT '0', ";
		$sql .= "`bottom` int(1) NOT NULL DEFAULT '0', ";
		$sql .= "`date_available` datetime NOT NULL DEFAULT '0000-00-00 00:00:00', ";
		$sql .= "`sort_order` int(3) NOT NULL DEFAULT '0', ";
		$sql .= "`status` tinyint(1) NOT NULL DEFAULT '1', ";
		$sql .= "`date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00', ";
		$sql .= "`date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00', ";
		$sql .= "`viewed` int(5) NOT NULL DEFAULT '0', ";
		$sql .= "PRIMARY KEY (`article_id`) ";
		$sql .= ") ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;";
		$this->db->query($sql);

		$sql  = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."article_description` ( ";
		$sql .= "`article_id` int(11) NOT NULL, ";
		$sql .= "`language_id` int(11) NOT NULL, ";
		$sql .= "`title` varchar(64) NOT NULL, ";
		$sql .= "`subject` text NOT NULL, ";
		$sql .= "`description` text NOT NULL, ";
		$sql .= "`meta_description` varchar(255) NOT NULL, ";
		$sql .= "`meta_keyword` varchar(255) NOT NULL, ";
		$sql .= "`tag` text NOT NULL, ";
		$sql .= "PRIMARY KEY (`article_id`,`language_id`) ";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		$this->db->query($sql);

		$sql  = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."article_related` ( ";
		$sql .= "`article_id` int(11) NOT NULL, ";
		$sql .= "`related_id` int(11) NOT NULL, ";
		$sql .= "PRIMARY KEY (`article_id`,`related_id`) ";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		$this->db->query($sql);

		$sql  = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."article_to_layout` ( ";
		$sql .= "`article_id` int(11) NOT NULL, ";
		$sql .= "`store_id` int(11) NOT NULL, ";
		$sql .= "`layout_id` int(11) NOT NULL, ";
		$sql .= "PRIMARY KEY (`article_id`,`store_id`) ";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		$this->db->query($sql);

		$sql  = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."article_to_store` ( ";
		$sql .= "`article_id` int(11) NOT NULL, ";
		$sql .= "`store_id` int(11) NOT NULL, ";
		$sql .= "PRIMARY KEY (`article_id`,`store_id`) ";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		$this->db->query($sql);

		$sql  = "INSERT INTO  `".DB_PREFIX."layout` (`layout_id`, `name`) VALUES (NULL , 'Article');";
		$this->db->query($sql);
		
		$id = $this->db->getLastId();
		$sql = "INSERT INTO `".DB_PREFIX."layout_route` (`layout_route_id` ,`layout_id` ,`store_id` ,`route`) VALUES (NULL , '".$id."', '0', 'article/');";
		$this->db->query($sql);
	}
	
	public function dropDatabaseTables() {
		$sql = "DROP TABLE IF EXISTS `".DB_PREFIX."article`;";
		$this->db->query($sql);
		$sql = "DROP TABLE IF EXISTS `".DB_PREFIX."article_description`;";
		$this->db->query($sql);
		$sql = "DROP TABLE IF EXISTS `".DB_PREFIX."article_related`;";
		$this->db->query($sql);
		$sql = "DROP TABLE IF EXISTS `".DB_PREFIX."article_to_layout`;";
		$this->db->query($sql);
		$sql = "DROP TABLE IF EXISTS `".DB_PREFIX."article_to_store`;";
		$this->db->query($sql);
	}
}
?>