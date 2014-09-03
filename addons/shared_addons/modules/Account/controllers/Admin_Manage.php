<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 * @author  Xervmon Dev Team
 * @package Xervmon\Core\Modules\amznaccount\Controllers
 */
require_once SHARED_ADDONPATH . 'libraries/aws/aws.phar';
 
require_once (SHARED_ADDONPATH . 'helpers/StringHelper.php');
require_once (SHARED_ADDONPATH . 'libraries/Xervmon/CloudType.php');
require_once (SHARED_ADDONPATH . 'libraries/Xervmon/MongodbFactory.php');

use Aws\S3\S3Client;
class Admin_Manage extends Admin_Controller
{
	/** @var string The current active section */
	protected $section = 'accounts';
	private $phpmongoClient;
	private $_ci;

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
	}

	/**
	 * Show all created blog posts
	 */
	public function index()
	{
		
	}

	/**
	 * Create new amznaccount Account
	 */
	public function create()
	{
		role_or_die('account', 'add_account');
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
