<?php
class ControllerCatalogArticle extends Controller { 
	private $error = array();

	public function index() {
		$this->language->load('catalog/article');

		$this->document->setTitle($this->language->get('heading_title'));
		 
		$this->load->model('catalog/article');

		$this->getList();
	}

	public function insert() {
		$this->language->load('catalog/article');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/article');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_article->addArticle($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['filter_title'])) {
				$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
			}
		
			if (isset($this->request->get['filter_author'])) {
				$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
			}
		
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
					
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
							
			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
			}
							
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('catalog/article', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('catalog/article');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/article');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_article->editArticle($this->request->get['article_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['filter_title'])) {
				$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
			}
		
			if (isset($this->request->get['filter_author'])) {
				$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
			}
		
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
					
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
							
			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
			}
							
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('catalog/article', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}
 
	public function delete() {
		$this->language->load('catalog/article');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/article');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $article_id) {
				$this->model_catalog_article->deleteArticle($article_id);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['filter_title'])) {
				$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
			}
		
			if (isset($this->request->get['filter_author'])) {
				$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
			}
		
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
					
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
							
			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
			}
							
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('catalog/article', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

  	public function copy() {
    	$this->language->load('catalog/article');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/article');
		
		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $article_id) {
				$this->model_catalog_article->copyArticle($article_id);
	  		}

			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['filter_title'])) {
				$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
			}
		
			if (isset($this->request->get['filter_author'])) {
				$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
			}
		
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
					
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
							
			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
			}
							
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('catalog/article', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getList();
  	}
	
	protected function getList() {
		if (isset($this->request->get['filter_title'])) {
			$filter_title = $this->request->get['filter_title'];
		} else {
			$filter_title = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}		
		
		if (isset($this->request->get['filter_date_modified'])) {
			$filter_date_modified = $this->request->get['filter_date_modified'];
		} else {
			$filter_date_modified = null;
		}		
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'ad.title';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$url = '';
			
		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
						
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
						
		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}
						
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
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/article', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->link('catalog/article/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['copy'] = $this->url->link('catalog/article/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');	
		$this->data['delete'] = $this->url->link('catalog/article/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$this->data['articles'] = array();

		$data = array(
			'filter_title' 			=> $filter_title, 
			'filter_status' 		=> $filter_status,
			'filter_date_added' 	=> $filter_date_added,
			'filter_date_modified' 	=> $filter_date_modified,
			'sort' 					=> $sort,
			'order' 				=> $order,
			'start' 				=> ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' 				=> $this->config->get('config_admin_limit')
		);
		
		$this->load->model('tool/image');
		
		$article_total = $this->model_catalog_article->getTotalArticles();
	
		$results = $this->model_catalog_article->getArticles($data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/article/update', 'token=' . $this->session->data['token'] . '&article_id=' . $result['article_id'] . $url, 'SSL')
			);
						
			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
			}
	
			$this->data['articles'][] = array(
				'article_id' 		=> $result['article_id'],
				'image'				=> $image,
				'title'				=> $result['title'],
				'status'     		=> ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added'     	=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' 	=> date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'sort_order'		=> $result['sort_order'],
				'selected'			=> isset($this->request->post['selected']) && in_array($result['article_id'], $this->request->post['selected']),
				'action'			=> $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');		
		$this->data['text_disabled'] = $this->language->get('text_disabled');		
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_image'] = $this->language->get('column_image');		
		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_date_modified'] = $this->language->get('column_date_modified');		
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_copy'] = $this->language->get('button_copy');		
		$this->data['button_insert'] = $this->language->get('button_insert');		
		$this->data['button_delete'] = $this->language->get('button_delete');		
		$this->data['button_filter'] = $this->language->get('button_filter');
 
 		$this->data['token'] = $this->session->data['token'];
		
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

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
								
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
			
		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}
			
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_title'] = $this->url->link('catalog/article', 'token=' . $this->session->data['token'] . '&sort=ad.title' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('catalog/article', 'token=' . $this->session->data['token'] . '&sort=a.status' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('catalog/article', 'token=' . $this->session->data['token'] . '&sort=a.date_added' . $url, 'SSL');
		$this->data['sort_date_modified'] = $this->url->link('catalog/article', 'token=' . $this->session->data['token'] . '&sort=a.date_modified' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('catalog/article', 'token=' . $this->session->data['token'] . '&sort=a.sort_order' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
			
		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $article_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/article', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['filter_title'] = $filter_title;
		$this->data['filter_status'] = $filter_status;
		$this->data['filter_date_added'] = $filter_date_added;
		$this->data['filter_date_modified'] = $filter_date_modified;

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/article_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	protected function getForm() {
		$this->document->addScript('view/javascript/jquery/jquery.stringToSlug.min.js');

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
    	$this->data['text_select_all'] = $this->language->get('text_select_all');
    	$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');
    	$this->data['text_none'] = $this->language->get('text_none');
		
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_author'] = $this->language->get('entry_author');
		$this->data['entry_subject'] = $this->language->get('entry_subject');
		$this->data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_tag'] = $this->language->get('entry_tag');
    	$this->data['entry_category'] = $this->language->get('entry_category');
    	$this->data['entry_support'] = $this->language->get('entry_support');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_image'] = $this->language->get('entry_image');
    	$this->data['entry_filename'] = $this->language->get('entry_filename');
		$this->data['entry_mask'] = $this->language->get('entry_mask');
    	$this->data['entry_date_available'] = $this->language->get('entry_date_available');
    	$this->data['entry_date_added'] = $this->language->get('entry_date_added');
    	$this->data['entry_date_modified'] = $this->language->get('entry_date_modified');
		$this->data['entry_top'] = $this->language->get('entry_top');
		$this->data['entry_bottom'] = $this->language->get('entry_bottom');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_related'] = $this->language->get('entry_related');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
  		$this->data['button_upload'] = $this->language->get('button_upload');
    	
		$this->data['tab_general'] = $this->language->get('tab_general');
    	$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_design'] = $this->language->get('tab_design');

		$this->data['auto_seo'] = $this->config->get('auto_seo');

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
		
 		if (isset($this->error['subject'])) {
			$this->data['error_subject'] = $this->error['subject'];
		} else {
			$this->data['error_subject'] = array();
		}
		
	 	if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = array();
		}
		
  		if (isset($this->error['filename'])) {
			$this->data['error_filename'] = $this->error['filename'];
		} else {
			$this->data['error_filename'] = '';
		}
		
  		if (isset($this->error['mask'])) {
			$this->data['error_mask'] = $this->error['mask'];
		} else {
			$this->data['error_mask'] = '';
		}
				
		if (isset($this->error['date_available'])) {
			$this->data['error_date_available'] = $this->error['date_available'];
		} else {
			$this->data['error_date_available'] = '';
		}	

		$url = '';
			
		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
								
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

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
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/article', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		if (!isset($this->request->get['article_id'])) {
			$this->data['action'] = $this->url->link('catalog/article/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/article/update', 'token=' . $this->session->data['token'] . '&article_id=' . $this->request->get['article_id'] . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('catalog/article', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['article_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$article_info = $this->model_catalog_article->getArticle($this->request->get['article_id']);
		}
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['article_description'])) {
			$this->data['article_description'] = $this->request->post['article_description'];
		} elseif (isset($this->request->get['article_id'])) {
			$this->data['article_description'] = $this->model_catalog_article->getArticleDescriptions($this->request->get['article_id']);
		} else {
			$this->data['article_description'] = array();
		}

		$this->load->model('setting/store');
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		if (isset($this->request->post['article_store'])) {
			$this->data['article_store'] = $this->request->post['article_store'];
		} elseif (isset($this->request->get['article_id'])) {
			$this->data['article_store'] = $this->model_catalog_article->getArticleStores($this->request->get['article_id']);
		} else {
			$this->data['article_store'] = array(0);
		}		
		
		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($article_info)) {
			$this->data['keyword'] = $article_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}
		
		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (!empty($article_info)) {
			$this->data['image'] = $article_info['image'];
		} else {
			$this->data['image'] = '';
		}
		
		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && file_exists(DIR_IMAGE . $this->request->post['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($article_info) && $article_info['image'] && file_exists(DIR_IMAGE . $article_info['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($article_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		
    	if (isset($this->request->post['filename'])) {
    		$this->data['filename'] = $this->request->post['filename'];
    	} elseif (!empty($article_info)) {
      		$this->data['filename'] = $article_info['filename'];
		} else {
			$this->data['filename'] = '';
		}
		
    	if (isset($this->request->post['mask'])) {
    		$this->data['mask'] = $this->request->post['mask'];
    	} elseif (!empty($article_info)) {
      		$this->data['mask'] = $article_info['mask'];		
		} else {
			$this->data['mask'] = '';
		}
		
		if (isset($this->request->post['top'])) {
			$this->data['top'] = $this->request->post['top'];
		} elseif (!empty($article_info)) {
			$this->data['top'] = $article_info['top'];
		} else {
			$this->data['top'] = 0;
		}
		
		if (isset($this->request->post['bottom'])) {
			$this->data['bottom'] = $this->request->post['bottom'];
		} elseif (!empty($article_info)) {
			$this->data['bottom'] = $article_info['bottom'];
		} else {
			$this->data['bottom'] = 0;
		}
		
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($article_info)) {
			$this->data['status'] = $article_info['status'];
		} else {
			$this->data['status'] = 1;
		}
				
		if (isset($this->request->post['date_available'])) {
       		$this->data['date_available'] = $this->request->post['date_available'];
		} elseif (!empty($article_info)) {
			$this->data['date_available'] = date('Y-m-d', strtotime($article_info['date_available']));
		} else {
			$this->data['date_available'] = date('Y-m-d', time() - 86400);
		}
											
		if (isset($this->request->post['date_added'])) {
       		$this->data['date_added'] = $this->request->post['date_added'];
		} elseif (!empty($article_info)) {
			$this->data['date_added'] = $article_info['date_added'];
		} else {
			$this->data['date_added'] = '';
		}
											
		if (isset($this->request->post['date_modified'])) {
       		$this->data['date_modified'] = $this->request->post['date_modified'];
		} elseif (!empty($article_info)) {
			$this->data['date_modified'] = $article_info['date_modified'];
		} else {
			$this->data['date_modified'] = '';
		}
											
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($article_info)) {
			$this->data['sort_order'] = $article_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}

		// Author
    	if (isset($this->request->post['author'])) {
    		$this->data['author'] = $this->request->post['author'];
    	} elseif (!empty($article_info)) {
      		$this->data['author'] = $article_info['author'];
		} else {
			$this->data['author'] = '';
		}

		// Categories
		/*$this->load->model('catalog/article_category');

		if (isset($this->request->post['article_category'])) {
			$article_categories = $this->request->post['article_category'];
		} elseif (isset($this->request->get['article_id'])) {
			$article_categories = $this->model_catalog_article->getArticleCategories($this->request->get['article_id']);
		} else {
			$article_categories = array();
		}
	
		$this->data['article_categories'] = array();
		
		foreach ($article_categories as $article_category_id) {
			$article_category_info = $this->model_catalog_article_category->getArticleCategory($article_category_id);
			
			if ($article_category_info) {
				$this->data['article_categories'][] = array(
					'article_category_id' => $article_category_info['article_category_id'],
					'name'                => ($article_category_info['path'] ? $article_category_info['path'] . ' &gt; ' : '') . $article_category_info['name']
				);
			}
		}*/
		
		// Related
		$this->data['articles'] = $this->model_catalog_article->getArticles();
		
		if (isset($this->request->post['article_related'])) {
			$this->data['article_related'] = $this->request->post['article_related'];
		} elseif (isset($this->request->get['article_id'])) {
			$this->data['article_related'] = $this->model_catalog_article->getArticleRelated($this->request->get['article_id']);
		} else {
			$this->data['article_related'] = array();
		}		

		// Layout
		if (isset($this->request->post['article_layout'])) {
			$this->data['article_layout'] = $this->request->post['article_layout'];
		} elseif (isset($this->request->get['article_id'])) {
			$this->data['article_layout'] = $this->model_catalog_article->getArticleLayouts($this->request->get['article_id']);
		} else {
			$this->data['article_layout'] = array();
		}

		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
				
		$this->template = 'catalog/article_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/article')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['article_description'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 64)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}

			if (utf8_strlen($value['description']) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
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

  	protected function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'catalog/article')) {
      		$this->error['warning'] = $this->language->get('error_permission');  
    	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}
  	
  	protected function validateCopy() {
    	if (!$this->user->hasPermission('modify', 'catalog/article')) {
      		$this->error['warning'] = $this->language->get('error_permission');  
    	}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

	public function upload() {
		$this->language->load('catalog/article');

		$json = array();

		if (!$this->user->hasPermission('modify', 'catalog/article')) {
      		$json['error'] = $this->language->get('error_permission');
    	}	

		if (!isset($json['error'])) {	
			if (!empty($this->request->files['file']['name'])) {
				$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));
				
				if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 128)) {
					$json['error'] = $this->language->get('error_filename');
				}	  	
				
				if ($this->request->files['file']['size'] > 300000) {
					$json['error'] = $this->language->get('error_filesize');
				}
				
				// Allowed file mime types
				$allowed = array(
					'application/zip',
					'application/octet-stream',
					'application/x-rar-compressed'
				);
						
				if (!in_array($this->request->files['file']['type'], $allowed)) {
					$json['error'] = $this->language->get('error_filetype');
				}
				
				// Allowed file extension types		
				$allowed = array(
					'.zip',
					'.rar'
				);

				if (!in_array(strtolower(strrchr($filename, '.')), $allowed)) {
					$json['error'] = $this->language->get('error_filetype');
				}

				if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = 'error_upload_' . $this->request->files['file']['error'];
				}			
			} else {
				$json['error'] = $this->language->get('error_file');
			}
		}
		
		if (!isset($json['error'])) {
			if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
				$ext = $filename;
				$exts = md5($filename . '' . mt_rand());

				$json['filename'] = $ext;
				$json['mask'] = $exts;

				move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $filename);
			}

			$json['success'] = $this->language->get('text_upload');
		}	

		$this->response->setOutput(json_encode($json));
	}

	/*public function autocomplete() {
		$json = array();
		
		if (isset($this->request->get['filter_title']) || isset($this->request->get['filter_article_category_id'])) {
			$this->load->model('catalog/article');
			
			if (isset($this->request->get['filter_title'])) {
				$filter_title = $this->request->get['filter_title'];
			} else {
				$filter_title = '';
			}
			
			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];	
			} else {
				$limit = 20;	
			}			
						
			$data = array(
				'filter_title' => $filter_title,
				'start'        => 0,
				'limit'        => $limit
			);
			
			$results = $this->model_catalog_article->getArticles($data);
			
			foreach ($results as $result) {
				$json[] = array(
					'article_id' 	=> $result['article_id'],
					'title'         => html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8')
				);					
			}
		}

		$sort_order = array();
	  
		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['title'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->setOutput(json_encode($json));
	}*/
}
?>