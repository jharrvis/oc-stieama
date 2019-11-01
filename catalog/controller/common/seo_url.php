<?php
class ControllerCommonSeoUrl extends Controller {
	public function index() {
		// Add rewrite to url class
		if ($this->config->get('config_seo_url')) {
			$this->url->addRewrite($this);
		}
		
		// Decode URL
		if (isset($this->request->get['_route_'])) {
			$parts = explode('/', $this->request->get['_route_']);
			
			foreach ($parts as $part) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "'");
				
				if ($query->num_rows) {
					$url = explode('=', $query->row['query']);
					
					if ($url[0] == 'product_id') {
						$this->request->get['product_id'] = $url[1];
					}
					
					if ($url[0] == 'category_id') {
						if (!isset($this->request->get['path'])) {
							$this->request->get['path'] = $url[1];
						} else {
							$this->request->get['path'] .= '_' . $url[1];
						}
					}	
					
					if ($url[0] == 'manufacturer_id') {
						$this->request->get['manufacturer_id'] = $url[1];
					}
					
					if ($url[0] == 'information_id') {
						$this->request->get['information_id'] = $url[1];
					}
					
					if ($url[0] == 'article_id') {
						$this->request->get['article_id'] = $url[1];
					}
					if ($url[0] == 'author_id') {
						$this->request->get['author_id'] = $url[1];
					}
					
				} else {
					$this->request->get['route'] = 'error/not_found';	
				}
			}
			
			if (isset($this->request->get['product_id'])) {
				$this->request->get['route'] = 'product/product';
			} elseif (isset($this->request->get['path'])) {
				$this->request->get['route'] = 'product/category';
			} elseif (isset($this->request->get['manufacturer_id'])) {
				$this->request->get['route'] = 'product/manufacturer/product';
			} elseif (isset($this->request->get['information_id'])) {
				$this->request->get['route'] = 'information/information';
			} elseif (isset($this->request->get['article_id'])) {
				$this->request->get['route'] = 'articles/article';
			} elseif (isset($this->request->get['auhtor_id'])) {
				$this->request->get['route'] = 'articles/author';
			}
			
			if (isset($this->request->get['route'])) {
				return $this->forward($this->request->get['route']);
			}
		}
	}
	
	public function rewrite($link) {
		if ($this->config->get('config_seo_url')) {
			$url_data = parse_url(str_replace('&amp;', '&', $link));
			
			$url = ''; 
			
			$data = array();
			
			parse_str($url_data['query'], $data);
			
			foreach ($data as $key => $value) {
				if (isset($data['route'])) {
					if (($data['route'] == 'product/product' && $key == 'product_id') || 
						 (($data['route'] == 'product/manufacturer/product' || $data['route'] == 'product/product') && $key == 'manufacturer_id') || 
						 ($data['route'] == 'information/information' && $key == 'information_id') ||
						 ($data['route'] == 'articles/article' && $key == 'article_id') ||
						 ($data['route'] == 'articles/author' && $key == 'author_id')
						 ) {
						
						//directory
						$dir = '';
						if(isset($data['dir']) && $data['dir']!='')	{
							$dir = '/' . $data['dir'];
							unset($data['dir']);
						}
						
						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");
					
						if ($query->num_rows) {
							$url .= $dir . '/' . $query->row['keyword'];
							
							unset($data[$key]);
						}
					} elseif ($key == 'path') {
						$categories = explode('_', $value);
						
						foreach ($categories as $category) {
							$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int)$category . "'");
					
							if ($query->num_rows) {
								$url .= '/' . $query->row['keyword'];
							}							
						}
						
						unset($data[$key]);
					}
				}
			}
			
			if ($url) {
				unset($data['route']);
			
				$query = '';
			
				if ($data) {
					foreach ($data as $key => $value) {
						$query .= '&' . $key . '=' . $value;
					}
					
					if ($query) {
						$query = '?' . trim($query, '&');
					}
				}

				return $url_data['scheme'] . '://' . $url_data['host'] . (isset($url_data['port']) ? ':' . $url_data['port'] : '') . str_replace('/index.php', '', $url_data['path']) . $url . $query;
			}
			else {
				//SEO URL's are enabled, but this URL doesn't have an SEO keyword
				return $this->removeDir($link);
			}
		}
		else {
			return $this->removeDir($link);
		}		
	}
	private function removeDir($link) {
		//remove any "dir=article-folder&"
		//this isn't detrimental, but it makes the URLs a bit cleaner when not using SEO URLs.
		return preg_replace('|dir=[a-zA-Z-/]+&amp;|', '', $link);
	}
}
?>