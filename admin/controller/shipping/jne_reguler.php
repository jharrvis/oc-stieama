<?php
class ControllerShippingJNEReguler extends Controller { 
	private $error = array();
	
	public function index() {  
		$this->load->language('shipping/jne_reguler');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				 
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('jne_reguler', $this->request->post);	

			$this->session->data['success'] = $this->language->get('text_success');
									
			$this->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_rate'] = $this->language->get('entry_rate');
		$this->data['entry_jne_reguler_class'] = $this->language->get('entry_jne_reguler_class');
		$this->data['entry_tax'] = $this->language->get('entry_tax');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_shipping'),
			'href'      => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('shipping/jne_reguler', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('shipping/jne_reguler', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'); 

		$this->load->model('localisation/geo_zone');
		
		$geo_zones = $this->model_localisation_geo_zone->getGeoZones();
		
		foreach ($geo_zones as $geo_zone) {
			if (isset($this->request->post['jne_reguler_' . $geo_zone['geo_zone_id'] . '_rate'])) {
				$this->data['jne_reguler_' . $geo_zone['geo_zone_id'] . '_rate'] = $this->request->post['jne_reguler_' . $geo_zone['geo_zone_id'] . '_rate'];
			} else {
				$this->data['jne_reguler_' . $geo_zone['geo_zone_id'] . '_rate'] = $this->config->get('jne_reguler_' . $geo_zone['geo_zone_id'] . '_rate');
			}		
			
			if (isset($this->request->post['jne_reguler_' . $geo_zone['geo_zone_id'] . '_status'])) {
				$this->data['jne_reguler_' . $geo_zone['geo_zone_id'] . '_status'] = $this->request->post['jne_reguler_' . $geo_zone['geo_zone_id'] . '_status'];
			} else {
				$this->data['jne_reguler_' . $geo_zone['geo_zone_id'] . '_status'] = $this->config->get('jne_reguler_' . $geo_zone['geo_zone_id'] . '_status');
			}		
		}
		
		$this->data['geo_zones'] = $geo_zones;

		if (isset($this->request->post['jne_reguler_tax_class_id'])) {
			$this->data['jne_reguler_tax_class_id'] = $this->request->post['jne_reguler_tax_class_id'];
		} else {
			$this->data['jne_reguler_tax_class_id'] = $this->config->get('jne_reguler_tax_class_id');
		}
		
		if (isset($this->request->post['jne_reguler_status'])) {
			$this->data['jne_reguler_status'] = $this->request->post['jne_reguler_status'];
		} else {
			$this->data['jne_reguler_status'] = $this->config->get('jne_reguler_status');
		}
		
		if (isset($this->request->post['jne_reguler_sort_order'])) {
			$this->data['jne_reguler_sort_order'] = $this->request->post['jne_reguler_sort_order'];
		} else {
			$this->data['jne_reguler_sort_order'] = $this->config->get('jne_reguler_sort_order');
		}	
		
		$this->load->model('localisation/tax_class');
				
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		$this->template = 'shipping/jne_reguler.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}
		
	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/jne_reguler')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>