<?php  
class ControllerModuleYahooMessenger extends Controller {
	protected function index($setting) {
		//print_r($setting);
		$this->language->load('module/yahoo_messenger');

      	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$ymarray = explode(",", $this->config->get('yahoo_messenger_code'));
		$this->data['ymcode'] = "";
		foreach($ymarray as $ymid){
			$this->data['ymcode'] .= '<a href="ymsgr:sendim?'.trim($ymid).'" border="0"><img src="http://opi.yahoo.com/online?u='.trim($ymid).'&t='.$setting['image'].'" border="0"></a>';
			if($setting['listview']=="0")
				$this->data['ymcode'] .= "<br />";
			else
				$this->data['ymcode'] .= "&nbsp;&nbsp;";
		}
		//$this->id = 'yahoo_messenger';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/yahoo_messenger.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/yahoo_messenger.tpl';
		} else {
			$this->template = 'default/template/module/yahoo_messenger.tpl';
		}
		
		$this->render();
	}
}
?>