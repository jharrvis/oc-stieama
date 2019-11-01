<?php
######################################################################################
#
#  Articles for OpenCart. Developed by Tim Wills - Net-IT Web Consulting, Australia
#  Get articles written from as little as $1.90 from www.highqualitywriting.com
#  
######################################################################################
class ControllerModuleArticles extends Controller { 
	private $error = array();
	
	public function index() {
		$this->language->load('module/articles');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('module/articles');
		$this->model_module_articles->checkArticles();

		$this->getArticlesList();
	}
	
	public function insert() {
		$this->language->load('module/articles');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('module/articles');
		
		if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_module_articles->addArticle($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('module/articles', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getArticleForm();
	}
	
	public function update() {
		$this->language->load('module/articles');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('module/articles');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_module_articles->editArticle($this->request->get['article_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('module/articles', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		
		$this->getArticleForm();
	}
 	
	public function delete() {
		$this->language->load('module/articles');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('module/articles');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $article_id) {
				$this->model_module_articles->deleteArticle($article_id);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('module/articles', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getArticlesList();
	}
	
	private function getArticlesList() {
		//show the main page articles list
		
		$this->document->addStyle('view/stylesheet/articles.css');
		
		//set sort field
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'ad.title';
		}
		//set sort order
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		//set page number
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		//set query string
		$url = '';
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		//set breadcrumbs
  		$this->data['breadcrumbs'] = array();
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/articles', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		//define button URLs
		$this->data['url_live']			= HTTP_CATALOG.'index.php?route=articles/articles';//url list all live articles (site map)
		$this->data['url_authors']		= $this->url->link('module/articles/authors', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['url_preferences']	= $this->url->link('module/articles/preferences', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['url_tips']			= $this->url->link('module/articles/tips', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['insert']			= $this->url->link('module/articles/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete']			= $this->url->link('module/articles/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		//pageination data
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		
		$this->load->model('tool/image');
		
		$this->data['articles'] = array();
		
		$articles_total = $this->model_module_articles->getTotalArticles();
		
		$results = $this->model_module_articles->getArticles($data);
		
    	foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('module/articles/update', 'token=' . $this->session->data['token'] . '&article_id=' . $result['article_id'] . $url, 'SSL')
			);
			
			//specify image thumbnail (if available)
			if($result['image']!='') {
				$result['image'] = $this->model_tool_image->resize($result['image'], 30, 30);
			}
			
			//link to edit author
			if(is_numeric($result['author_id'])) {
				$author_href = $this->url->link('module/articles/updateAuthor', 'token=' . $this->session->data['token'] . '&author_id=' . $result['author_id'] . $url, 'SSL');
			} else {
				$author_href = '';
			}
			
			$this->data['articles'][] = array(
				'article_id'	=> $result['article_id'],
				'title'			=> $result['title'],
				'date_added'	=> $result['date_added'],
				'author_id'		=> $result['author_id'],
				'author_name'	=> $result['author_name'],
				'author_href'	=> $author_href,
				'image'			=> $result['image'],
				'contextual'	=> $result['contextual'],
				'sort_order'	=> $result['sort_order'],
				'status'		=> $result['status'],
				'selected'		=> isset($this->request->post['selected']) && in_array($result['article_id'], $this->request->post['selected']),
				'action'		=> $action
			);
		}
		$this->data['arYesNo']				= array(0=>'No', 1=>'Yes');
		$this->data['arStatus']				= array(0=>'Disabled', 1=>'Enabled');
		
		$this->data['heading_title']		= $this->language->get('heading_title');
		
		$this->data['text_no_results']		= $this->language->get('text_no_results');
		$this->data['text_author_credit']	= $this->language->get('text_author_credit');
		
		$this->data['button_live']			= $this->language->get('button_live');
		$this->data['button_authors']		= $this->language->get('button_authors');
		$this->data['button_preferences']	= $this->language->get('button_preferences');
		$this->data['button_tips']			= $this->language->get('text_tips');
		
		$this->data['column_title']			= $this->language->get('column_title');
		$this->data['column_date_added']	= $this->language->get('column_date_added');
		$this->data['column_author_name']	= $this->language->get('column_author_name');
		$this->data['column_image']			= $this->language->get('column_image');
		$this->data['column_contextual']	= $this->language->get('column_contextual');
		$this->data['column_sort_order']	= $this->language->get('column_sort_order');
		$this->data['column_status']		= $this->language->get('column_status');
		$this->data['column_action']		= $this->language->get('column_action');		
		
		$this->data['button_insert']		= $this->language->get('button_insert');
		$this->data['button_delete']		= $this->language->get('button_delete');
 
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_title']		= $this->url->link('module/articles', 'token=' . $this->session->data['token'] . '&sort=ad.title' . $url, 'SSL');
		$this->data['sort_date_added']	= $this->url->link('module/articles', 'token=' . $this->session->data['token'] . '&sort=a.date_added' . $url, 'SSL');
		$this->data['sort_author_name']	= $this->url->link('module/articles', 'token=' . $this->session->data['token'] . '&sort=aa.name' . $url, 'SSL');
		$this->data['sort_image']		= $this->url->link('module/articles', 'token=' . $this->session->data['token'] . '&sort=a.image' . $url, 'SSL');
		$this->data['sort_contextual']	= $this->url->link('module/articles', 'token=' . $this->session->data['token'] . '&sort=a.contextual' . $url, 'SSL');
		$this->data['sort_order']		= $this->url->link('module/articles', 'token=' . $this->session->data['token'] . '&sort=a.sort_order' . $url, 'SSL');
		$this->data['sort_status']		= $this->url->link('module/articles', 'token=' . $this->session->data['token'] . '&sort=a.status' . $url, 'SSL');
		
		$url = '';
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $articles_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('module/articles', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'module/articles_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
	}
	
	private function getArticleForm() {
		$this->document->addScript('view/javascript/strings.js');
		$this->document->addStyle('view/stylesheet/articles.css');
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_default']			= $this->language->get('text_default');	//store
		$this->data['text_enabled']			= $this->language->get('text_enabled');	//status
    	$this->data['text_disabled']		= $this->language->get('text_disabled');//status
		$this->data['text_browse']			= $this->language->get('text_browse');	//image
		$this->data['text_clear']			= $this->language->get('text_clear');	//image
		$this->data['text_image_manager']	= $this->language->get('text_image_manager');
		$this->data['text_author_credit']	= $this->language->get('text_author_credit');
		
		$this->data['entry_title']			= $this->language->get('entry_title');
		$this->data['entry_description']	= $this->language->get('entry_description');
		$this->data['entry_store']			= $this->language->get('entry_store');
		$this->data['entry_keyword']		= $this->language->get('entry_keyword');
		$this->data['entry_date_added']		= $this->language->get('entry_date_added');
		$this->data['entry_author']			= $this->language->get('entry_author');
		$this->data['entry_image']			= $this->language->get('entry_image');
		$this->data['entry_contextual']		= $this->language->get('entry_contextual');
		$this->data['entry_sort_order']		= $this->language->get('entry_sort_order');
		$this->data['entry_status']			= $this->language->get('entry_status');
		$this->data['entry_layout']			= $this->language->get('entry_layout');
		
		$this->data['button_save']			= $this->language->get('button_save');
		$this->data['button_cancel']		= $this->language->get('button_cancel');
    	
		$this->data['tab_general']			= $this->language->get('tab_general');
    	$this->data['tab_data']				= $this->language->get('tab_data');
		
		$this->data['token']				= $this->session->data['token'];
		
		$this->data['url_preferences']		= $this->url->link('module/articles/preferences', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_authors']			= $this->url->link('module/articles/authors', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['button_view_authors']	= $this->language->get('button_view_authors');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = array();
		}
 		if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = array();
		}
		
 		if (isset($this->error['date_added'])) {
			$this->data['error_date_added'] = $this->error['date_added'];
		} else {
			$this->data['error_date_added'] = '';
		}
		
		$url = '';
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
  		$this->data['breadcrumbs'] = array();
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),     		
      		'separator' => false
   		);
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/articles', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['article_id'])) {
			$this->data['action'] = $this->url->link('module/articles/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('module/articles/update', 'token=' . $this->session->data['token'] . '&article_id=' . $this->request->get['article_id'] . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('module/articles', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		if (isset($this->request->get['article_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$articles_info = $this->model_module_articles->getArticle($this->request->get['article_id']);
		}
		
		$this->data['authors'] = $this->model_module_articles->getAuthors();
		
		if(isset($this->request->post['author_id'])) {
			$this->data['author_id'] = $this->request->post['author_id'];
		} elseif(!empty($articles_info)) {
			$this->data['author_id'] = $articles_info['author_id'];
		} else {
			$this->data['author_id'] = '';
		}
		
		if(isset($this->request->post['contextual'])) {
			$this->data['contextual'] = $this->request->post['contextual'];
		} elseif(!empty($articles_info)) {
			$this->data['contextual'] = $articles_info['contextual'];
		} else {
			$this->data['contextual'] = '1';
		}
		
		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($articles_info)) {
			$this->data['keyword'] = $articles_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}
		
		if(isset($this->request->post['date_added'])) {
			$this->data['date_added'] = $this->request->post['date_added'];
		} elseif(!empty($articles_info)) {
			$this->data['date_added'] = $articles_info['date_added'];
		} else {
			$this->data['date_added'] = date("Y-m-d");
		}
		
		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (isset($articles_info['image'])) {
			$this->data['image'] = $articles_info['image'];
		} else {
			$this->data['image'] = '';
		}
		
		$this->load->model('tool/image');
		
		if (isset($this->request->post['image']) && file_exists(DIR_IMAGE . $this->request->post['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($articles_info) && $articles_info['image'] && file_exists(DIR_IMAGE . $articles_info['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($articles_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['article_description'])) {
			$this->data['article_description'] = $this->request->post['article_description'];
		} elseif (isset($this->request->get['article_id'])) {
			$this->data['article_description'] = $this->model_module_articles->getArticleDescriptions($this->request->get['article_id']);
		} else {
			$this->data['article_description'] = array();
		}
		
		if (isset($this->request->post['contextual'])) {
			$this->data['contextual'] = $this->request->post['contextual'];
		} elseif (!empty($articles_info)) {
			$this->data['contextual'] = $articles_info['contextual'];
		} else {
			$this->data['contextual'] = 1;
		}
		
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($articles_info)) {
			$this->data['sort_order'] = $articles_info['sort_order'];
		} else {
			$this->data['sort_order'] = 1;
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($articles_info)) {
			$this->data['status'] = $articles_info['status'];
		} else {
			$this->data['status'] = 1;
		}
		
		$this->load->model('setting/store');
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		if (isset($this->request->post['article_store'])) {
			$this->data['article_store'] = $this->request->post['article_store'];
		} elseif (isset($this->request->get['article_id'])) {
			$this->data['article_store'] = $this->model_module_articles->getArticleStores($this->request->get['article_id']);
		} else {
			$this->data['article_store'] = array(0);
		}
		
		
		$this->template = 'module/article_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
	}
	
	private function utf8_strlen($string) {
		//this function is included in OpenCart 1.5.1.3 and later (/system/helper/utf8.php)
		//I've added it here for backward compatibility
		return strlen(utf8_decode($string));
	}
	
	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'module/articles')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		foreach ($this->request->post['article_description'] as $language_id => $value) {
			if (($this->utf8_strlen($value['title']) < 3) || ($this->utf8_strlen($value['title']) > 250)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
			if (($this->utf8_strlen($value['description']) < 3)) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}
		}
		if($this->request->post['date_added']!='' && !$this->validateDate($this->request->post['date_added'])) {
			$this->error['date_added'] = $this->language->get('error_date_added');
		}
		/*
		if (!isset($this->request->post['image'])) {
			$this->error['image'] = $this->language->get('error_image');
		}
		*/
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	private function validateDate($date) {
		//check for valid date format yyyy-mm-dd
		$pattern = '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/';
		if(preg_match($pattern, $date)) {
			//check number ranges
			$year	= substr($date,0,4);
			$month	= substr($date,5,7);
			$day	= substr($date,8);
			if($year>2000 && $year<2040 && $month>=1 && $month<=12 && $day>=1 && $day<=31) return true;
		}
	}
	private function getTimestamp($date) {
		//return timestamp. Assumes date has already been validated
		$year	= substr($date,0,4);
		$month	= substr($date,5,7);
		$day	= substr($date,8);
		return mktime(0,0,0,(int)$month,(int)$day,(int)$year);
	}
	
	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'module/articles')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	
	//PREFERENCES
	public function preferences() {
		$this->language->load('module/articles');
		
		$this->document->addScript('view/javascript/strings.js');
		$this->document->addStyle('view/stylesheet/articles.css');
		
		$this->document->setTitle($this->language->get('heading_title_preferences'));
		
		
		$this->load->model('setting/setting');
		
		
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatePreferencesForm()) {			
			$this->model_setting_setting->editSetting('articles', $this->request->post);		
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('module/articles', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		
		$this->data['heading_title']		= $this->language->get('heading_title_preferences');
		
		$this->data['text_enabled']			= $this->language->get('text_enabled');
    	$this->data['text_disabled']		= $this->language->get('text_disabled');
		$this->data['text_author_credit']	= $this->language->get('text_author_credit');
		$this->data['text_content_top'] 	= $this->language->get('text_content_top');
		$this->data['text_content_bottom']	= $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] 	= $this->language->get('text_column_left');
		$this->data['text_column_right']	= $this->language->get('text_column_right');
		$this->data['text_yes']				= $this->language->get('text_yes');
		$this->data['text_no']				= $this->language->get('text_no');
		$this->data['text_author_credit']	= $this->language->get('text_author_credit');
		
		$this->data['entry_layout']			= $this->language->get('entry_layout');
		$this->data['entry_position']		= $this->language->get('entry_position');
		$this->data['entry_status']			= $this->language->get('entry_status');
		$this->data['entry_sort_order']		= $this->language->get('entry_sort_order');
		
		$this->data['entry_columns']		= $this->language->get('entry_columns');
		$this->data['entry_random']			= $this->language->get('entry_random');
		
		$this->data['entry_title']			= $this->language->get('entry_title');
		$this->data['entry_dir']			= $this->language->get('entry_dir');
		$this->data['entry_auto_contextual']= $this->language->get('entry_auto_contextual');
		$this->data['entry_img_pos']		= $this->language->get('entry_img_pos');
		$this->data['entry_img_width']		= $this->language->get('entry_img_width');
		$this->data['entry_img_height']		= $this->language->get('entry_img_height');
		$this->data['entry_sort_by']		= $this->language->get('entry_sort_by');
		
		$this->data['button_save']			= $this->language->get('button_save');
		$this->data['button_cancel']		= $this->language->get('button_cancel');
		$this->data['button_add_module']	= $this->language->get('button_add_module');
		$this->data['button_remove']		= $this->language->get('button_remove');
    	
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
 		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}
		
 		if (isset($this->error['img_width'])) {
			$this->data['error_img_width'] = $this->error['img_width'];
		} else {
			$this->data['error_img_width'] = '';
		}
		
 		if (isset($this->error['img_height'])) {
			$this->data['error_img_height'] = $this->error['img_height'];
		} else {
			$this->data['error_img_height'] = '';
		}
		
		//querystring
		$url = '';
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		//breadcrumbs
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),     		
      		'separator' => false
   		);
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/articles', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_preferences'),
			'href'      => $this->url->link('module/articles/preferences', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/articles/preferences', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('module/articles', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['token'] = $this->session->data['token'];
		
		
		$this->data['articles_preferences'] = array();
		if (isset($this->request->post['articles_preferences'])) {
			$this->data['articles_preferences'] = $this->request->post['articles_preferences'];
		} elseif ($this->config->get('articles_preferences')) {
			$this->data['articles_preferences'] = $this->config->get('articles_preferences');
		}
		//set defaults for first use (either first time loading preferences or after update with new preferences added)
		if(!isset($this->data['articles_preferences']['title']))		$this->data['articles_preferences']['title']		= 'Articles';
		if(!isset($this->data['articles_preferences']['dir']))			$this->data['articles_preferences']['dir']			= 'articles';
		if(!isset($this->data['articles_preferences']['contextual']))	$this->data['articles_preferences']['contextual']	= '1';
		if(!isset($this->data['articles_preferences']['img_pos']))		$this->data['articles_preferences']['img_pos']		= 'right';
		if(!isset($this->data['articles_preferences']['img_width']))	$this->data['articles_preferences']['img_width']	= '250';
		if(!isset($this->data['articles_preferences']['img_height']))	$this->data['articles_preferences']['img_height']	= '400';
		if(!isset($this->data['articles_preferences']['sort_by']))		$this->data['articles_preferences']['sort_by']		= 'a.sort_order';
		if(!isset($this->data['articles_preferences']['sort_dir']))		$this->data['articles_preferences']['sort_dir']		= 'ASC';
		
		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['articles_module'])) {
			$this->data['modules'] = $this->request->post['articles_module'];
		} elseif ($this->config->get('articles_module')) {
			$this->data['modules'] = $this->config->get('articles_module');
		}
		//var_dump($this->data['modules']);
		
		
		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->template = 'module/articles_preferences_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
	}
	
	private function validatePreferencesForm() {
		if (!$this->user->hasPermission('modify', 'module/articles')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (isset($this->request->post['articles_preferences'])) {
			$preferences = $this->request->post['articles_preferences'];
			//title
			if($preferences['title']=='') {
				$this->error['title'] = $this->language->get('error_preferences_title');
			}
			//dir (not validate, but clean)
			$preferences['dir'] = strtolower(trim($preferences['dir']));
			if(substr($preferences['dir'],0,1)=='/') $preferences['dir'] = substr($preferences['dir'],1);
			if(substr($preferences['dir'],-1)=='/') $preferences['dir'] = substr($preferences['dir'],0,-1);
			
			//image max width
			if(!is_numeric($preferences['img_width']) || $preferences['img_width']<20 || $preferences['img_width']>1000) {
				$this->error['img_width'] = $this->language->get('error_preferences_img_width');
			}
			//image max height
			if(!is_numeric($preferences['img_height']) || $preferences['img_height']<20 || $preferences['img_height']>800) {
				$this->error['img_height'] = $this->language->get('error_preferences_img_height');
			}
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	
	//TIPS
	public function tips() {
		$this->language->load('module/articles');
		$this->document->addStyle('view/stylesheet/articles.css');
		
		$this->document->setTitle($this->language->get('text_tips'));
		
		$this->data['heading_title']		= $this->language->get('text_tips');
		$this->data['text_author_credit']	= $this->language->get('text_author_credit');
		
		//breadcrumbs
  		$this->data['breadcrumbs'] = array();
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),     		
      		'separator' => false
   		);
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/articles', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_tips'),
			'href'      => $this->url->link('module/articles/tips', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		
		
		$tips = array();
		$tips[] = array(108, 'Author&rsquo;s picture in search results', 'authors-picture-in-search-results');
		$tips[] = array(48, 'Article Marketing', 'article-marketing');
		$tips[] = array(72, 'Beating Google Panda', 'beating-google-panda');
		$tips[] = array(96, 'Google Farmer Update', 'google-farmer-update');
		$tips[] = array(106, 'Buying Aged Domains', 'buying-aged-domains');
		$tips[] = array(98, 'Building Authority Sites', 'building-authority-sites');
		$tips[] = array(95, 'Google Plus One', 'google-plus-one');
		$tips[] = array(107, 'Increase Website Conversions', 'increase-website-conversions');
		$tips[] = array(79, 'Is Your Content Good?', 'is-your-content-good');
		$tips[] = array(76, 'Adding Google Plus', 'adding-google-plus');
		$tips[] = array(74, 'Is your website Sticky?', 'is-your-website-sticky');
		$tips[] = array(73, 'The Google Penguin Update', 'the-google-penguin-update');
		$tips[] = array(86, 'Less Backlinks Better Results', 'less-backlinks-better-results');
		$tips[] = array(89, 'Lead Generation Websites', 'lead-generation-websites');
		$tips[] = array(102, 'List Building Ideas', 'list-building-ideas');
		$tips[] = array(90, 'Money in Adsense?', 'money-in-adsense');
		$tips[] = array(103, 'Keyword Domains', 'keyword-domains');
		$tips[] = array(92, 'Youtube Keyword Tool', 'youtube-keyword-tool');
		
		$this->data['tips'] = $tips;
		
		
		
		
		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->template = 'module/articles_tips.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
	}
	
	//AUTHORS
	public function authors() {
		$this->language->load('module/articles');
		
		$this->document->setTitle($this->language->get('heading_title_authors'));
		
		$this->load->model('module/articles');
		$this->model_module_articles->checkArticles();

		$this->getAuthorsList();
	}
	
	private function getAuthorsList() {
		//show the authors table list
		
		$this->document->addStyle('view/stylesheet/articles.css');
		
		//set sort field
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'aa.name';
		}
		//set sort order
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		//set page number
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		//set query string
		$url = '';
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		//set breadcrumbs
  		$this->data['breadcrumbs'] = array();
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/articles', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_authors'),
			'href'      => $this->url->link('module/articles/authors', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		//define button URLs
		$this->data['insert']			= $this->url->link('module/articles/insertAuthor', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete']			= $this->url->link('module/articles/deleteAuthor', 'token=' . $this->session->data['token'] . $url, 'SSL');	
		
		//pageination data
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		
		$this->load->model('tool/image');
		
		$this->data['authors'] = array();
		
		$authors_total = $this->model_module_articles->getTotalAuthors();
		
		$results = $this->model_module_articles->getAuthors($data);
		
    	foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('module/articles/updateAuthor', 'token=' . $this->session->data['token'] . '&author_id=' . $result['author_id'] . $url, 'SSL')
			);
			
			//specify image thumbnail (if available)
			if($result['image']!='') {
				$result['image'] = $this->model_tool_image->resize($result['image'], 30, 30);
			}
			
			$this->data['authors'][] = array(
				'author_id'		=> $result['author_id'],
				'name'			=> $result['name'],
				'googleid'		=> $result['googleid'],
				'image'			=> $result['image'],
				'status'		=> $result['status'],
				'short_bio'		=> substr(strip_tags(html_entity_decode($result['short_bio'])),0,50),
				'links'			=> $result['links'],
				'selected'		=> isset($this->request->post['selected']) && in_array($result['author_id'], $this->request->post['selected']),
				'action'		=> $action
			);
		}
		$this->data['arStatus']				= array(0=>'Disabled', 1=>'Enabled');
		
		$this->data['heading_title']		= $this->language->get('heading_title_authors');
		
		$this->data['text_no_results']		= $this->language->get('text_no_results');
		$this->data['text_author_credit']	= $this->language->get('text_author_credit');
		
		$this->data['column_author_name']	= $this->language->get('column_author_name');
		$this->data['column_author_googleid']= $this->language->get('column_author_googleid');
		$this->data['column_author_photo']	= $this->language->get('column_author_photo');
		$this->data['column_author_bio']	= $this->language->get('column_author_bio');
		$this->data['column_author_links']	= $this->language->get('column_author_links');
		$this->data['column_status']		= $this->language->get('column_status');
		$this->data['column_action']		= $this->language->get('column_action');		
		
		$this->data['button_insert']		= $this->language->get('button_insert');
		$this->data['button_delete']		= $this->language->get('button_delete');
 
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_name']	= $this->url->link('module/articles/authors', 'token=' . $this->session->data['token'] . '&sort=aa.name' . $url, 'SSL');
		$this->data['sort_googleid']= $this->url->link('module/articles/authors', 'token=' . $this->session->data['token'] . '&sort=aa.googleid' . $url, 'SSL');
		$this->data['sort_image']	= $this->url->link('module/articles/authors', 'token=' . $this->session->data['token'] . '&sort=aa.image' . $url, 'SSL');
		$this->data['sort_bio']		= $this->url->link('module/articles/authors', 'token=' . $this->session->data['token'] . '&sort=aad.short_bio' . $url, 'SSL');
		$this->data['sort_links']	= $this->url->link('module/articles/authors', 'token=' . $this->session->data['token'] . '&sort=links' . $url, 'SSL');
		$this->data['sort_status']	= $this->url->link('module/articles/authors', 'token=' . $this->session->data['token'] . '&sort=aa.status' . $url, 'SSL');
		
		$url = '';
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		$pagination = new Pagination();
		$pagination->total = $authors_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('module/articles/authors', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		
		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'module/articles_authors_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
	}
	
	public function insertAuthor() {
		$this->language->load('module/articles');
		
		$this->document->setTitle($this->language->get('heading_title_authors'));
		
		$this->load->model('module/articles');
		
		if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateAuthorForm()) {
			$this->model_module_articles->addAuthor($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('module/articles/authors', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		
		$this->getAuthorForm();
	}
	
	public function updateAuthor() {
		$this->language->load('module/articles');

		$this->document->setTitle($this->language->get('heading_title_authors'));
		
		$this->load->model('module/articles');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateAuthorForm()) {
			$this->model_module_articles->editAuthor($this->request->get['author_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('module/articles/authors', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		
		$this->getAuthorForm();
	}
 	
	public function deleteAuthor() {
		$this->language->load('module/articles');
		
		$this->document->setTitle($this->language->get('heading_title_authors'));
		
		$this->load->model('module/articles');
		
		if (isset($this->request->post['selected']) && $this->validateDeleteAuthor()) {
			foreach ($this->request->post['selected'] as $author_id) {
				$this->model_module_articles->deleteAuthor($author_id);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('module/articles/authors', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getAuthorsList();
	}
	
	private function validateDeleteAuthor() {
		if (!$this->user->hasPermission('modify', 'module/articles')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	private function getAuthorForm() {
		$this->document->addStyle('view/stylesheet/articles.css');
		
		$this->data['heading_title'] = $this->language->get('heading_title_authors');
		
		$this->data['text_default']			= $this->language->get('text_default');	//store
		$this->data['text_enabled']			= $this->language->get('text_enabled');	//status
    	$this->data['text_disabled']		= $this->language->get('text_disabled');//status
		$this->data['text_browse']			= $this->language->get('text_browse');	//image
		$this->data['text_clear']			= $this->language->get('text_clear');	//image
		$this->data['text_image_manager']	= $this->language->get('text_image_manager');
		$this->data['text_author_credit']	= $this->language->get('text_author_credit');
		
		$this->data['entry_author_name']	= $this->language->get('entry_author_name');
		$this->data['entry_author_googleid']= $this->language->get('entry_author_googleid');
		$this->data['text_author_google_url']= $this->language->get('text_author_google_url');
		$this->data['entry_author_photo']	= $this->language->get('entry_author_photo');
		$this->data['entry_author_short_bio']= $this->language->get('entry_author_short_bio');
		$this->data['entry_author_long_bio']= $this->language->get('entry_author_long_bio');
		$this->data['entry_status']			= $this->language->get('entry_status');
		$this->data['entry_sort_order']		= $this->language->get('entry_sort_order');
		
		$this->data['entry_link_name']		= $this->language->get('entry_link_name');
		$this->data['entry_link_href']		= $this->language->get('entry_link_href');
		$this->data['entry_new_window']		= $this->language->get('entry_new_window');
		$this->data['entry_nofollow']		= $this->language->get('entry_nofollow');
		
		$this->data['button_add_link']		= $this->language->get('button_add_link');
		$this->data['button_save']			= $this->language->get('button_save');
		$this->data['button_cancel']		= $this->language->get('button_cancel');
		$this->data['button_remove']		= $this->language->get('button_remove');
    	
		$this->data['tab_general']			= $this->language->get('tab_general');
    	$this->data['tab_data']				= $this->language->get('tab_data');
		
		$this->data['token']				= $this->session->data['token'];
		
		
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_author_name'] = $this->error['name'];
		} else {
			$this->data['error_author_name'] = '';
		}
 		if (isset($this->error['googleid'])) {
			$this->data['error_googleid'] = $this->error['googleid'];
		} else {
			$this->data['error_googleid'] = '';
		}
		
		$url = '';
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
  		$this->data['breadcrumbs'] = array();
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),     		
      		'separator' => false
   		);
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/articles', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_authors'),
			'href'      => $this->url->link('module/articles/authors', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['author_id'])) {
			$this->data['action'] = $this->url->link('module/articles/insertAuthor', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('module/articles/updateAuthor', 'token=' . $this->session->data['token'] . '&author_id=' . $this->request->get['author_id'] . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('module/articles/authors', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		$authors_info = array();
		if (isset($this->request->get['author_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$authors_info = $this->model_module_articles->getAuthor($this->request->get['author_id']);
		}
		
		if(isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif(!empty($authors_info)) {
			$this->data['name'] = $authors_info['name'];
		} else {
			$this->data['name'] = '';
		}
		
		if(isset($this->request->post['googleid'])) {
			$this->data['googleid'] = $this->request->post['googleid'];
		} elseif(!empty($authors_info)) {
			$this->data['googleid'] = $authors_info['googleid'];
		} else {
			$this->data['googleid'] = '';
		}
		
		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (isset($authors_info['image'])) {
			$this->data['image'] = $authors_info['image'];
		} else {
			$this->data['image'] = '';
		}
		
		$this->load->model('tool/image');
		
		if (isset($this->request->post['image']) && file_exists(DIR_IMAGE . $this->request->post['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($authors_info) && $authors_info['image'] && file_exists(DIR_IMAGE . $authors_info['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($authors_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['author_description'])) {
			$this->data['author_description'] = $this->request->post['author_description'];
		} elseif (isset($this->request->get['author_id'])) {
			$this->data['author_description'] = $this->model_module_articles->getAuthorDescriptions($this->request->get['author_id']);
		} else {
			$this->data['author_description'] = array();
		}
		
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($authors_info)) {
			$this->data['status'] = $authors_info['status'];
		} else {
			$this->data['status'] = 1;
		}
		
		
		$this->data['links'] = isset($authors_info['links']) ? $authors_info['links'] : array();
		
		
		$this->template = 'module/articles_author_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
	}
	
	private function validateAuthorForm() {
		if (!$this->user->hasPermission('modify', 'module/articles')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if($this->request->post['name']=='') {
			$this->error['name'] = $this->language->get('error_author_name');
		}
		if($this->request->post['googleid']!='' && !is_numeric($this->request->post['googleid'])) {
			$this->error['googleid'] = $this->language->get('error_author_googleid');
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>