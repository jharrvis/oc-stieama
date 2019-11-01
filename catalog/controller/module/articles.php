<?php  
######################################################################################
#
#  Articles for OpenCart. Developed by Tim Wills - Net-IT Web Consulting, Australia
#  Get articles written from as little as $1.90 from www.highqualitywriting.com
#  
######################################################################################
class ControllerModuleArticles extends Controller {
	protected function index($setting) {
		
		$this->load->model('module/articles');
		
		$this->document->addStyle('catalog/view/theme/default/stylesheet/articles.css');
		
		$this->data['columns']		= $setting['columns'];
		$this->data['position']		= $setting['position'];
		//var_dump($setting);
		
		$this->data['preferences'] = $this->config->get('articles_preferences');
		
		
		$this->data['articles'] = $this->model_module_articles->getArticles($this->data['preferences'], $setting['random']);
		//var_dump($this->data);
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/articles.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/articles.tpl';
		} else {
			$this->template = 'default/template/module/articles.tpl';
		}
		
		$this->render();
	}

}
?>