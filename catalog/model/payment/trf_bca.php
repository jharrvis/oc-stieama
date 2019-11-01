<?php 
class ModelPaymentTrfBca extends Model {
  	public function getMethod($address, $total) {
		$this->load->language('payment/trf_bca');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('trf_bca_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if ($this->config->get('trf_bca_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('trf_bca_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}	
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => 'trf_bca',
        		'title'      => $this->language->get('text_title'),
        		'info'      => 
					'<p>' . $this->language->get('text_logo') . '<br/>' .
					'<b>' . $this->language->get('text_accountno') . '</b>' . $this->config->get('trf_bca_accountno') . '<br/>' .
					'<b>' . $this->language->get('text_accountname') . '</b>' . $this->config->get('trf_bca_accountname') . '</p>',
				'sort_order' => $this->config->get('trf_bca_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
}
?>