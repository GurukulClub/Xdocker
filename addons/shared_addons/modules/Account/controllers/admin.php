<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 * @author  Xervmon Dev Team
 * @package Xervmon\Core\Modules\amznaccount\Controllers
 */
require_once SHARED_ADDONPATH . 'libraries/aws/aws.phar';
 
require_once (SHARED_ADDONPATH . 'helpers/StringHelper.php');

require_once (SHARED_ADDONPATH . 'libraries/Xervmon/CloudType.php');
require_once SHARED_ADDONPATH . "libraries/XervmonMongoQB.php";
require_once (SHARED_ADDONPATH . 'libraries/MongoQB/src/MongoQB/Builder.php');

use Aws\S3\S3Client;
class Admin extends Admin_Controller
{
	/** @var string The current active section */
	protected $section = 'accounts';
	private $phpmongoClient;
	private $_ci;
	
	static $dbh;

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

		$this -> lang -> load(array('Account'));
		$this -> load -> config('Account/Account');
		$this -> phpmongoClient = new XervmonMongoQB();
		//$this -> phpmongoClient = $this -> phpmongoClient -> getMongoQB();
		
	}

	/**
	 * Show all created blog posts
	 */
	public function index()
	{
		$get = $this->input->get();
		
		$total_rows = $this -> phpmongoClient->getTotalRows($this->current_user, $this->section);
		$pagination = create_pagination('admin/account/index', $total_rows);
		$pageSize = Settings::get('records_per_page');
		//$collection, $select, $where = array(), $limit = 25, $offset = 0
		$accounts = $this -> phpmongoClient -> getData($this->current_user, $this->section, array(), $pageSize, isset($get['offset']) ? $get['offset'] : 0);
		//$accountArr = array();
		foreach($accounts as $account)
		{
			//$account -> api_key = StringHelper::decrypt($account -> api_key ,md5($this->current_user->username));
			//$accountArr[] = $account;
		}
		//do we need to unset the layout because the request is ajax?
		$this -> input -> is_ajax_request() and $this -> template -> set_layout(false);

		//if($this->input->is_ajax_request()) echo 'eajax'; else echo 'no ajax';
		$this -> template -> title($this -> module_details['name']) -> append_js('admin/filter.js') -> set_partial('filters', 'admin/partials/filters') -> set('pageSize', $pageSize) -> set('pagination', $pagination) -> set('accounts', $accounts);

		$this -> input -> is_ajax_request() ? $this -> template -> build('admin/tables/list') : $this -> template -> build('admin/index');
				
	}

	/**
	 * Create new amznaccount Account
	 */
	public function create()
	{
		role_or_die('account', 'add_account');
		$post = new stdClass();
		$post -> name = '';
		$post -> cloudProviders = config_item('cloudProviders');
		
		$this -> template -> title($this -> module_details['name'], lang('Account:create_title')) -> set('post', $post) -> append_css('common/form.css') -> append_css('common/style.css') -> append_css('common/select2.css') ->  append_js('jquery/jquery.validate.js') -> append_js('script/jquery/select2.js') -> append_js('script/script.js') -> append_js('jquery/jquery.validate.unobtrusive.js') -> append_js('jquery/jquery.validate.bootstrap.js') -> append_js('module::account.js') -> build('admin/form');
		
	}
	
	public function add()
	{
		$this->create();
	}

	/**
	 * Edit amznaccount Account
	 *
	 * @param int $id The ID of the blog post to edit
	 */
	public function edit($id = 0)
	{
		$id OR redirect('admin/Account/Manage');
		role_or_die('account', 'edit_account');
	}

	

	/**
	 * Delete blog post
	 *
	 * @param int $id The ID of the blog post to delete
	 */
	public function delete($id = 0)
	{
		$id OR redirect('admin/Account/Manage');
		role_or_die('account', 'delete_account');

	}
	
	public function saveData()
	{
		
	}
	
	public function getFields($provider)
	{
		
	}

}
