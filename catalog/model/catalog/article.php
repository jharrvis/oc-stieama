<?php
class ModelCatalogArticle extends Model {
	public function updateViewed($article_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "article SET viewed = (viewed + 1) WHERE article_id = '" . (int)$article_id . "'");
	}
	
	public function getArticle($article_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "article a LEFT JOIN " . DB_PREFIX . "article_description ad ON (a.article_id = ad.article_id) LEFT JOIN " . DB_PREFIX . "article_to_store a2s ON (a.article_id = a2s.article_id) WHERE a.article_id = '" . (int)$article_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND a.status = '1'");
	
		return $query->row;
	}
	
	public function getArticles($data = array()) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article a LEFT JOIN " . DB_PREFIX . "article_description ad ON (a.article_id = ad.article_id) LEFT JOIN " . DB_PREFIX . "article_to_store a2s ON (a.article_id = a2s.article_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND a.status = '1' ORDER BY a.sort_order, LCASE(ad.title) ASC");
		
		return $query->rows;
	}
	
	public function getLatestArticles($limit) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
				
		$article_data = $this->cache->get('article.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $customer_group_id . '.' . (int)$limit);

		if (!$article_data) { 
			$query = $this->db->query("SELECT a.article_id FROM " . DB_PREFIX . "article a LEFT JOIN " . DB_PREFIX . "article_to_store a2s ON (a.article_id = a2s.article_id) WHERE a.status = '1' AND a.date_available <= NOW() AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY a.date_added DESC LIMIT " . (int)$limit);
		 	 
			foreach ($query->rows as $result) {
				$article_data[$result['article_id']] = $this->getArticle($result['article_id']);
			}
			
			$this->cache->set('article.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$limit, $article_data);
		}
		
		return $article_data;
	}
	
	public function getArticleRelated($article_id) {
		$article_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_related ar LEFT JOIN " . DB_PREFIX . "article a ON (ar.related_id = a.article_id) LEFT JOIN " . DB_PREFIX . "article_to_store a2s ON (a.article_id = a2s.article_id) WHERE ar.article_id = '" . (int)$article_id . "' AND a.status = '1' AND a.date_available <= NOW() AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		foreach ($query->rows as $result) { 
			$article_data[$result['related_id']] = $this->getArticle($result['related_id']);
		}
		
		return $article_data;
	}
		
	/*public function getArticleCategories($article_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_to_article_category WHERE article_id = '" . (int)$article_id . "'");

		return $query->rows;
	}

	public function getArticleCategoriesByArticleId($article_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_to_article_category WHERE article_id = '" . (int)$article_id . "'");

		return $query->rows;
	}*/

	public function getTotalArticles($data = array()) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		$sql = "SELECT COUNT(DISTINCT a.article_id) AS total"; 
		
		if (!empty($data['filter_article_category_id'])) {
			if (!empty($data['filter_sub_article_category'])) {
				$sql .= " FROM " . DB_PREFIX . "article_category_path acp LEFT JOIN " . DB_PREFIX . "article_to_article_category a2c ON (acp.article_category_id = a2c.article_category_id)";			
			} else {
				$sql .= " FROM " . DB_PREFIX . "article_to_article_category a2c";
			}
		
			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "article_filter af ON (a2c.article_id = af.article_id) LEFT JOIN " . DB_PREFIX . "article a ON (af.article_id = a.article_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "article a ON (a2c.article_id = a.article_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "article a";
		}
		
		$sql .= " LEFT JOIN " . DB_PREFIX . "article_description ad ON (a.article_id = ad.article_id) LEFT JOIN " . DB_PREFIX . "article_to_store a2s ON (a.article_id = a2s.article_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND a.status = '1' AND a.date_available <= NOW() AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		
		/*if (!empty($data['filter_article_category_id'])) {
			if (!empty($data['filter_sub_article_category'])) {
				$sql .= " AND ap.path_id = '" . (int)$data['filter_article_category_id'] . "'";	
			} else {
				$sql .= " AND a2c.article_category_id = '" . (int)$data['filter_article_category_id'] . "'";			
			}*/	
		
			if (!empty($data['filter_filter'])) {
				$implode = array();
				
				$filters = explode(',', $data['filter_filter']);
				
				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}
				
				$sql .= " AND af.filter_id IN (" . implode(',', $implode) . ")";				
			}
		/*}*/
		
		if (!empty($data['filter_title']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";
			
			if (!empty($data['filter_title'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_title'])));

				foreach ($words as $word) {
					$implode[] = "ad.title LIKE '%" . $this->db->escape($word) . "%'";
				}
				
				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR ad.description LIKE '%" . $this->db->escape($data['filter_title']) . "%'";
				}
			}
			
			if (!empty($data['filter_title']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}
			
			if (!empty($data['filter_tag'])) {
				$sql .= "ad.tag LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
			}
		
			$sql .= ")";				
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
    
	public function getArticleLayoutId($article_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_to_layout WHERE article_id = '" . (int)$article_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
		 
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return $this->config->get('config_layout_article');
		}
	}	
}
?>