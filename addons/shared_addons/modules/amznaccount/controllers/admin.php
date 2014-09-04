<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 * @author  Xervmon Dev Team
 * @package Xervmon\Core\Modules\amznaccount\Controllers
 */
require_once SHARED_ADDONPATH . 'libraries/aws/aws.phar';
require_once (SHARED_ADDONPATH .'libraries/Xervmon/CloudType.php');
require_once SHARED_ADDONPATH . "libraries/XervmonMongoQB.php";
require_once (SHARED_ADDONPATH . 'libraries/MongoQB/src/MongoQB/Builder.php');
require_once (SHARED_ADDONPATH . 'helpers/StringHelper.php');

use Aws\S3\S3Client;

class Admin extends Admin_Controller
{
	/** @var string The current active section */
	protected $section = 'accounts';
	private $phpmongoClient;
	private $_ci;

	/** @var array The validation rules */
	protected $validation_rules = array('name' => array('field' => 'name', 'label' => 'lang:global:title', 'rules' => 'trim|required|max_length[100]'), 'api_key' => array('field' => 'api_key', 'label' => 'lang:amznaccount.error_api_key', 'rules' => 'trim|required|max_length[100]'), 'secret_key' => array('field' => 'secret_key', 'label' => 'lang:amznaccount.error_secret_key', 'rules' => 'trim|required|max_length[100]'), 'account_id' => array('field' => 'account_id', 'label' => 'lang:amznaccount.error_account_id', 'rules' => 'trim|required|max_length[25]'), 'bucket_name' => array('field' => 'bucket_name', 'label' => 'lang:amznaccount.error_bucket_name', 'rules' => 'trim|max_length[100]'), );

	/**
	 * Every time this controller controller is called should:
	 * - load the blog and blog_categories models
	 * - load the keywords and form validation libraries
	 * - set the hours, minutes and categories template variables.
	 */
	public function __construct()
	{
		$this->_ci = ci();
		parent::__construct();

		$this -> lang -> load(array('amznaccount'));
		$this -> load -> config('amznaccount/amznaccount');
		
		$this -> phpmongoClient = new XervmonMongoQB();
		$this -> phpmongoClient = $this -> phpmongoClient -> getMongoQB();
		
		$this -> template -> set('hours', array_combine($hours = range(0, 23), $hours)) -> set('minutes', array_combine($minutes = range(0, 59), $minutes));
	}

	/**
	 * Show all created blog posts
	 */
	public function index()
	{
		$accounts = $this->phpmongoClient ->where( array('userid' => $this->current_user->id, 'cloudProvider' => CloudType::AWS_CLOUD) ) -> get($this->section);
	
		$accountArr = array();
		foreach($accounts as $account)
		{
			$apiKey = StringHelper::decrypt($account['apiKey'] ,md5($this->current_user->username));
			$accountArr[] = $account;
		}
		//do we need to unset the layout because the request is ajax?
		$this -> input -> is_ajax_request() and $this -> template -> set_layout(false);

		$this -> template -> title($this -> module_details['name']) 
						  -> append_js('admin/filter.js') 
						  -> set_partial('filters', 'admin/partials/filters') 
						 -> set('accounts', $accountArr);
	}

	

}
