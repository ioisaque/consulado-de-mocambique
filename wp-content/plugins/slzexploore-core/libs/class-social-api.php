<?php
/**
 * Social Api class.
 * 
 * @since 1.0
 */
class Slzexploore_Core_Social_Api {

	private $log_buffer = '';
	private $log_last_get_url_page_data = '';

	private $cache_var_name_last_val = 'social_api_v3_last_val';

	private $do_transient_save = false;

	public $sw_cache_last_val = array();

	function __construct() {
		$this->sw_cache_last_val = get_option($this->cache_var_name_last_val, '');
		$this->log(__FUNCTION__, 'New class instance');
	}
	
	private function in_cache($service_id, $user_id) {
		if (is_array($this->sw_cache_last_val) and isset($this->sw_cache_last_val[$service_id]['uid']) and $this->sw_cache_last_val[$service_id]['uid'] == $user_id and (time() - $this->sw_cache_last_val[$service_id]['timestamp'] < $this->sw_cache_last_val[$service_id]['expires']) ) {
			return true;
		}
		else {
			return false;
		}
	}

	private function get_cache($service_id, $user_id) {
		if (is_array($this->sw_cache_last_val) and isset($this->sw_cache_last_val[$service_id]['uid']) and $this->sw_cache_last_val[$service_id]['uid'] == $user_id) {
			return $this->sw_cache_last_val[$service_id];
		}
		else {
			return false;
		}
	}

	private function save_cache($service_id, $data) {
		if($this->do_transient_save == true) {
			$this->sw_cache_last_val[$service_id] = $data;
			update_option($this->cache_var_name_last_val, $this->sw_cache_last_val);
		}
	
		$this->do_transient_save = false;
	}

	public function get_url_wordpress($url) {
	
		$this->log(__FUNCTION__, "Fatching url: $url");
	
		$response = wp_remote_get($url, array(
			'decompress' => false,
			'timeout' => 10,
			'sslverify' => false,
			'user-agent' => 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0'
		));
	
		if (is_wp_error($response)) {
			$this->log(__FUNCTION__, 'got wp_error, get_error_message: ' . $response->get_error_message());
			return false;
		}
	
		if (wp_remote_retrieve_response_code($response) != 200) {
			$this->log(__FUNCTION__, 'Response code != 200: ' . wp_remote_retrieve_response_code($response));
		}
	
		$request_result = wp_remote_retrieve_body($response);
	
		if ($request_result == '') {
			$this->log(__FUNCTION__, 'Empty response via wp_remote_retrieve_body, Quitting.');
			return false;
		}
	
	
		$this->log_last_get_url_page_data = $request_result;
		return $request_result;
	}
	
	public function get_url_file_get_contents($url) {
		$this->log(__FUNCTION__, "Fatching url: $url");
	
		$opts = array(
			'http'=>array(
				'method'=>"GET",
				'header'=>"Accept-language: en\r\n" .
				"User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0\r\n"
			)
		);
		$context = stream_context_create($opts);
	
		$data = file_get_contents($url, false, $context);
	
		if (empty($data)) {
			$this->log(__FUNCTION__, 'file_get_contents returned empty result');
			return false;
		} else {
			return $data;
		}
	}
	
	private function get_url_curl($url) {
		$this->log(__FUNCTION__, "Fatching url: $url");
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch,  CURLOPT_FOLLOWLOCATION, true);
		curl_setopt ($ch,  CURLOPT_MAXREDIRS, 3); 
		curl_setopt ($ch,  CURLOPT_ENCODING, ''); 
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 10); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch,CURLOPT_AUTOREFERER,true); 
		curl_setopt($ch,CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0');
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$data = curl_exec($ch);
		$this->log(__FUNCTION__, 'curl_error: ' . curl_error($ch));
		$this->log(__FUNCTION__, 'curl_getinfo: ' . curl_getinfo($ch));
		$results['data'] = $data;
		curl_close($ch);
		return $data;
	}
	
	private function get_url($url) {
		return $this->get_url_wordpress($url);
	}
	
	private function get_json($url) {
		$json = json_decode($this->get_url($url), true);
		return $json;
	}
	
	private function extract_numbers_from_string($string) {
		$buffy = '';
		foreach (str_split($string) as $char) {
			if (is_numeric($char)) {
				$buffy .= $char;
			}
		}
		return $buffy;
	}
	
	public function get_social_counter($service_id, $user_id, $disable_cache = false) {
		$buffy_array = 0;
		$expl_maches = '';
	
		if ($this->in_cache($service_id, $user_id) === false or $disable_cache === true) {
	
			try {
				switch ($service_id) {
					case 'facebook':
						$sw_data = $this->get_url("http://facebook.com/$user_id");
						if ($sw_data === false) {
							$this->log(__FUNCTION__, 'The get_url method FAILED, returning 0');
							$buffy_array = 0;
							break;
						}
						$pattern = "/id=\"PagesLikesCountDOMID\">(.*?)<\/span><\/span>/i";
						preg_match_all($pattern, $sw_data, $matches);
						$sub_string = '';
						if(!empty($matches[1][0]))
							$sub_string = preg_replace('/class=\"(.*?)\"/i', '', $matches[1][0]);
						$buffy_array =  $this->extract_numbers_from_string($sub_string);
						break;
	
					case 'twitter':
						$twitter_worked = false;
						$sw_data = $this->get_url("https://twitter.com/$user_id");
						if ($sw_data === false) {
							$this->log(__FUNCTION__, 'The get_url method FAILED, we are trying again via the api');
						} else {
							$pattern = "/title=\"(.*)\"(.*)data-nav=\"followers\"/i";
							preg_match_all($pattern, $sw_data, $matches);
							if (!empty($matches[1][0])) {
								$buffer_counter_fix = $this->extract_numbers_from_string(htmlentities($matches[1][0]));
								$buffy_array = (int) $buffer_counter_fix;
								if (!empty($buffy_array) and is_numeric($buffy_array)) {
									$twitter_worked = true; //skip twitter second check it worked!
								}
							}
						}
						if ($twitter_worked === false) {
							if (!class_exists('TwitterApiClient')) {
								require_once 'twitter-client.php';
								$Client = new TwitterApiClient;
								$Client->set_oauth (YOUR_CONSUMER_KEY, YOUR_CONSUMER_SECRET, SOME_ACCESS_KEY, SOME_ACCESS_SECRET);
								try {
									$path = 'users/show';
									$args = array ('screen_name' => $user_id);
									$data = @$Client->call( $path, $args, 'GET' );
									if (!empty($data['followers_count'])) {
										$buffy_array = (int) $data['followers_count'];  //set the buffer
									}
								}
								catch( TwitterApiException $Ex ){}
							}
						}
						break;
	
					case 'vimeo':
						$sw_data = @$this->get_url("http://vimeo.com/$user_id");
						$pattern = "/<b class=\"stat_list_count\">(.*?)<\/b>(\s+)<span class=\"stat_list_label\">likes<\/span>/";
						preg_match($pattern, $sw_data, $matches);
						if (!empty($matches[1])) {
							$buffy_array = (int) $matches[1];
						}
						break;
	
					case 'youtube':
						$url = "https://www.googleapis.com/youtube/v3/channels?part=statistics&key=AIzaSyC7-Ilttw4gUKmJktPz-h9273s-CG-FhyI";
						$search_id = str_replace("channel/", "", $user_id);
						if (strpos($user_id, "channel/") === 0) {
							$url .= "&id=$search_id";
						} else {
							$url .= "&forUsername=$search_id";
						}
	
						$sw_data = @$this->get_json($url);
						$subscriberCount = @$sw_data['items'][0]['statistics']['subscriberCount'];
						if (!empty($subscriberCount)) {
							$buffy_array = (int) $subscriberCount;
						}
						break;
	
					case 'googleplus':
						$sw_data = @$this->get_json("https://www.googleapis.com/plus/v1/people/$user_id?key=AIzaSyDXiP0oA4L76DxCONYY5HIbFTS3D7LMiKE");
						if (!empty($sw_data['plusOneCount'])) {
							$buffy_array = (int) $sw_data['plusOneCount'];
						}else{
							$sw_data = @$this->get_url("https://plus.google.com/$user_id/posts");
							$pattern = "/<span role=\"button\" class=\"d-s o5a\" tabindex=\"0\">(.*?)<\/span>/";
							preg_match($pattern, $sw_data, $matches);
							if (!empty($matches[1])) {
								$expl_maches = explode(' ', trim($matches[1]));
								$buffy_array = str_replace(array('.', ','), array(''), $expl_maches[0]);
							}
						}
						break;
	
					case 'instagram':
						$sw_data = @$this->get_url("http://instagram.com/$user_id#");
						$pattern = "/followed_by\"\:\{\"count\"\:(.*?)\}\,\"/";
						preg_match($pattern, $sw_data, $matches);
						if (!empty($matches[1])) {
							$buffy_array = (int) $matches[1];
						}
						break;
	
					case 'soundcloud':
						$sw_data = @$this->get_json("http://api.soundcloud.com/users/$user_id.json?client_id=c76b89c45956170d07b51d33132aed24");
						if (!empty($sw_data['followers_count'])) {
							$buffy_array = (int) $sw_data['followers_count'];
						}
						break;
	
					case 'rss':
						$buffy_array = (int) $user_id;
						break;
				}
				if ($disable_cache === true) {
					return $buffy_array;
				}
				if ($buffy_array > 0) {
					$local_cash['count'] = $buffy_array;
					if($buffy_array > 0){
						$local_cash['ok_count'] = $buffy_array;
					}
					$local_cash['timestamp'] = time();
					$local_cash['expires'] = 10800;
					$local_cash['uid'] = $user_id;
		
					$this->do_transient_save = true;
		
					$this->save_cache($service_id, $local_cash);
				} else {
					$local_cash = $this->get_cache($service_id, $user_id);
	
					if (is_array($local_cash) and isset($local_cash['ok_count']) > 0) {
						$buffy_array = intval($local_cash['ok_count']);
					}else{
						$buffy_array = 0;
					}
	
					$local_cash['timestamp'] = time();
					$local_cash['count'] = 0;
					$local_cash['uid'] = $user_id;
					$local_cash['expires'] = 10800;
	
					$this->do_transient_save = true;
	
					$this->save_cache($service_id, $local_cash);
				}
			} catch (Exception $e) {
	
			}
			return $buffy_array;
		} else {
			$local_cash = $this->get_cache($service_id, $user_id);
			if (is_array($local_cash) and array_key_exists('ok_count', $local_cash)) {
				$this->log(__FUNCTION__, "$service_id - $user_id found in cache (ok_count): " . intval($local_cash['ok_count']));
				return intval($local_cash['ok_count']);
			} else {
				$this->log(__FUNCTION__, "$service_id - $user_id found in cache but the cache is empty or something");
				return 0;
			}
		}
	}

	private function log($function_name, $message) {
		$this->log_buffer .=  str_pad($function_name, 30, '.') . " - $message <br>";
	}

	function get_log() {
		return $this->log_buffer;
	}

	function get_log_last_get_url_page_data() {
		return $this->log_last_get_url_page_data;
	}
}