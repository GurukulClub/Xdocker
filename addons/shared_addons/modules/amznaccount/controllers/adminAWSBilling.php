<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 * @author  Xervmon Dev Team
 * @package Xervmon\Core\Modules\amznaccount\Controllers
 */
require_once (SHARED_ADDONPATH . 'libraries/Xervmon/CloudType.php');
class AdminAWSBilling extends Admin_Controller
{
	/** @var string The current active section */
	protected $section = 'accounts';

	/** @var array The validation rules */
	protected $validation_rules = array('name' => array('field' => 'name', 'label' => 'lang:global:title', 'rules' => 'trim|required|max_length[100]'), 'api_key' => array('field' => 'api_key', 'label' => 'lang:amznaccount.error_api_key', 'rules' => 'trim|required|max_length[100]'), 'secret_key' => array('field' => 'secret_key', 'label' => 'lang:amznaccount.error_secret_key', 'rules' => 'trim|required|max_length[100]'), 'account_id' => array('field' => 'account_id', 'label' => 'lang:amznaccount.error_account_id', 'rules' => 'trim|required|max_length[25]'), 'account_user' => array('field' => 'account_user', 'label' => 'lang:amznaccount.error_account_user', 'rules' => 'trim|max_length[25]'), 'password' => array('field' => 'password', 'label' => 'lang:amznaccount.error_password', 'rules' => 'trim|max_length[25]'), );

	/**
	 * Every time this controller controller is called should:
	 * - load the blog and blog_categories models
	 * - load the keywords and form validation libraries
	 * - set the hours, minutes and categories template variables.
	 */
	public function __construct()
	{
		parent::__construct();

		$this -> lang -> load(array('amznaccount'));
		$this -> lang -> load(array('awsbilling'));
		$this -> lang -> load(array('Cloud/Cloud'));
		$this -> load -> library('Cloud/ManageCloud');
		$this -> load -> model('awsbilling_m');
		$this -> template -> set('hours', array_combine($hours = range(0, 23), $hours)) -> set('minutes', array_combine($minutes = range(0, 59), $minutes));
		//->set('categories', $_categories)

	}

	public function index()
	{
		$this -> template -> append_js('module::awsbilling.js') -> append_css('bootstrap/daterangepicker.css') -> append_js('bootstrap/date.js') -> append_js('bootstrap/moment.js') -> append_js('bootstrap/daterangepicker.js') -> build('admin/spendAnalytics/index');
	}

	public function getRegions()
	{
		print json_encode(array('message' => $this -> managecloud -> getSupportedRegionCodeIIGen(CloudType::AWS_CLOUD)));
	}

	public function getData()
	{
		// Send AWS billing data between the selected dates
		$get = $this -> input -> get();
		$startDate = isset($get['startDate']) ? $get['startDate'] : date('yyyy-MM-dd');
		$endDate = isset($get['endDate']) ? $get['endDate'] : date('yyyy-MM-dd');
		print json_encode(array('message' => $this -> awsbilling_m -> get_all_between($startDate, $endDate), 'status' => true));
	}

}
