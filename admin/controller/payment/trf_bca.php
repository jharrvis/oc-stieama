<?php 
class ControllerPaymentTrfBca extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/trf_bca');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
		
			$this->request->post['trf_bca_image'] = $this->language->get('logo_image');
			$this->request->post['trf_bca_title'] = $this->language->get('logo_title');
			$this->model_setting_setting->editSetting('trf_bca', $this->request->post);	
			
			$this->load->model('setting/bankaccounts');
			$this->model_setting_bankaccounts->refreshAccounts();
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));

		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_logo'] = $this->language->get('text_trf_bca');
		
		$this->data['entry_bank_name'] = $this->language->get('entry_bank_name');
		$this->data['entry_accountno'] = $this->language->get('entry_accountno');
		$this->data['entry_accountname'] = $this->language->get('entry_accountname');
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
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

 		if (isset($this->error['accountno'])) {
			$this->data['error_accountno'] = $this->error['accountno'];
		} else {
			$this->data['error_accountno'] = '';
		}

  		if (isset($this->error['accountname'])) {
			$this->data['error_accountname'] = $this->error['accountname'];
		} else {
			$this->data['error_accountname'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/trf_bca', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/trf_bca', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['trf_bca_accountno'])) {
			$this->data['trf_bca_accountno'] = $this->request->post['trf_bca_accountno'];
		} else {
			$this->data['trf_bca_accountno'] = $this->config->get('trf_bca_accountno');
		}
		
		if (isset($this->request->post['trf_bca_accountname'])) {
			$this->data['trf_bca_accountname'] = $this->request->post['trf_bca_accountname'];
		} else {
			$this->data['trf_bca_accountname'] = $this->config->get('trf_bca_accountname');
		}
		
		if (isset($this->request->post['trf_bca_total'])) {
			$this->data['trf_bca_total'] = $this->request->post['trf_bca_total'];
		} else {
			$this->data['trf_bca_total'] = $this->config->get('trf_bca_total'); 
		} 
				
		if (isset($this->request->post['trf_bca_order_status_id'])) {
			$this->data['trf_bca_order_status_id'] = $this->request->post['trf_bca_order_status_id'];
		} else {
			$this->data['trf_bca_order_status_id'] = $this->config->get('trf_bca_order_status_id'); 
		} 
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['trf_bca_geo_zone_id'])) {
			$this->data['trf_bca_geo_zone_id'] = $this->request->post['trf_bca_geo_zone_id'];
		} else {
			$this->data['trf_bca_geo_zone_id'] = $this->config->get('trf_bca_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['trf_bca_status'])) {
			$this->data['trf_bca_status'] = $this->request->post['trf_bca_status'];
		} else {
			$this->data['trf_bca_status'] = $this->config->get('trf_bca_status');
		}
		
		if (isset($this->request->post['trf_bca_sort_order'])) {
			$this->data['trf_bca_sort_order'] = $this->request->post['trf_bca_sort_order'];
		} else {
			$this->data['trf_bca_sort_order'] = $this->config->get('trf_bca_sort_order');
		}

		$this->template = 'payment/trf_bca.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/trf_bca')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['trf_bca_accountno']) {
			$this->error['accountno'] = $this->language->get('error_accountno');
		}

		if (!$this->request->post['trf_bca_accountname']) {
			$this->error['accountname'] = $this->language->get('error_accountname');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	
	public function uninstall() {
		$this->load->model('setting/bankaccounts');
		$this->model_setting_bankaccounts->refreshAccounts();
	}
}
?>
