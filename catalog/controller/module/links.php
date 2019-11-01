<?php
class ControllerModuleLinks extends Controller {
	protected function index() {
		$this->language->load('module/links');
		$this->load->model('tool/image');

      	$this->data['heading_title'] = $this->language->get('heading_title');
        $image = array();
        $alt = array();
        $url = array();
        
      	for($i = 0; $this->config->get('links_url'.$i); $i++){
      		
      		$this->data['alt'][$i] = $this->config->get('links_alt'.$i);
      		$this->data['url'][$i] = $this->config->get('links_url'.$i);
      	}
      	      	
		
		$this->id = 'links';

		if ($this->config->get('links_position') == 'home') {
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/links_home.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/links_home.tpl';
			} else {
				$this->template = 'default/template/module/links_home.tpl';
			}
		} else {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/links.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/links.tpl';
			} else {
				$this->template = 'default/template/module/links.tpl';
			}
		}

		$this->render();
	}
}
?>