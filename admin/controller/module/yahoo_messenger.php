<?php
class ControllerModuleYahooMessenger extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/yahoo_messenger');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('yahoo_messenger', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		$this->data['text_view_row'] = $this->language->get('text_view_row');
		$this->data['text_view_col'] = $this->language->get('text_view_col');
		
		$this->data['entry_code'] = $this->language->get('entry_code');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_opi_img'] = $this->language->get('entry_opi_img');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_list_view'] = $this->language->get('entry_list_view');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
 		if (isset($this->error['code'])) {
			$this->data['error_code'] = $this->error['code'];
		} else {
			$this->data['error_code'] = '';
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_module'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('module/yahoo_messenger', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/yahoo_messenger', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['yahoo_messenger_code'])) {
			$this->data['yahoo_messenger_code'] = $this->request->post['yahoo_messenger_code'];
		} else {
			$this->data['yahoo_messenger_code'] = $this->config->get('yahoo_messenger_code');
		}	
		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['yahoo_messenger_module'])) {
			$this->data['modules'] = $this->request->post['yahoo_messenger_module'];
		} elseif ($this->config->get('yahoo_messenger_module')) { 
			$this->data['modules'] = $this->config->get('yahoo_messenger_module');
		}
		
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->template = 'module/yahoo_messenger.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
		
		$this->response->setOutput($this->render());
		
		/*
		if (isset($this->request->post['yahoo_messenger_opi_code'])) {
			$this->data['yahoo_messenger_opi_code'] = $this->request->post['yahoo_messenger_opi_code'];
		} else {
			$this->data['yahoo_messenger_opi_code'] = $this->config->get('yahoo_messenger_opi_code');
		}
		
		if (isset($this->request->post['yahoo_messenger_position'])) {
			$this->data['yahoo_messenger_position'] = $this->request->post['yahoo_messenger_position'];
		} else {
			$this->data['yahoo_messenger_position'] = $this->config->get('yahoo_messenger_position');
		}
		
		if (isset($this->request->post['yahoo_messenger_status'])) {
			$this->data['yahoo_messenger_status'] = $this->request->post['yahoo_messenger_status'];
		} else {
			$this->data['yahoo_messenger_status'] = $this->config->get('yahoo_messenger_status');
		}
		
		if (isset($this->request->post['yahoo_messenger_sort_order'])) {
			$this->data['yahoo_messenger_sort_order'] = $this->request->post['yahoo_messenger_sort_order'];
		} else {
			$this->data['yahoo_messenger_sort_order'] = $this->config->get('yahoo_messenger_sort_order');
		}				
		
		$this->template = 'module/yahoo_messenger.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		*/
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/yahoo_messenger')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['yahoo_messenger_code']) {
			$this->error['code'] = $this->language->get('error_code');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>
