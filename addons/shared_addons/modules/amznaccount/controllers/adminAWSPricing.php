<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 * @author  Xervmon Dev Team
 * @package Xervmon\Core\Modules\amznaccount\Controllers
 */
require_once (SHARED_ADDONPATH . 'libraries/Xervmon/CloudType.php');

require_once (SHARED_ADDONPATH . 'libraries/aws/EC2InstancePrices.php');
class AdminAWSPricing extends Admin_Controller
{
	/** @var string The current active section */
	protected $section = 'accounts';

	/**
	 * Every time this controller controller is called should:
	 * - load the blog and blog_categories models
	 * - load the keywords and form validation libraries
	 * - set the hours, minutes and categories template variables.
	 */
	public function __construct()
	{
		parent::__construct();

		$this -> lang -> load(array('Cloud/Cloud'));
		$this -> load -> library('Cloud/ManageCloud');
		$this -> template -> set('hours', array_combine($hours = range(0, 23), $hours)) -> set('minutes', array_combine($minutes = range(0, 59), $minutes));
		//->set('categories', $_categories)

	}

	public function index()
	{
		$ec2 = new EC2InstancePrices();
		// echo '<pre>';
		// print_r($ec2 -> get_ec2_reserved_instances_prices());
		// echo "\n<br/>=================================================\n<br/>";
		// print_r($ec2 -> get_ec2_ondemand_instances_prices());
		// echo "\n<br/>=================================================\n<br/>";
		//$ec2 -> get_ec2_data();
		$this -> template -> append_js('module::awspricing.js') -> set('reserved_instance_prices', $ec2 -> get_ec2_reserved_instances_prices()) -> set('ondemand_instance_prices', $ec2 -> get_ec2_ondemand_instances_prices()) -> build('admin/pricing/index');
	}

}
