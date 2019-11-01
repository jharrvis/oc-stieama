<?php  
class ControllerModuleBankAccounts extends Controller {
	protected function index() {
		$this->language->load('module/bank_accounts');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
    	
		$this->data['text_accountno'] = $this->language->get('text_accountno');
    	$this->data['text_accountname'] = $this->language->get('text_accountname');
    	$this->data['text_error'] = $this->language->get('text_error');
    	
		$this->load->model('setting/setting');
		$setting = $this->model_setting_setting->getSetting('bank_accounts_common');
		
		$this->data['accounts'] = $setting['accounts'];

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/bank_accounts.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/bank_accounts.tpl';
		} else {
			$this->template = 'default/template/module/bank_accounts.tpl';
		}
		
		$this->render();
	}
}
?>