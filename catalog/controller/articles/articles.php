<?php 
######################################################################################
#
#  Articles for OpenCart. Developed by Tim Wills - Net-IT Web Consulting, Australia
#  Get articles written from as little as $1.90 from www.highqualitywriting.com
#  
######################################################################################
class ControllerArticlesArticles extends Controller {
	public function index() {  
    	$this->language->load('articles/articles');
		
		$this->load->model('module/articles');
		
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
		
		//preferences
		$preferences = $this->config->get('articles_preferences');
		
		//get articles
		$this->data['articles'] = $this->model_module_articles->getArticles($preferences);
   		
		
		$this->document->setTitle($preferences['title']); 
		
		$this->data['heading_title'] = $preferences['title'];
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/articles/articles.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/articles/articles.tpl';
		} else {
			$this->template = 'default/template/articles/articles.tpl';
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
?>