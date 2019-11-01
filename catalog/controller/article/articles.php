<?php 
class ControllerArticleArticles extends Controller {
	private $error = array(); 
	
	public function index() {  
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}
		
    	$this->language->load('article/article');
		
		$this->load->model('catalog/article');
		
		$this->load->model('tool/image'); 

		$this->data['breadcrumbs'] = array();
		
      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	);
		
		$this->document->setTitle($this->language->get('heading_title'));

		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/article.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/article.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/article.css');
		}

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('article/articles'),      		
				'separator' => $this->language->get('text_separator')
			);

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_heading'] = sprintf($this->language->get('text_heading'), $this->config->get('config_name'));
		$this->data['text_by'] = $this->language->get('text_by');

		$this->data['button_readmore'] = $this->language->get('button_readmore');

			$this->data['articles'] = array();

			$article_total = $this->model_catalog_article->getTotalArticles(); 

			$results = $this->model_catalog_article->getArticles();

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('image_article_width'), $this->config->get('image_article_height'));
				} else {
					$image = false;
				}

				$this->data['articles'][] = array(
					'article_id'	=> $result['article_id'],
					'date_added1'	=> date('j', strtotime($result['date_added'])),
					'date_added2'	=> date('F Y', strtotime($result['date_added'])),
					'thumb'			=> $image,
					'title'			=> $result['title'],
					'author'		=> $result['author'],
					'description'	=> utf8_substr(strip_tags(html_entity_decode($result['subject'], ENT_QUOTES, 'UTF-8')), 0, 300) . '...',
					'href'			=> $this->url->link('article/article', 'article_id=' .  $result['article_id'])
				);
			}

			$this->data['limits'] = array();

			$limits = array_unique(array($this->config->get('config_catalog_limit'), 25, 50, 75, 100));

			sort($limits);

			foreach($limits as $value){
				$this->data['limits'][] = array(
					'text'  => $value,
					'value' => $value
				);
			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$pagination = new Pagination();
			$pagination->total = $article_total;
			$pagination->text = $this->language->get('text_pagination');

			$this->data['pagination'] = $pagination->render();

			$this->data['continue'] = $this->url->link('common/home');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/article/articles.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/article/articles.tpl';
			} else {
				$this->template = 'default/template/article/articles.tpl';
			}
			
			$this->children = array(
				'common/header',
				'common/content_top',
				'common/column_left',
				'common/column_right',
				'common/content_bottom',
				'common/footer'
			);
						
			$this->response->setOutput($this->render());
    }
}
?>