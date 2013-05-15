<?php

	class OAuth2 {
		static $login_attempts = 0;
		protected $client_id;
		protected $redirect_uri;
		protected $client_secret;
		protected $email;
		protected $password;
		public function __construct($client_id, $redirect_uri, $client_secret, $email, $password) {
			$this->client_id = $client_id;
			$this->redirect_uri = $redirect_uri;
			$this->client_secret = $client_secret;
			$this->email = $email;
			$this->password = $password;
		}
		public function login($content) {
			if (self::$login_attempts > 0) {
				die('can\'t login');
			}
			self::$login_attempts++;
			$parser = new HTML_Parser($content);
			$url = $parser->get_form_url();
			$data = $parser->get_hidden_fields();
			$data['Email'] = $this->email;
			$data['Passwd'] = $this->password;
			$data['signIn'] = 'Sign in';
			$data['PersistentCookie'] = 'yes';
			$curl = new CURL;
			return $curl->exec(
				$url,
				$data
			);
		}
		public function accept($content) {
			$parser = new HTML_Parser($content);
			$content = $parser->get_first_form();
			$url = $parser->get_form_url();
			$data = $parser->get_hidden_fields();
			$curl = new CURL;
			$curl->setopt(CURLOPT_HEADER, true);
			$content = $curl->exec(
				$url,
				$data
			);
			if (preg_match('/Location: [^\s]+\?(state=[^\s]+)/', $content, $matches)) {
				parse_str($matches[1], $params);
				$data = array(
					'code' => $params['code'],
					'client_id' => $this->client_id,
					'client_secret' => $this->client_secret,
					'redirect_uri' => $this->redirect_uri,
					'grant_type' => 'authorization_code',
				);
				$curl = new CURL;
				$content = $curl->exec(
					'https://accounts.google.com/o/oauth2/token',
					$data
				);
				return json_decode($content);
			} else {
				die('can\'t get token');
			}
		}
		public function get_token() {
			$url = 'https://accounts.google.com/o/oauth2/auth?'
				. 'scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile&'
				. 'state=%2Fprofile&'
				. 'redirect_uri=' . urlencode($this->redirect_uri) . '&'
				. 'response_type=code&'
				. 'client_id=' . $this->client_id . '&approval_prompt=force';

			$curl = new CURL;
			$content = $curl->exec($url);
			if (preg_match('/loginform/', $content)) {
				$content = $this->login($content);
			}
			return $this->accept($content);
		}
	}
