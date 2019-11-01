<?php 
######################################################################################
#
#  Articles for OpenCart. Developed by Tim Wills - Net-IT Web Consulting, Australia
#  Get articles written from as little as $1.90 from www.highqualitywriting.com
#  
######################################################################################
class ControllerArticlesAuthor extends Controller {
	public function index() {  
    	$this->language->load('articles/articles');
		
		$this->load->model('module/articles');
		
		//$this->document->addStyle('catalog/view/theme/default/stylesheet/article.css');
		
		//breadcrumbs
		$this->data['breadcrumbs'] = array();
		
      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_title'),
			'href'      => $this->url->link('articles/articles'),
			'separator' => $this->language->get('text_separator')
		);
		
		//get author
		if (isset($this->request->get['author_id'])) {
			$author_id = (int)$this->request->get['author_id'];
		} else {
			$author_id = 0;
		}
		
		$author_info = $this->model_module_articles->getAuthor($author_id);
   		
		if ($author_info) {
	  		$this->document->setTitle($author_info['name']); 
			
      		$this->data['breadcrumbs'][] = array(
        		'text'      => $author_info['name'],
				'href'      => $this->url->link('articles/author', 'author_id=' .  $author_id),      		
        		'separator' => $this->language->get('text_separator')
      		);
			
			
			
			$this->data['author_name']		= $author_info['name'];
      		$this->data['googleid']			= $author_info['googleid'];
      		$this->data['image']			= $author_info['image'];
			$this->data['long_bio']			= html_entity_decode($author_info['long_bio'], ENT_QUOTES, 'UTF-8');
			$this->data['links']			= $this->model_module_articles->getLinks($author_info['author_id']);
			
			$this->data['text_about']		= $this->language->get('text_about');
			$this->data['text_connect']		= str_replace('[name]', $author_info['name'], $this->language->get('text_connect'));
			
			$this->load->model('tool/image');

			if ($author_info['image']) {
				$this->data['image'] = $this->model_tool_image->resize($author_info['image'], 250, 250);
			} else {
				$this->data['image'] = '';
			}
			
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/articles/author.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/articles/author.tpl';
			} else {
				$this->template = 'default/template/articles/author.tpl';
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
    	} else {
      		$this->data['breadcrumbs'][] = array(
        		'text'      => $this->language->get('text_error'),
				'href'      => $this->url->link('articles/author', 'author_id=' . $author_id),
        		'separator' => $this->language->get('text_separator')
      		);
			
	  		$this->document->setTitle($this->language->get('text_error'));
			
      		$this->data['heading_title']	= $this->language->get('text_error');
      		$this->data['text_error']		= $this->language->get('text_error');
      		$this->data['button_continue']	= $this->language->get('button_continue');
      		$this->data['continue']			= $this->url->link('common/home');
			
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