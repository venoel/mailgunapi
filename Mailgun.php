<?php
class Mailgun extends CApplicationComponent{

	/**
	 * @var boolean Use test or real domain to send email.
	 */
	public $useSandbox = false;

	/**
	 * @var string URL of sandbox domain.
	 */
	public $sandboxUrl = '';

	/**
	 * @var string URL of real domain
	 */
	public $url = '';

	/**
	 * @var string API Key
	 * @see https://app.mailgun.com/app/domains/<yourdomainname>
	 */
	public $apikey = '';

	/**
	 *	@var array Error info
	 */
	protected $status;


	/**
	 * Initialize configuration
	 */
	public function init(){
		if ( $this->useSandbox ){
			if ( empty($this->sandboxUrl) ){
				throw new CException('Configuration parameter "sandboxUrl" should be set.');
			}
			$this->url = $this->sandboxUrl;
		}
		if ( empty($this->apikey) ) {
			throw new CException('Configuration parameter "apikey" should be set.');
		}
		if ( empty($this->url) ) {
			throw new CException('Configuration parameter "url" should be set.');
		}
		parent::init();
	}

	/**
	 * Send email through API
	 * @param string $from E-mail. Example: "MYSITE <admin@mysite.com>"
	 * @param string $to E-mail.
	 * @param string $subject Subject of e-mail.
	 * @param string $body E-mail body with HTML content.
	 */
	public function send($from,$to,$subject,$body){
		$data = array(
			'from' => $from,
			'to' => $to,
			'subject' => $subject,
			'html' => $body
		);
		$headers = array(
			"Authorization: Basic " . base64_encode('api:'.$this->apikey)
		);

		$ch = curl_init($this->url.'/messages');

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$jsonResponse = curl_exec($ch);
		if ( $jsonResponse === false ) {
			$this->status = array(
				'code' => curl_errno($ch),
				'message' => curl_error($ch)
			);
			$result = false;
		}
		else {
			$response = json_decode($jsonResponse);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$result = false;

			if ( !empty($response->message) ){
				$this->status = array(
					'code' => $http_code,
					'message' => $response->message
				);
			} else {
				$this->status = array(
					'code' => $http_code,
					'message' => $jsonResponse
				);
			}
			if ( $http_code === 200 && !empty($response->message) ){
				$result = true;
			}
		}
		return $result;
	}

	/**
	 * Get status of last request.
	 *
	 * @return array Array with keys 'code' and 'message' of last request.
	 */
	public function getStatus(){
		return $this->status;
	}

	/**
	 * Get current config parameters.
	 */
	public function getConfig(){
		return array(
			'url' => $this->url,
			'useSandbox' => $this->useSandbox,
			'apikey' => $this->apikey
		);
	}


}
