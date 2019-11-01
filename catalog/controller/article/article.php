<?php 
class ControllerArticleArticle extends Controller {
	private $error = array(); 
	
	public function index() {  
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}
		
    	$this->language->load('article/article');
		
		$this->load->model('catalog/article');
		
		$this->load->model('tool/image'); 

		$this->data['breadcrumbs'] = array();
		
      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	);
		
      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_articles'),
			'href'      => $this->url->link('article/articles'),      		
        	'separator' => $this->language->get('text_separator')
      	);
		
		if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
			$url = '';
			
			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}
						
			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}
						
			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
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
						
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
						
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_search'),
				'href'      => $this->url->link('article/search', $url),
				'separator' => $this->language->get('text_separator')
			); 	
		}
		
		if (isset($this->request->get['article_id'])) {
			$article_id = (int)$this->request->get['article_id'];
		} else {
			$article_id = 0;
		}
		
		$this->load->model('catalog/article');
		
		$article_info = $this->model_catalog_article->getArticle($article_id);

		if ($article_info) {
			$url = '';
			
			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}
			
			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}
						
			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}
			
			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
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
						
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
												
			$this->data['breadcrumbs'][] = array(
				'text'      => $article_info['title'],
				'href'      => $this->url->link('article/article', $url . '&article_id=' . $this->request->get['article_id']),
				'separator' => $this->language->get('text_separator')
			);			
			
			$next 	= $this->model_catalog_article->getarticle($article_id+1);
			$prev 	= $this->model_catalog_article->getarticle($article_id-1);

				if($next):
					$this->data['next_url'] = $this->url->link('article/article', 'article_id=' .  $next['article_id']);
					$this->data['next']= $next['title'];
				else:
					$this->data['next_url'] = '';
					$this->data['next']= '';
				endif;

				if($prev):
					$this->data['prev_url'] = $this->url->link('article/article', 'article_id=' .  $prev['article_id']);
					$this->data['prev']= $prev['title'];
				else:
					$this->data['prev_url'] = '';
					$this->data['prev']= null;
				endif;
			
			$this->document->setTitle($article_info['title']);
			$this->document->setDescription($article_info['meta_description']);
			$this->document->setKeywords($article_info['meta_keyword']);
			$this->document->addLink($this->url->link('article/article', 'article_id=' . $this->request->get['article_id']), 'canonical');

			if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/article.css')) {
				$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/article.css');
			} else {
				$this->document->addStyle('catalog/view/theme/default/stylesheet/article.css');
			}
		
      		$this->data['heading_title'] = $article_info['title'];
      		
			$this->data['text_advertisement'] = $this->language->get('text_advertisement');
			$this->data['text_tags'] = $this->language->get('text_tags');
			$this->data['text_by'] = $this->language->get('text_by');
			$this->data['text_previous'] = $this->language->get('text_previous');
			$this->data['text_next'] = $this->language->get('text_next');
			$this->data['text_related_articles'] = $this->language->get('text_related_articles');
			$this->data['text_social_links'] = $this->language->get('text_social_links');
			$this->data['text_stay_connected'] = $this->language->get('text_stay_connected');
			
			$this->data['button_download'] = $this->language->get('button_download');
			
			$this->data['article_id'] = $this->request->get['article_id'];
			
			$this->load->model('tool/image');

			$this->data['description'] = html_entity_decode($article_info['description'], ENT_QUOTES, 'UTF-8');
			$this->data['subject'] = html_entity_decode($article_info['subject'], ENT_QUOTES, 'UTF-8');
			$this->data['image'] = $this->model_tool_image->resize($article_info['image'], $this->config->get('image_article_width'), $this->config->get('image_article_height'));
			$this->data['date_added'] = date('F j, Y', strtotime($article_info['date_added']));
			$this->data['author'] = $article_info['author'];
			$this->data['facebook_apikey'] = $this->config->get('facebook_apikey');
			$this->data['current_url'] = $this->url->link('article/article', 'article_id=' . $this->request->get['article_id']);

			if ($this->config->get('facebook_comment') == '1') {
				$this->data['facebook_comment'] = $this->config->get('facebook_comment');
			} else {
				$this->data['facebook_comment'] = '';
			}
		
			if ($article_info['filename']) {
				$this->data['filename'] = $server . 'download/' . $article_info['filename'];
			} else {
				$this->data['filename'] = '';
			}
			
			$this->data['articles'] = array();

				$this->data['articles'][] = array(
					'article_id' => $article_info['article_id'],
					'subject'    => $article_info['subject'],
					'title'    	 => $article_info['title'],
					'filename'	 => $server . 'download/' . $article_info['filename'],
					'href'    	 => $this->url->link('article/article', 'article_id=' . $article_info['article_id'])
				);
			
			$this->data['tags'] = array();
			
			if ($article_info['tag']) {		
				$tags = explode(',', $article_info['tag']);
				
				foreach ($tags as $tag) {
					$this->data['tags'][] = array(
						'tag'  => trim($tag),
						'href' => $this->url->link('article/search', 'tag=' . trim($tag))
					);
				}
			}
			
			$this->model_catalog_article->updateViewed($this->request->get['article_id']);
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/article/article.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/article/article.tpl';
			} else {
				$this->template = 'default/template/article/article.tpl';
			}
			
			$this->children = array(
				'common/header',
				'common/content_top',
				'common/column_left',
				'common/column_right',
				'common/content_bottom',
				'common/footer'
			);
						
			$this->response->setOutput($this->render());
		} else {
			$url = '';
			
			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}
			
			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}	
			
			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}	
					
			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}
							
			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
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
						
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
								
      		$this->data['breadcrumbs'][] = array(
        		'text'      => $this->language->get('text_error'),
				'href'      => $this->url->link('article/article', $url . '&article_id=' . $article_id),
        		'separator' => $this->language->get('text_separator')
      		);			
		
			$this->document->setTitle($this->language->get('text_error'));

      		$this->data['heading_title'] = $this->language->get('text_error');

      		$this->data['text_error'] = $this->language->get('text_error');

      		$this->data['button_continue'] = $this->language->get('button_continue');
      		$this->data['button_back'] = $this->language->get('button_back');

      		$this->data['continue'] = $this->url->link('common/home');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
			}
			
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
						
			$this->response->setOutput($this->render());
    	}
  	}
}
?>