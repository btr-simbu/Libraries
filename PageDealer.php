<?php
/**
 * Created by PhpStorm.
 * User: Binary Tech Resonance Pvt. Ltd.
 * Date: 03-07-2018
 * Time: 10:28 AM
 */

trait PageDealer {
	public $current_page = '';
	public $page_title = 'Mlstudios';

	//SPECIAL USECASES - Not mandatory
	public $page_bg = '';
	public $page_head_title = '';

	public $meta_author = 'Binary Resonance';
	public $meta_title = 'Mlstudios';
	public $meta_description = 'Mlstudios - Wedding Photography, Candid Photography';
	public $meta_keywords = 'Mlstudios, photography';

	public function renderPageDetails(){

		$this->page_title = 'Mlstudios';

		$full_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$current = basename($_SERVER['PHP_SELF']);

		$parts = parse_url($full_url);

		if(isset($parts['query'])){
			parse_str($parts['query'], $query);
		}

		$this->current_page = $current;
		switch ($current){
			case 'index.php':
				$this->page_title .= ' | Welcome to Mlstudios';
				break;
			case 'about.php':
				//SPECIAL USECASES - Not mandatory
				$this->page_head_title = 'About Mlstudios';

				$this->page_title .= ' | About Mlstudios';
				break;
			case 'contact.php':
				$this->page_head_title = 'Contact Mlstudios';

				$this->page_title .= ' | Contact Us';
				break;
			case 'services.php':
				$this->page_head_title = 'Mlstudios Services';

				$this->page_title .= ' | Our Services';
				break;
			case '404.php':
				$this->page_bg = '404.jpg';
				$this->page_head_title = 'Oops!!! 404.';

				$this->page_title .= ' | Oops!!..404';
				break;
		}

		//For dynamic page uses only
		if(isset($query['_sep']) && $query['_sep'] == '1'){
			if(isset($query['_var']) && $query['_var'] != ''){
				$var = $query['_var'];
				switch ($var) {
					case 'hell':
						$this->page_title = 'FROM Query URL';
						$this->meta_title = 'Nothing to say about this concept.';
						break;
					case 'heaven':
						$this->page_title = 'Yea its Heaven';
						$this->meta_title = 'Nothing to say about this concept.';
						break;
				}
			}
		}
	}
}