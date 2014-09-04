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


class Admin extends Admin_Controller
{
	/** @var string The current active section */
	protected $section = 'accounts';
	private $phpmongoClient;
	private $_ci;

	/** @var array The validation rules */
	protected $validation_rules = array('name' => array('field' => 'name', 'label' => 'lang:global:title', 'rules' => 'trim|required|max_length[100]'), 
										'api_key' => array('field' => 'api_key', 'label' => 'lang:amznaccount.error_api_key', 'rules' => 'trim|required|max_length[100]'), 
										'secret_key' => array('field' => 'secret_key', 'label' => 'lang:amznaccount.error_secret_key', 'rules' => 'trim|required|max_length[100]'),);

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
			$account['api_key'] = StringHelper::decrypt($account['api_key'] ,md5($this->current_user->username));
			$accountArr[] = $account;
		}
		//do we need to unset the layout because the request is ajax?
		$this -> input -> is_ajax_request() and $this -> template -> set_layout(false);

		$this -> template -> title($this -> module_details['name']) 
						  -> append_js('admin/filter.js') 
						  -> set_partial('filters', 'admin/partials/filters') 
						 -> set('accounts', $accountArr);
		$this -> input -> is_ajax_request() ? 
						$this -> template -> build('admin/tables/list') : $this -> template -> build('admin/index');
	
	}

	/**
	 * Create new amznaccount Account
	 */
	public function create()
	{
		role_or_die('amznaccount', 'add_amznaccount');
		$post = new stdClass();
		$post -> name = '';
		$post -> api_key = '';
		$post -> secret_key = '';
		$this -> template -> title($this -> module_details['name'], lang('amznaccount:create_title')) 
						  -> set('post', $post) 
						  -> append_css('bootstrap/bootstrap.css') 
						  -> append_css('bootstrap/bootstrap-responsive.css') 
						  -> append_css('common/form.css') 
						  -> append_css('common/style.css') 
						  -> append_css('common/select2.css') 
						  -> append_js('jquery/jquery.validate.js') 
						  -> append_js('script/jquery/select2.js') 
						  -> append_js('script/script.js')
						  -> append_js('jquery/jquery.validate.unobtrusive.js') 
						  -> append_js('jquery/jquery.validate.bootstrap.js') 
	   					  -> append_js('module::amznaccount.js') 
	   					  -> build('admin/form');
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
	public function edit($id = '')
	{
		$id OR redirect('admin/amznaccount');
		role_or_die('amznaccount', 'edit_amznaccount');
		$acct = $this->phpmongoClient ->where( array
													(
													'userid' => $this->current_user->id,
													'cloudProvider' => CloudType::AWS_CLOUD,
													'_id' => new MongoId($id)
													) 
											  ) -> get($this->section);
		$post -> api_key = StringHelper::decrypt($post['api_key'], md5($this->current_user->username));
		$post -> secret_key = StringHelper::decrypt($post['secret_key'], md5($this->current_user->username ));

		$post -> id = $id;
		$this -> template -> title($this -> module_details['name'], sprintf(lang('amznaccount:edit_title'), $post -> name)) 
			  -> set('post', $post)
			  -> append_js('jquery/jquery.validate.js') 
			  -> append_css('bootstrap/bootstrap.css') 
			  -> append_css('bootstrap/bootstrap-responsive.css') 
			  -> append_css('common/form.css') 
			  -> append_js('jquery/jquery.validate.unobtrusive.js') 
			  -> append_js('jquery/jquery.validate.bootstrap.js') 
			  -> append_js('module::amznaccount.js') 
			  -> append_js('script/script.js') 
			  -> append_css('common/style.css')
			  -> append_js('script/jquery/select2.js') 
			  -> build('admin/form');
	}

	/**
	 * Delete blog post
	 *
	 * @param int $id The ID of the blog post to delete
	 */
	public function delete($id = '')
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
				if ($post = $this->phpmongoClient ->where( 
														array
														(
															'userid' => $this->current_user->id,
															'cloudProvider' => CloudType::AWS_CLOUD,
															'_id' => new MongoId($id)
														) 
													 ) -> get($this->section))
				{
					if ($this->phpmongoClient ->where( array(
													'		userid' => $this->current_user->id,
															'cloudProvider' => CloudType::AWS_CLOUD,
															'_id' => new MongoId($id)
												) ) -> delete($this->section))
					{
						// Wipe cache for this model, the content has changed
						$post_titles[] = $post[0]['name'];
						$deleted_ids[] = $id;
					}
				}
			}
			Events::trigger('amznaccount_deleted', $deleted_ids);
			$this -> triggerEvent($post_titles, 'amznaccount_deleted');
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
					Events::trigger('email', array('name' => $this -> current_user -> display_name, 'accountName'=> $post['name'], 'slug' => $eventType, 'to' => $notifyEmail-> value), 'array');
						
				}
				else {	
					Events::trigger('email', array('name' => $this -> current_user -> display_name, 'accountName'=>$post['name']  ,'api_key' => $post['api_key'], 'secret_key' => $post['secret_key'], 'slug' => $eventType, 'to' => $notifyEmail-> value), 'array');
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
			Events::trigger('email', array('name' => $this -> current_user -> display_name, 'accountName'=>$post['name'] , 'api_key' => $post['api_key'], 'secret_key' => $post['secret_key'], 'slug' => lang('rackaccount:rackaccount_assigned_template'), 'to' => $user -> email), 'array');

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
		
		$message = '';

		$accountArray = array('name' => $this -> input -> post('name'), 
							  'cloudProvider' => CloudType::AWS_CLOUD, 
							  'api_key' => StringHelper::encrypt($this -> input -> post('api_key'), md5($this->current_user->username )), 
							  'secret_key' => StringHelper::encrypt($this -> input -> post('secret_key'), md5($this->current_user->username )),
							  'created_on' => now(), 
							  'userid' => $this -> current_user -> id, 
							  'created_by' => $this -> current_user -> id, );
		$id = $this -> input -> post('id');
		if (empty($id))
		{
			//Do validation and insert
			// Merge and set our validation rules
			$amznaccountValidation = array_merge($this -> validation_rules, array('name' => array('field' => 'name', 'label' => 'lang:global:title', 'rules' => 'trim|required|max_length[100]|callback__check_title'), 'api_key' => array('field' => 'api_key', 'label' => 'lang:amznaccount.error_api_key', 'rules' => 'trim|required|max_length[100]|callback__check_username'),));
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
				log_message('info', __FILE__ . '->' . __FUNCTION__ . ' Insert Data inserted');
			} else
			{
				$errors = validation_errors();
				$message = json_encode(array('status' => 'error', 'status_msg' => $errors));
			}

		} 
		else
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
				log_message('info', __FILE__ . '->' . __FUNCTION__ . ' Update Data inserted ');
			} else
			{
				$errors = validation_errors();
				$message = json_encode(array('status' => 'error', 'status_msg' => $errors));
			}
		}
		print $message;
		return;
	}

	private function simpleInsert($accountArray)
	{
		try
		{
			$uniqueId = $this -> phpmongoClient -> insert($this->section, $accountArray);
			$message = array('id' => $uniqueId, 'status' => 'success', 'status_msg' => sprintf($this -> lang -> line('amznaccount:post_add_success'), $accountArray['name']));
			Events::trigger('amznaccount_created', $uniqueId);
			$this -> triggerEvent($accountArray, 'amznaccount_created');
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
		$ret = $this -> phpmongoClient->where(array('_id' => new MongoId($id), 'userid' => $this->current_user->id ))
								  ->set($accountArray)
								  ->update($this->section, $accountArray);
			
		if ($ret)
		{
			$message = array('status' => 'success', 'status_msg' => sprintf($this -> lang -> line('amznaccount:post_edit_success'), $accountArray['name']));

			Events::trigger('amznaccount_updated', $id);
			$this -> triggerEvent($accountArray, 'amznaccount_updated');
		} 
		else
		{
			$message = array('status' => 'error', 'status_msg' => lang('amznaccount:post_edit_error'));
		}
		return json_encode($message);
	}

	/**
	 * Callback method that checks the title of an post
	 *
	 * @param string $title or $name The Title to check
	 * @param string $id
	 *
	 * @return bool
	 */
	public function _check_title($title)
	{
		$this -> form_validation -> set_message('_check_title', sprintf(lang('amznaccount:already_exist_error'), lang('global:title')));

		$ret =  $this -> phpmongoClient->where(array('name'=> $title, 'userid' => $this->current_user->id ))->get($this->section);
		if(empty($ret)) return true; else return false;
	}

	public function _check_username($api_key, $id = null)
	{
		$this -> form_validation -> set_message('_check_username', lang('amznaccount:already_exist_error'));
		$ret = $this -> phpmongoClient->where(array('api_key'=> $api_key, 'userid' => $this->current_user->id ))->get($this->section);
		if(empty($ret)) return true; else return false;
	}

	private function _checkCloudConnection($accountArray)
	{
		try
		{
			$account = json_decode(json_encode($accountArray), FALSE);
			$auth = $this -> authenticate($account);
			if (!$auth)
			{
				log_message('error', __FILE__ . ' /' . __FUNCTION__ . ' Auth Failure ' . json_encode($accountArray));
			}
			return $auth;
		} catch(Exception $e)
		{
			log_message('error', 'Connection failed with API and Secret -input .' . json_encode($accountArray));
			log_message('error', 'Connection failed with API and Secret .' . json_encode($e));
			return false;
		}
	}
	
	private function authenticate($account)
	{
		$config['key'] = StringHelper::decrypt($account->api_key, md5($this->current_user->username ));
		$config['secret'] = StringHelper::decrypt($account->secret_key, md5($this->current_user->username ));
		$config['region'] = 'us-east-1';
		$this -> aws = Aws\Common\Aws::factory($config);

		$conSTatus = false;
		try
		{
			$this -> ec2Compute = $this -> aws -> get('ec2');
			$result = $this -> ec2Compute->getRegions();
			$conSTatus = (!empty($result) && count($result) > 0);

		} catch(Exception $ex)
		{
			$conSTatus = false;
			log_message('error', 'Connection failed with API and Secret .' . $ex->getMessage());
		}
		return $conSTatus;
	}

}
