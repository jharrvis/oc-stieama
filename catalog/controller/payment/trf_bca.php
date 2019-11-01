<?php
class ControllerPaymentTrfBca extends Controller {
	protected function index() {
		$this->language->load('payment/trf_bca');
		
		$this->data['text_instruction'] = $this->language->get('text_instruction');
    	$this->data['text_accountno'] = $this->language->get('text_accountno');
		$this->data['text_accountname'] = $this->language->get('text_accountname');
		$this->data['text_detail1'] = $this->language->get('text_detail1');
		$this->data['text_detail2'] = $this->language->get('text_detail2');
		$this->data['text_logo'] = $this->language->get('text_logo');
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		
		$this->data['accountno'] = $this->config->get('trf_bca_accountno');
		$this->data['accountname'] = $this->config->get('trf_bca_accountname');

		$this->data['continue'] = $this->url->link('checkout/success');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/trf_bca.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/trf_bca.tpl';
		} else {
			$this->template = 'default/template/payment/trf_bca.tpl';
		}	
		
		$this->render(); 
	}
	
	public function confirm() {
		$this->language->load('payment/trf_bca');
		
		$this->load->model('checkout/order');
		
		$comment  = "<p><b>" . $this->language->get('text_title') . "</b><br/>" . $this->language->get('text_detail1') . "</p>";
		$comment .= "<p><b>" . $this->language->get('text_accountno') . "</b>" . $this->config->get('trf_bca_accountno') . "<br/>";
		$comment .= "<b>" . $this->language->get('text_accountname') . "</b>" . $this->config->get('trf_bca_accountname') . "</p>";
		$comment .= "<p>" . $this->language->get('text_detail2') . "</p>";
		
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('trf_bca_order_status_id'), $comment, true);
	}
}
?>