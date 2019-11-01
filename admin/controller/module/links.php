<?php
class ControllerModuleLinks extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/links');

		#$this->document->title = $this->language->get('heading_title');
                #$this->document->title = "Kontak";
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			
			
			
			$this->model_setting_setting->editSetting('links', $this->request->post);								
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token']);
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_left'] = $this->language->get('text_left');
		$this->data['text_right'] = $this->language->get('text_right');
		
		$this->data['entry_limit'] = $this->language->get('entry_limit');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_url'] = $this->language->get('entry_url');
		$this->data['entry_alt'] = $this->language->get('entry_alt');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_module'),
      		'separator' => ' :: '
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=module/links&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=module/links&token=' . $this->session->data['token'];
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token'];

		$this->data['token'] = $this->session->data['token'];
		
		if (isset($this->request->post['links_limit'])) {
			$this->data['links_limit'] = $this->request->post['links_limit'];
		} else {
			$this->data['links_limit'] = $this->config->get('links_limit');
		}	
		
		 if(! $this->data['links_limit']){
		 	 $this->data['links_limit'] = 5;
		 }
		
		$this->data['positions'] = array();
		
		$this->data['positions'][] = array(
			'position' => 'left',
			'title'    => $this->language->get('text_left'),
		);
		
		$this->data['positions'][] = array(
			'position' => 'right',
			'title'    => $this->language->get('text_right'),
		);
		
		
		if (isset($this->request->post['links_position'])) {
			$this->data['links_position'] = $this->request->post['links_position'];
		} else {
			$this->data['links_position'] = $this->config->get('links_position');
		}
		
		if (isset($this->request->post['links_status'])) {
			$this->data['links_status'] = $this->request->post['links_status'];
		} else {
			$this->data['links_status'] = $this->config->get('links_status');
		}
		
		if (isset($this->request->post['links_sort_order'])) {
			$this->data['links_sort_order'] = $this->request->post['links_sort_order'];
		} else {
			$this->data['links_sort_order'] = $this->config->get('links_sort_order');
		}				
		
		
		$links_url = array();
		$links_alt = array();
		
		for ($i = 0; $i < 10; $i++){						
			if (isset($this->request->post['links_url'.$i])) {								
			  	$links_url[$i] = $this->request->post['links_url'.$i];
			  	$links_alt[$i] = $this->request->post['links_alt'.$i];
			} else {
				$links_url[$i] = $this->config->get('links_url'.$i);
				$links_alt[$i] = $this->config->get('links_alt'.$i);
			}
								
		}
		
                $this->data['links_url'] =   $links_url;
                $this->data['links_alt'] =   $links_alt;
                
			
		$this->template = 'module/links.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/links')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
	
	
	
	
}
?>
