<?php 
######################################################################################
#
#  Articles for OpenCart. Developed by Tim Wills - Net-IT Web Consulting, Australia
#  Get articles written from as little as $1.90 from www.highqualitywriting.com
#  
######################################################################################
class ControllerArticlesArticle extends Controller {
	public function index() {  
    	$this->language->load('articles/articles');
		
		$this->load->model('module/articles');

		$this->document->addStyle('catalog/view/theme/default/stylesheet/article.css');
		
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
		
		//get article
		if (isset($this->request->get['article_id'])) {
			$article_id = (int)$this->request->get['article_id'];
		} else {
			$article_id = 0;
		}
		
		$articles_info = $this->model_module_articles->getArticle($article_id);
   		
		if ($articles_info) {
	  		$this->document->setTitle($articles_info['title']); 
			
			
			//get preferences and set default if blank
			$preferences = $this->config->get('articles_preferences');
			$dir = $this->model_module_articles->getDir($preferences);
			if(!isset($preferences['img_width'])  || !is_numeric($preferences['img_width']))					$preferences['img_width']	= 250;
			if(!isset($preferences['img_height']) || !is_numeric($preferences['img_height']))					$preferences['img_height']	= 400;
			if(!isset($preferences['img_pos'])	  || !in_array($preferences['img_pos'],array('left','right')))	$preferences['img_pos']		= 'right';
			if(!isset($preferences['contextual']))																$preferences['contextual']	= false;
			//var_dump($preferences);
			
			
      		$this->data['breadcrumbs'][] = array(
        		'text'      => $articles_info['title'],
				//'href'      => $this->url->link('articles/article', 'article_id=' .  $article_id),      		
				'href'      => $this->url->link('articles/article', $dir.'article_id=' .  $article_id),      		
        		'separator' => $this->language->get('text_separator')
      		);
			
			
      		$this->data['heading_title']	= $articles_info['title'];
      		$this->data['date_added']		= $articles_info['date_added'];
			$this->data['description']		= html_entity_decode($articles_info['description'], ENT_QUOTES, 'UTF-8');
      		$this->data['img_pos']			= $preferences['img_pos'];
			
			$this->load->model('tool/image');

			if ($articles_info['image']) {
				$this->data['image'] = $this->model_tool_image->resize($articles_info['image'], $preferences['img_width'], $preferences['img_height']);
			} else {
				$this->data['image'] = '';
			}
			
			
			//contextual links
			if($preferences['contextual']) {
				//define the contextual patterns/replacement data
				$this->model_module_articles->SetContextualLinks($articles_info['article_id'], $dir);
				//Add the contextual links to the article body
				$this->data['description'] = $this->model_module_articles->addContextualLinks($this->data['description']);
			}
			
			//author
			$author_info = $this->model_module_articles->getAuthor($articles_info['author_id']);
			if(isset($author_info['name'])) {
				$this->data['author_name']		= $author_info['name'];
				$this->data['author_googleid']	= $author_info['googleid'];
				//$this->data['author_image']		= $author_info['image'];
				$this->data['author_short_bio']	= html_entity_decode($author_info['short_bio'], ENT_QUOTES, 'UTF-8');
				
				if(strlen(trim($author_info['long_bio']))>8) {
					$this->data['author_bio_url'] = $this->url->link('articles/author', 'author_id=' . $author_info['author_id']);
				} else {
					$this->data['author_bio_url'] = '';
				}
				
				$this->data['links']			= $this->model_module_articles->getLinks($author_info['author_id']);
				
				if ($author_info['image']) {
					$this->data['author_image'] = $this->model_tool_image->resize($author_info['image'], 150, 150);
				} else {
					$this->data['author_image'] = '';
				}
				
				$this->data['text_about']		= $this->language->get('text_about');
				$this->data['text_connect']		= str_replace('[name]', $author_info['name'], $this->language->get('text_connect'));
			}
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/articles/article.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/articles/article.tpl';
			} else {
				$this->template = 'default/template/articles/article.tpl';
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
				'href'      => $this->url->link('articles/article', $dir.'article_id=' . $article_id),
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