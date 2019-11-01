<?php
class ControllerModuleArticle extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/article');

		$this->document->setTitle($this->language->get('title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('article', $this->request->post);		

			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');

		$this->data['entry_auto_seo'] = $this->language->get('entry_auto_seo');
		$this->data['entry_in_menubar'] = $this->language->get('entry_in_menubar');
		$this->data['entry_image_article'] = $this->language->get('entry_image_article');
		$this->data['entry_image_related'] = $this->language->get('entry_image_related');
		$this->data['entry_facebook_comment'] = $this->language->get('entry_facebook_comment');
		$this->data['entry_facebook_apikey'] = $this->language->get('entry_facebook_apikey');
		$this->data['entry_facebook_apisecret'] = $this->language->get('entry_facebook_apisecret');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
 		if (isset($this->error['image_article'])) {
			$this->data['error_image_article'] = $this->error['image_article'];
		} else {
			$this->data['error_image_article'] = '';
		}
				
 		if (isset($this->error['article_related'])) {
			$this->data['error_article_related'] = $this->error['article_related'];
		} else {
			$this->data['error_article_related'] = '';
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
       		'text'      => $this->language->get('title'),
			'href'      => $this->url->link('module/article', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/article', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['auto_seo'])) {
			$this->data['auto_seo'] = $this->request->post['auto_seo'];
		} else {
			$this->data['auto_seo'] = $this->config->get('auto_seo');
		}

		if (isset($this->request->post['menubar'])) {
			$this->data['menubar'] = $this->request->post['menubar'];
		} else {
			$this->data['menubar'] = $this->config->get('menubar');
		}

		if (isset($this->request->post['image_article_width'])) {
			$this->data['image_article_width'] = $this->request->post['image_article_width'];
		} else {
			$this->data['image_article_width'] = $this->config->get('image_article_width');
		}
		
		if (isset($this->request->post['image_article_height'])) {
			$this->data['image_article_height'] = $this->request->post['image_article_height'];
		} else {
			$this->data['image_article_height'] = $this->config->get('image_article_height');
		}

		if (isset($this->request->post['article_related_width'])) {
			$this->data['article_related_width'] = $this->request->post['article_related_width'];
		} else {
			$this->data['article_related_width'] = $this->config->get('article_related_width');
		}
		
		if (isset($this->request->post['article_related_height'])) {
			$this->data['article_related_height'] = $this->request->post['article_related_height'];
		} else {
			$this->data['article_related_height'] = $this->config->get('article_related_height');
		}

		if (isset($this->request->post['facebook_apikey'])) {
			$this->data['facebook_apikey'] = $this->request->post['facebook_apikey'];
		} else {
			$this->data['facebook_apikey'] = $this->config->get('facebook_apikey');
		}
		
		if (isset($this->request->post['facebook_apisecret'])) {
			$this->data['facebook_apisecret'] = $this->request->post['facebook_apisecret'];
		} else {
			$this->data['facebook_apisecret'] = $this->config->get('facebook_apisecret');
		}
		
		if (isset($this->request->post['facebook_comment'])) {
			$this->data['facebook_comment'] = $this->request->post['facebook_comment'];
		} else {
			$this->data['facebook_comment'] = $this->config->get('facebook_comment');
		}
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->template = 'module/article.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/article')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['image_article_width'] || !$this->request->post['image_article_height']) {
			$this->error['image_article'] = $this->language->get('error_image_article');
		} 

		if (!$this->request->post['article_related_width'] || !$this->request->post['article_related_height']) {
			$this->error['article_related'] = $this->language->get('error_article_related');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}

	public function install() {
		$this->load->model('catalog/article');
		$this->model_catalog_article->createDatabaseTables();
	}

	public function uninstall() {
		$this->load->model('catalog/article');
		$this->model_catalog_article->dropDatabaseTables();
	}
}
?>