<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User controller for the users module (frontend)
 *
 * @author		 Phil Sturgeon
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Modules\Users\Controllers
 */

class Admin extends Admin_Controller
{
	/**
	 * Constructor method
	 *
	 * @return \Users
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('users/user_m');
		$this->load->model('groups/group_m');
		$this->load->helper('user');
		$this->load->library('form_validation');
		$this->lang->load('users/user');
		$this->lang->load('UserProfile');
	}
	
	
	/**
	 * View a user profile based on the username
	 *
	 * @param string $username The Username or ID of the user
	 */
	public function view($username = null)
	{
		// work out the visibility setting
		switch (Settings::get('profile_visibility'))
		{
			case 'public':
				// if it's public then we don't care about anything
				break;

			case 'owner':
				// they have to be logged in so we know if they're the owner
				$this->current_user or redirect('users/login/users/view/'.$username);

				// do we have a match?
				$this->current_user->username !== $username and redirect('404');
				break;

			case 'hidden':
				// if it's hidden then nobody gets it
				redirect('404');
				break;

			case 'member':
				// anybody can see it if they're logged in
				$this->current_user or redirect('users/login/users/view/'.$username);
				break;
		}

		// Don't make a 2nd db call if the user profile is the same as the logged in user
		if ($this->current_user && $username === $this->current_user->username)
		{
			$user = $this->current_user;
		}
		// Fine, just grab the user from the DB
		else
		{
			$user = $this->ion_auth->get_user($username);
		}

		// No user? Show a 404 error
		$user or show_404();

		$this->template
			->append_css('common/form.css')
			->build('admin/profile/view', array(
			'_user' => $user,
		));
	}

	public function editProfile()
	{
		echo 'edit profile';
	}
	/**
	 * Edit Profile
	 *
	 * @param int $id
	 */
	public function edit($id = 0)
	{
		if ($this->current_user and $this->current_user->group === 'admin' and $id > 0)
		{
			$user = $this->user_m->get(array('id' => $id));

			// invalide user? Show them their own profile
			$user or redirect(site_url('admin/users_extn/edit'));
		}
		else
		{
			$user = $this->current_user or redirect('users/login/users/edit'.(($id > 0) ? '/'.$id : ''));
		}

		$profile_data = array(); // For our form

		// Get the profile data
		$profile_row = $this->db->limit(1)
			->where('user_id', $user->id)->get('profiles')->row();

		// If we have API's enabled, load stuff
		if (Settings::get('api_enabled') and Settings::get('api_user_keys'))
		{
			$this->load->model('api/api_key_m');
			$this->load->language('api/api');

			$api_key = $this->api_key_m->get_active_key($user->id);
		}

		$this->validation_rules = array(
			array(
				'field' => 'email',
				'label' => lang('user:email'),
				'rules' => 'required|xss_clean|valid_email'
			),
			array(
				'field' => 'display_name',
				'label' => lang('profile_display_name'),
				'rules' => 'required|xss_clean'
			)
		);

		// --------------------------------
		// Merge streams and users validation
		// --------------------------------

		// Get the profile fields validation array from streams
		$this->load->driver('Streams');
		$profile_validation = $this->streams->streams->validation_array('profiles', 'users', 'edit', array(), $profile_row->id);

		// Set the validation rules
		$this->form_validation->set_rules(array_merge($this->validation_rules, $profile_validation));

		// Get user profile data. This will be passed to our
		// streams insert_entry data in the model.
		$assignments = $this->streams->streams->get_assignments('profiles', 'users');

		// --------------------------------

		// Settings valid?
		if ($this->form_validation->run())
		{
			PYRO_DEMO and show_error(lang('global:demo_restrictions'));

			// Get our secure post
			$secure_post = $this->input->post();

			$user_data = array(); // Data for our user table
			$profile_data = array(); // Data for our profile table

			// --------------------------------
			// Deal with non-profile fields
			// --------------------------------
			// The non-profile fields are:
			// - email
			// - password
			// The rest are streams
			// --------------------------------

			$user_data['email'] = $secure_post['email'];

			// If password is being changed (and matches)
			if ($secure_post['password'])
			{
				$user_data['password'] = $secure_post['password'];
				unset($secure_post['password']);
			}

			// --------------------------------
			// Set the language for this user
			// --------------------------------

			if (isset($secure_post['lang']) and $secure_post['lang'])
			{
				$this->ion_auth->set_lang($secure_post['lang']);
				//$_SESSION['lang_code'] = $secure_post['lang'];
			}

			// --------------------------------
			// The profile data is what is left
			// over from secure_post.
			// --------------------------------

			$profile_data = $secure_post;

			if ($this->ion_auth->update_user($user->id, $user_data, $profile_data) !== false)
			{
				Events::trigger('post_user_update');
				$this->session->set_flashdata('success', $this->ion_auth->messages());
			}
			else
			{
				$this->session->set_flashdata('error', $this->ion_auth->errors());
			}

			redirect('admin/UserProfile/edit'.(($id > 0) ? '/'.$id : ''));
		}
		else
		{
			// --------------------------------
			// Grab user data
			// --------------------------------
			// Currently just the email.
			// --------------------------------		

			if (isset($_POST['email']))
			{
				$user->email = $_POST['email'];
			}
		}

		// --------------------------------
		// Grab user profile data
		// --------------------------------

		foreach ($assignments as $assign)
		{
			if (isset($_POST[$assign->field_slug]))
			{
				$profile_data[$assign->field_slug] = $this->input->post($assign->field_slug);
			}
			else
			{
				$profile_data[$assign->field_slug] = $profile_row->{$assign->field_slug};
			}
		}

		// --------------------------------
		// Run Stream Events
		// --------------------------------

		$profile_stream_id = $this->streams_m->get_stream_id_from_slug('profiles', 'users');
		$this->fields->run_field_events($this->streams_m->get_stream_fields($profile_stream_id), array());

		// --------------------------------

		// Render the view
		$this->template->build('admin/profile/edit', array(
			'_user' => $user,
			'display_name' => $profile_row->display_name,
			'profile_fields' => $this->streams->fields->get_stream_fields('profiles', 'users', $profile_data),
			'api_key' => isset($api_key) ? $api_key : null,
		));
	}

	/**
	 * Callback method used during login
	 *
	 * @param str $email The Email address
	 *
	 * @return bool
	 */
	public function _check_login($email)
	{
		$remember = false;
		if ($this->input->post('remember') == 1)
		{
			$remember = true;
		}

		if ($this->ion_auth->login($email, $this->input->post('password'), $remember))
		{
			return true;
		}

		Events::trigger('login_failed', $email);
		error_log('Login failed for user '.$email);

		$this->form_validation->set_message('_check_login', $this->ion_auth->errors());
		return false;
	}

	/**
	 * Username check
	 *
	 * @author Ben Edmunds
	 *
	 * @param string $username The username to check.
	 *
	 * @return bool
	 */
	public function _username_check($username)
	{
		if ($this->ion_auth->username_check($username))
		{
			$this->form_validation->set_message('_username_check', lang('user:error_username'));
			return false;
		}

		return true;
	}

	/**
	 * Email check
	 *
	 * @author Ben Edmunds
	 *
	 * @param string $email The email to check.
	 *
	 * @return bool
	 */
	public function _email_check($email)
	{
		if ($this->ion_auth->email_check($email))
		{
			$this->form_validation->set_message('_email_check', lang('user:error_email'));
			return false;
		}

		return true;
	}
	
	public function generate_key()
	{
		$this->load->model('api/api_key_m');
		$this->load->config('rest');
		if ( ! $this->current_user)
		{
			exit(json_encode(array('status' => false, 'error' => 'Log in')));
		}
				
		// Try and make the key, error on fail
		if ( ! ($api_key = $this->api_key_m->make_key($this->current_user->id)))
		{
			exit(json_encode(array('status' => false, 'error' => 'Could not create the key for some reason.')));
		}
		
		exit(json_encode(array('status' => true, 'api_key' => $api_key)));
	}
	
	/**
	 * Reset a user's password
	 *
	 * @param bool $code
	 */
	public function reset_pass($code = null)
	{
		$this->template->title(lang('user:reset_password_title'));

		if (PYRO_DEMO)
		{
			show_error(lang('global:demo_restrictions'));
		}

		//if user is logged in they don't need to be here
		if ($this->current_user)
		{
			$this->session->set_flashdata('error', lang('user:already_logged_in'));
			redirect('');
		}

		if ($this->input->post('email'))
		{
			$uname = (string) $this->input->post('user_name');
			$email = (string) $this->input->post('email');

			if ( ! $uname and ! $email)
			{
				// they submitted with an empty form, abort
				$this->template->set('error_string', $this->ion_auth->errors())
					->build('admin/UserProfile/reset_pass');
			}

			if ( ! ($user_meta = $this->ion_auth->get_user_by_email($email)))
			{
				$user_meta = $this->ion_auth->get_user_by_username($email);
			}

			// have we found a user?
			if ($user_meta)
			{
				$new_password = $this->ion_auth->forgotten_password($user_meta->email);

				if ($new_password)
				{
					//set success message
					$this->template->success_string = lang('forgot_password_successful');
				}
				else
				{
					// Set an error message explaining the reset failed
					$this->template->error_string = $this->ion_auth->errors();
				}
			}
			else
			{
				//wrong username / email combination
				$this->template->error_string = lang('user:forgot_incorrect');
			}
		}

		// code is supplied in url so lets try to reset the password
		if ($code)
		{
			// verify reset_code against code stored in db
			$reset = $this->ion_auth->forgotten_password_complete($code);

			// did the password reset?
			if ($reset)
			{
				redirect('admin/UserProfile/reset_complete');
			}
			else
			{
				// nope, set error message
				$this->template->error_string = $this->ion_auth->errors();
			}
		}

		$this->template->build('admin/UserProfile/reset_pass');
	}

	/**
	 * Password reset is finished
	 */
	public function reset_complete()
	{
		PYRO_DEMO and show_error(lang('global:demo_restrictions'));

		//if user is logged in they don't need to be here. and should use profile options
		if ($this->current_user)
		{
			$this->session->set_flashdata('error', lang('user:already_logged_in'));
			redirect('my-profile');
		}

		$this->template
			->title(lang('user:password_reset_title'))
			->build('admin/UserProfile/reset_pass_complete');
	}

	
	
}