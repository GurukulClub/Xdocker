<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 * @author  Xervmon Dev Team
 * @package Xervmon\Core\Modules\amznaccount\Controllers
 */
require_once (SHARED_ADDONPATH . 'libraries/Xervmon/CloudType.php');
require_once SHARED_ADDONPATH . 'libraries/aws/aws.phar';
require_once SHARED_ADDONPATH . "libraries/XervmonMongoQB.php";
require_once (SHARED_ADDONPATH . 'libraries/MongoQB/src/MongoQB/Builder.php');
require_once (SHARED_ADDONPATH . 'helpers/StringHelper.php');
//require_once 'config.php';

use Aws\S3\S3Client;
class Admin extends Admin_Controller
{
	/** @var string The current active section */
	protected $section = 'accounts';
	private $phpmongoClient;
	private $_config_file = 'mongoqb';
	public $_customerIdentifier;
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

		$this -> load -> model(array('amznaccount_m'));
		$this -> load -> model(array('business_metadata/page_extn_m'));
		$this -> load -> model(array('business_metadata/unique_id_m'));
		$this -> lang -> load(array('amznaccount'));
		$this -> load -> config('amznaccount/amznaccount');
		$this -> load -> library('Cloud/CloudFactoryAdapter');
		$this -> lang -> load(array('Cloud/Cloud'));
		$this -> phpmongoClient = new XervmonMongoQB();
		$this -> phpmongoClient = $this -> phpmongoClient -> getMongoQB();
		$this->_customerIdentifier = XervmonMongoQB::getCustomerIdentifier();
		
		$this -> template -> set('hours', array_combine($hours = range(0, 23), $hours)) -> set('minutes', array_combine($minutes = range(0, 59), $minutes));
		//->set('categories', $_categories)

	}

	/**
	 * Show all created blog posts
	 */
	public function index()
	{
		$base_where = '';

		if ($this -> input -> post('f_keywords'))
		{
			$base_where = $this -> input -> post('f_keywords');
			$rackaccount = $this -> amznaccount_m -> get_many_by($base_where);
		}

		// Create pagination links
		$total_rows = $this -> amznaccount_m -> count_by($base_where);
		$pagination = create_pagination('admin/amznaccount/index', $total_rows);
		$pageSize = Settings::get('records_per_page');
		

		// Using this data, get the relevant results
		$accounts = $this -> amznaccount_m -> limit($pagination['limit']) -> get_many_by($base_where);
		
		$accountArr = array();
		foreach($accounts as $account)
		{
			$account -> api_key = StringHelper::decrypt($account -> api_key ,md5($this->_customerIdentifier));
			$accountArr[] = $account;
		}
		$helpLinks = $this -> page_extn_m -> get_many_by($params = array('slug' => 'amznaccount'));
		//do we need to unset the layout because the request is ajax?
		$this -> input -> is_ajax_request() and $this -> template -> set_layout(false);

		//if($this->input->is_ajax_request()) echo 'eajax'; else echo 'no ajax';
		$this -> template -> title($this -> module_details['name']) -> append_js('admin/filter.js') -> set_partial('filters', 'admin/partials/filters') -> set('pageSize', $pageSize) -> set('pagination', $pagination) -> set('helpLinks', $helpLinks) -> set('accounts', $accountArr);

		$this -> input -> is_ajax_request() ? $this -> template -> build('admin/tables/list') : $this -> template -> build('admin/index');
		//print_r($this->template);

	}

	/**
	 * Create new amznaccount Account
	 */
	public function create()
	{
		role_or_die('amznaccount', 'add_amznaccount');
		$post = new stdClass();
		$post -> name = '';
		$post -> account_id = '';
		$post -> api_key = '';
		$post -> secret_key = '';
		$post -> bucket_name = '';
		$helpLinks = $this -> page_extn_m -> get_many_by($params = array('slug' => 'amznaccount'));
		$this -> template -> title($this -> module_details['name'], lang('amznaccount:create_title')) -> set('post', $post) -> append_css('bootstrap/bootstrap.css') -> append_css('bootstrap/bootstrap-responsive.css') -> append_css('common/form.css') -> append_css('common/style.css') -> append_css('common/select2.css') -> set('helpLinks', $helpLinks) -> append_js('jquery/jquery.validate.js') -> append_js('script/jquery/select2.js') -> append_js('script/script.js') -> append_js('jquery/jquery.validate.unobtrusive.js') -> append_js('jquery/jquery.validate.bootstrap.js') -> append_js('pnotify/jquery.pnotify.min.js') -> append_css('pnotify/jquery.pnotify.default.css') -> append_js('module::amznaccount.js') -> build('admin/form');
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
		$id OR redirect('admin/amznaccount');
		role_or_die('amznaccount', 'edit_amznaccount');
		$helpLinks = $this -> page_extn_m -> get_many_by($params = array('slug' => 'amznaccount'));
		$post = $this -> amznaccount_m -> get($id);
		$post -> api_key = StringHelper::decrypt($post -> api_key, md5($this->_customerIdentifier ));
		$post -> secret_key = StringHelper::decrypt($post -> secret_key, md5($this->_customerIdentifier ));

		$post -> id = $id;
		$this -> template -> title($this -> module_details['name'], sprintf(lang('amznaccount:edit_title'), $post -> name)) -> set('post', $post) -> set('helpLinks', $helpLinks) -> append_js('jquery/jquery.validate.js') -> append_css('bootstrap/bootstrap.css') -> append_css('bootstrap/bootstrap-responsive.css') -> append_css('common/form.css') -> append_js('jquery/jquery.validate.unobtrusive.js') -> append_js('jquery/jquery.validate.bootstrap.js') -> append_js('pnotify/jquery.pnotify.min.js') -> append_css('pnotify/jquery.pnotify.default.css') -> append_js('module::amznaccount.js') -> append_js('script/script.js') -> append_css('common/style.css') -> append_js('script/jquery/select2.js') -> build('admin/form');
	}

	/**
	 * Helper method to determine what to do with selected items from form post
	 */
	public function action()
	{
		switch ($this->input->post('btnAction'))
		{
			case 'publish' :
				$this -> publish();
				break;

			case 'delete' :
				$this -> delete();
				break;

			default :
				redirect('admin/amznaccount');
				break;
		}
	}

	/**
	 * Delete blog post
	 *
	 * @param int $id The ID of the blog post to delete
	 */
	public function delete($id = 0)
	{
		role_or_die('amznaccount', 'delete_amznaccount');

		// Delete one
		$ids = ($id) ? array($id) : $this -> input -> post('action_to');

		// Go through the array of slugs to delete
		if (!empty($ids))
		{
			$post_titles = array();
			$deleted_ids = array();
			foreach ($ids as $id)
			{
				// Get the current page so we can grab the id too
				if ($post = $this -> amznaccount_m -> get($id))
				{
					if ($this -> amznaccount_m -> delete($id))
					{
						//$this->comment_m->where('module', 'blog')->delete_by('module_id', $id);

						// Wipe cache for this model, the content has changed
						$this -> pyrocache -> delete('amznaccount_m');
						$post_titles[] = $post -> name;
						$deleted_ids[] = $id;
					}
				}
			}
			//$this -> schedule($deleted_ids, "delete");
			$accountArray['cloudAccountId'] = $id;
			$this -> schedule($accountArray, "delete");
			// Fire an event. We've deleted one or more accounts.
			Events::trigger('amznaccount_deleted', $deleted_ids);
			$this -> triggerEvent($post_titles, 'amznaccount_deleted');
			//	Events::trigger('amznaccount_account_deleted', $deleted_ids);
		}

		// Some pages have been deleted
		if (!empty($post_titles))
		{
			// Only deleting one page
			if (count($post_titles) == 1)
			{
				$this -> session -> set_flashdata('success', sprintf($this -> lang -> line('amznaccount:delete_success'), $post_titles[0]));
			}
			// Deleting multiple pages
			else
			{
				$this -> session -> set_flashdata('success', sprintf($this -> lang -> line('amznaccount:mass_delete_success'), implode('", "', $post_titles)));
			}
		}
		// For some reason, none of them were deleted
		else
		{
			$this -> session -> set_flashdata('notice', lang('amznaccount:delete_error'));
		}

		redirect('admin/amznaccount');
	}

	private function triggerEvent($post, $eventType, $optional = '')
	{
		$notifySetting = $this -> settings_m -> get(array('slug' => 'send_email'));
		$notifyEmail = $this -> settings_m -> get(array('slug' => 'xervmon_admin'));
		log_message('info', 'debug:' . SITE_REF . ':' . json_encode($notifySetting));

		if ($notifySetting -> value == 1)
		{
			log_message('info', 'debug:' . SITE_REF . ':' . $notifySetting -> value);

			$arr = explode(',', $notifySetting -> value);
			log_message('info', 'debug:' . SITE_REF . ':' . json_encode($arr));
			foreach ($arr as $notifyRecepient)
			{
				log_message('info', ' debug: ' . SITE_REF . ':' . json_encode($arr));
				if(empty($post['account_id']) || empty($post['api_key']) || empty($post['secret_key']))
				{
					Events::trigger('email', array('name' => $this -> current_user -> display_name, 'account_id'=>json_encode($post), 'slug' => $eventType, 'to' => $notifyEmail-> value), 'array');
						
				}
				else {	
					Events::trigger('email', array('name' => $this -> current_user -> display_name, 'account_id' => $post['account_id'], 'api_key' => $post['api_key'], 'secret_key' => $post['secret_key'], 'slug' => $eventType, 'to' => $notifyEmail-> value), 'array');
				}
			}
		} 
		else
		{
			log_message('ERROR', 'ERROR:' . SITE_REF . ': Notify setting not set.');
		}

		if (!empty($optional))
		{
			$user = $this -> user_m -> get(array('id' => $optional));
			Events::trigger('email', array('name' => $this -> current_user -> display_name, 'account_id' => $post['account_id'], 'api_key' => $post['api_key'], 'secret_key' => $post['secret_key'], 'slug' => lang('rackaccount:rackaccount_assigned_template'), 'to' => $user -> email), 'array');

		}
		// IF assinged to user/group is set - then send users assigned to the group

	}

	/**
	 *	Saves data from create and edit
	 */
	public function saveData()
	{
		$from = null;
		$to = null;
		$post = new stdClass();

		$this -> form_validation -> set_rules($this -> validation_rules);
		$id = $this -> input -> post('id');
		
		$scm = $this -> input -> post('support_cost_management') ? '1' : '0';
		$bucket_name = $this -> input -> post('bucket_name') ? $this -> input -> post('bucket_name') : '';
		$api_key = StringHelper::encrypt($this -> input -> post('api_key') ,md5($this->_customerIdentifier));
		$secret_key = StringHelper::encrypt($this -> input -> post('secret_key') ,md5($this->_customerIdentifier));

		if ($scm)
		{
			$this -> form_validation -> set_rules('bucket_name', lang('amznaccount:bucket_name'), 'required');
		}

		$message = '';

		$accountArray = array('name' => $this -> input -> post('name'), 'cloud_provider' => CloudType::AWS_CLOUD, 'api_key' => $api_key, 'secret_key' => $secret_key, 'account_id' => $this -> input -> post('account_id'), 'support_cost_management' => $scm, 'bucket_name' => $bucket_name, 'created_on' => now(), 'user_id' => $this -> current_user -> id, 'created_by' => $this -> current_user -> id, );
		$id = $this -> input -> post('id');
		if (empty($id))
		{
			//Do validation and insert
			// Merge and set our validation rules
			$amznaccountValidation = array_merge($this -> validation_rules, array('name' => array('field' => 'name', 'label' => 'lang:global:title', 'rules' => 'trim|required|max_length[100]|callback__check_title'), 'api_key' => array('field' => 'api_key', 'label' => 'lang:amznaccount.error_api_key', 'rules' => 'trim|required|max_length[100]|callback__check_username'), 'account_id' => array('field' => 'account_id', 'label' => 'lang:amznaccount.error_account_id', 'rules' => 'trim|required|max_length[25]|callback__check_account_id'), ));
			$this -> form_validation -> set_rules(array_merge($this -> validation_rules, $amznaccountValidation));
			if ($this -> form_validation -> run())
			{
				if ($this -> _checkCloudConnection($accountArray) == FALSE)
				{
					$message = array('status' => 'error', 'status_msg' => lang('amznaccount:authcheck_message'));
					print json_encode($message);
					//This should be in language
					return;
				}

				$message = $this -> simpleInsert($accountArray);
				$cloudAccountId =  json_decode($message) -> id;
				$accountArray['cloudAccountId'] = $cloudAccountId;
				$this -> schedule($accountArray, "insert");
				log_message('info', __FILE__ . '->' . __FUNCTION__ . ' Insert Data inserted to Scheduler ');
			} else
			{
				$errors = validation_errors();
				$message = json_encode(array('status' => 'error', 'status_msg' => $errors));
			}

		} else
		{
			//$this->form_validation->set_rules($amznaccountValidation);
			if ($this -> form_validation -> run())
			{
				if ($this -> _checkCloudConnection($accountArray) == FALSE)
				{
					$message = array('status' => 'error', 'status_msg' => lang('amznaccount:authcheck_message'));
					print json_encode($message);
					//This should be in language
					return;
				}

				$message = $this -> simpleUpdate($id, $accountArray);
				$accountArray['cloudAccountId'] = $id;
				$this -> schedule($accountArray, "update");
				log_message('info', __FILE__ . '->' . __FUNCTION__ . ' Update Data inserted to Scheduler ');
			} else
			{
				$errors = validation_errors();
				$message = json_encode(array('status' => 'error', 'status_msg' => $errors));
			}
		}
		print $message;
		return;
	}

	private function schedule($accountArray, $mode)
	{

		$from = strtotime("now");
		$to = strtotime("now");
		if (isset($accountArray['api_key']) && isset($accountArray['secret_key']))
		{
			$url = site_url() . '/daemons/aws_billing/cronjob.php?awsKey=' . $accountArray['api_key'] . '&awsSecret=' . $accountArray['secret_key'] . '&accountId=' . $accountArray['account_id'] . '&bucket=' . $accountArray['bucket_name'] . '&from=' . $from . '&to=' . $to . '&cloudAccountId=' . $accountArray['cloudAccountId'] . '';
			$arrayValues['cloudAccountId'] = $accountArray['cloudAccountId'];
			$arrayValues['cloudProvider'] = CloudType::AWS_CLOUD;
			$StrReplace = str_replace("index.php/", "", $url);
			$arrayValues['URL'] = $StrReplace;
			$arrayValues['status'] = 'New';
			$config = $this -> config -> item('mongoqb');
			$arrayValues['customerIdentifier'] = $config['customer_identifier'];
			
			$arrayValues = array_merge($arrayValues, $accountArray);
			$arrayValues['requestType'] = 'Billing';
			$arrayValues['db'] = array(
									   'host'       =>  StringHelper::encrypt ($this->db->hostname, md5($config['customer_identifier'])),
									   'remote_db_link'       =>  StringHelper::encrypt (ci()->config -> item('remote_db_link'), md5($config['customer_identifier'])),
									   'database'   =>  StringHelper::encrypt ($this->db->database, md5($config['customer_identifier'])),
									   'prefix'     =>  StringHelper::encrypt ($this->db->dbprefix, md5($config['customer_identifier'])),
									   'username'   =>  StringHelper::encrypt ($this->db->username, md5($config['customer_identifier'])),
									   'password'   =>  StringHelper::encrypt ($this->db->password, md5($config['customer_identifier'])),
									   );
		}

		switch($mode)
		{
			case 'insert' : $this ->phpmongoClient->insert('scheduler',$arrayValues ); break;
			case 'update' : $this ->phpmongoClient->where(array('cloudAccountId' 

                => intval($accountArray['cloudAccountId'])))

                ->set($arrayValues)

                ->update ('scheduler',$arrayValues ); break;

			case 'delete' :
				$this -> phpmongoClient -> where(array('cloudAccountId' => intval($accountArray['cloudAccountId']))) -> delete('scheduler');
				break;
		}
	}

	private function simpleInsert($accountArray, $uniqueId = '')
	{
		try
		{
			$uniqueId = $this -> amznaccount_m -> insert($accountArray);
			//print_r($uniqueId); die();
			$this -> pyrocache -> delete_all('amznaccount_m');
			$message = array('id' => $uniqueId, 'status' => 'success', 'status_msg' => sprintf($this -> lang -> line('amznaccount:post_add_success'), $accountArray['name']));
			Events::trigger('amznaccount_created', $uniqueId);
			$this -> triggerEvent($accountArray, 'amznaccount_created');
			//	Events::trigger('amznaccount_account_created', $uniqueId); //Need this for events later
		} catch(Exception $ex)
		{
			$message = array('status' => 'error', 'status_msg' => lang('amznaccount:post_add_error'));
		}
		return json_encode($message);
	}

	private function simpleUpdate($id, $accountArray)
	{
		$message = '';
		$accountArray['updated_on'] = now();
		$accountArray['updated_by'] = $this -> current_user -> id;
		if ($ret = $this -> amznaccount_m -> update($id, $accountArray))
		{
			$this -> pyrocache -> delete_all('amznaccount_m');
			$message = array('status' => 'success', 'status_msg' => sprintf($this -> lang -> line('amznaccount:post_edit_success'), $accountArray['name']));

			Events::trigger('amznaccount_updated', $id);
			$this -> triggerEvent($accountArray, 'amznaccount_updated');
			//	Events::trigger('amznaccount_account_edited', $id); //Need this for events later
		} else
		{
			$message = array('status' => 'error', 'status_msg' => lang('amznaccount:post_edit_error'));
		}
		return json_encode($message);
	}

	public function upload()
	{
		$post = new stdClass();

		$this -> form_validation -> set_rules($this -> pem_validation_rules);

		if ($this -> input -> post('created_on'))
		{
			$created_on = strtotime(sprintf('%s %s:%s', $this -> input -> post('created_on'), $this -> input -> post('created_on_hour'), $this -> input -> post('created_on_minute')));
		} else
		{
			$created_on = now();
		}
		$allAccounts = $this -> amznaccount_m -> getAccounts();
		$this -> template -> title($this -> module_details['name'], lang('amznaccount:upload')) -> set('accounts', $allAccounts) -> set('post', $post) -> append_js('jquery/jquery.validate.js') -> append_js('jquery/jquery.validate.unobtrusive.js') -> append_js('jquery/jquery.validate.bootstrap.js') -> append_js('bootstrap/bootstrap-fileupload.min.js') -> append_css('bootstrap/bootstrap-fileupload.min.css') -> append_js('bootstrap/bootstrap-fileupload.min.js')
		// -> append_js('module::amznUploadForm.js')
		-> build('admin/uploadForm');
	}

	public function ajaxUploadKey()
	{
		//File uploaded should be saved under uploads/
		//upload file - mime type should be application/x-x509-user-cert
		//The key should be able to used to connect to servers.

	}

	/**
	 * Callback method that checks the title of an post
	 *
	 * @param string $title or $name The Title to check
	 * @param string $id
	 *
	 * @return bool
	 */
	public function _check_title($title, $id = null)
	{
		$this -> form_validation -> set_message('_check_title', sprintf(lang('amznaccount:already_exist_error'), lang('global:title')));

		return $this -> amznaccount_m -> check_exists('name', $title, $id);
	}

	public function _check_account_id($account_id, $id = null)
	{
		$this -> form_validation -> set_message('_check_account_id', sprintf(lang('amznaccount:already_exist_error'), lang('global:title')));

		return $this -> amznaccount_m -> check_exists('account_id', $account_id, $id);

	}

	public function _check_username($api_key, $id = null)
	{
		$this -> form_validation -> set_message('_check_username', lang('amznaccount:already_exist_error'));
		return $this -> amznaccount_m -> check_exists('api_key', $api_key, $id);

	}

	/**
	 * Generate a preview hash
	 *
	 * @return bool
	 */
	private function _preview_hash()
	{
		return md5(microtime() + mt_rand(0, 1000));
	}

	private function _checkCloudConnection($accountArray)
	{
		try
		{
			$account = json_decode(json_encode($accountArray), FALSE);
			$this -> adapter = CloudFactoryAdapter::instance($account);
			$this -> adapter -> setDB($this -> db);
			$this -> adapter -> setGeo('', 'us-east-1');
			$auth = $this -> adapter -> authenticate($accountArray);
			$bucket_exists = false;
			if ($auth)
			{
				if (isset($accountArray['support_cost_management']) && ($accountArray['support_cost_management']) && !empty($accountArray['bucket_name']))
				{
					log_message('debug', __FILE__ . ' / ' . __FUNCTION__ . ' Auth success. Checking bucket ' . $accountArray['bucket_name']);
					$client = S3Client::factory(array('key' => $accountArray['api_key'], 'secret' => $accountArray['secret_key'], 'region' => 'us-east-1'));

					log_message('debug', __FILE__ . ' / ' . __FUNCTION__ . ' Auth success. Init S3Client ');
					if ($client -> doesBucketExist($accountArray['bucket_name']))
					{
						$bucket_exists = true;
						log_message('info', __FILE__ . ' / ' . __FUNCTION__ . 'AWS Account :' . $accountArray['bucket_name'] . ' exists ');
						return $auth && $bucket_exists;

					} else
					{
						log_message('info', __FILE__ . ' / ' . __FUNCTION__ . ' AWS Account :' . $accountArray['bucket_name'] . ' does not exist ');
						return $auth && $bucket_exists;
					}
				} else
				{
					log_message('info', __FILE__ . ' / ' . __FUNCTION__ . 'Valid AWS Account :Auth successful');
					log_message('info', 'Cost management not enabled ' . json_encode($accountArray));
					return $auth;
				}

			} else
			{
				log_message('error', __FILE__ . ' /' . __FUNCTION__ . ' Auth Failure ' . json_encode($accountArray));
				return false;
			}
		} catch(Exception $e)
		{
			log_message('error', 'Connection failed with API and Secret -input .' . json_encode($accountArray));
			log_message('error', 'Connection failed with API and Secret .' . json_encode($e));
			return false;
		}
	}

	private function _checkCloudConnectionXX($accountArray)
	{
		$regions = $this -> config -> item('aws_regions');

		$client = S3Client::factory(array('key' => $accountArray['api_key'], 'secret' => $accountArray['secret_key'], 'region' => 'us-east-1'));

		$result = $client -> listBuckets();

		//support_cost_management

		$bucket_exists = false;
		foreach ($regions as $region)
		{
			try
			{
				if ($client -> doesBucketExist($accountArray['bucket_name']))
				{
					log_message('info' . 'AWS Account :' . $accountArray['bucket_name'] . ' exists in ' . $region);
					$bucket_exists = true;
					break;
				}
			} catch(Exception $ex)
			{
				log_message('error' . 'AWS Account Connection with API ' . $accountArray['bucket_name'] . ' exists in ' . var_export($ex));
				$bucket_exists = false;
			}
		}
		return $bucket_exists;
	}

}
