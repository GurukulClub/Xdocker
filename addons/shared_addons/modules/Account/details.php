<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Amazon Accounts module
 *
 * @author  Xervmon Dev Team
 * @package Xervmon\Core\Modules\AmazonAccount
 */
class Module_Accounts extends Module
{
	public $version = '2.0.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Accounts',
			),
			'description' => array(
				'en' => 'Accounts - Captures details needed for IaaS Cloud Account.',
			),
			'frontend' => false,
			'backend' => true,
			'skip_xss' => true,
			'menu' => 'accounts',

			'roles' => array(
				'add_account', 'edit_account', 'delete_account'
			),

			'sections' => array(
				'accounts' => array(
					'name' => 'account:list_title',
					'uri' => 'admin/account',
					'shortcuts' => array(
						array(
							'name' => 'account:create_title',
							'uri' => 'admin/account/add',
							'class' => 'add',
						),
					),
				),
				
				
			),
		);
	}

	public function install()
	{
		
		
		// Install the emailTemplate
		$emailTemplates = array(
			array(
				'slug' => 'account_created',
				'name' => 'Account Created',
				'description' => 'Account Created',
				'subject' => '{{ settings:site_name }} :: Account created by {{ name }}',
				'body' => '{{ settings:site_name }} :: Account created by {{ name }}<br />Account ID: {{ account_id}}',
				'lang' => 'en',
				'is_default' => '0',
				'module' => '',
			),
			array(
				'slug' => 'account_updated',
				'name' => 'Account Updated',
				'description' => 'Account Updated',
				'subject' => '{{ settings:site_name }} :: Account Updated by {{ name }}',
				'body' => '{{ settings:site_name }} :: Account created by {{ name }}<br />Account ID: {{ account_id}}',
				'lang' => 'en',
				'is_default' => '0',
				'module' => '',
			),
			array(
				'slug' => 'account_deleted',
				'name' => 'Account Deleted',
				'description' => 'Account Deleted',
				'subject' => '{{ settings:site_name }} :: Account Deleted by {{ name }}',
				'body' => '{{ settings:site_name }} :: Account created by {{ name }}<br />Account ID: {{ account_id}}',
				'lang' => 'en',
				'is_default' => '0',
				'module' => '',
			),
			);
			
		foreach ($emailTemplates as $emailTemplate)
		{
			if ( ! $this->db->insert('email_templates', $emailTemplate))
			{
				return false;
			}
		}

		return true;

	}

	public function uninstall()
	{
		// This is a core module, lets keep it around.
		return false;
	}

	public function upgrade($old_version)
	{
		return true;
	}
}
