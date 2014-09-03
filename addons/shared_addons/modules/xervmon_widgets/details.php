<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Widgets Module
 *
 * @author Xervmon Dev Team
 * @package Xervmon\Core\Modules\Widgets
 */
class Module_Xervmon_Widgets extends Module {

	public $version = '1.2.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Xervmon Widgets',
			
			),
			'description' => array(
				'en' => 'Configurable Xervmon widgets for backend.',
			),
			'frontend' 	=> false,
			'backend'  	=> false,
			'menu'	  	=> 'content',
			'skip_xss'	=> true,

		
		);
	}

	public function install()
	{
		$this->dbforge->drop_table('xervmon_widget_definitions');
		$this->dbforge->drop_table('ervmon_widgets_user_tabs');
		$this->dbforge->drop_table('xervmon_widgets');
		$tables = array(
			'xervmon_user_widgets' => array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'user_id' => array('type' => 'INT', 'constraint' => 11, 'null' => false),
				'title' => array('type' => 'VARCHAR', 'constraint' => 255,  'null' => true, ),
				'widget_id' => array('type' => 'INT', 'constraint' => 11, 'null' => false),
				'tab_id' => array('type' => 'INT', 'constraint' => 11, 'null' => false),
				'options' => array('type' => 'text', 'null' => true, ),
				'refresh_rate' => array('type' => 'INT', 'constraint' => 11, 'null' => false, 'default'=> 10000),
				'json_url' => array('type' => 'VARCHAR', 'constraint' => 255,  'null' => true, ),
				'created_on' => array('type' => 'INT', 'constraint' => 11,  'null' => false, 'default'=> 300),
				'updated_on' => array('type' => 'INT', 'constraint' => 11,  'null' => false,  'default'=> 0),
				'created_by' => array('type' => 'INT', 'constraint' => 11,  'null' => false,  ),
				'updated_by' => array('type' => 'INT', 'constraint' => 11,  'null' => false,  'default'=> 0),
			),
			
			'xervmon_widgets_user_tabs' => array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'user_id' => array('type' => 'INT', 'constraint' => 11, 'null' => false),
				'title' => array('type' => 'VARCHAR', 'constraint' => 255,  'null' => false, ),
				'created_on' => array('type' => 'INT', 'constraint' => 11,  'null' => false, ),
				'updated_on' => array('type' => 'INT', 'constraint' => 11,  'null' => false,  'default'=> 0),
				'created_by' => array('type' => 'INT', 'constraint' => 11,  'null' => false,  ),
				'updated_by' => array('type' => 'INT', 'constraint' => 11,  'null' => false,  'default'=> 0),
			),
			
			'xervmon_widget_definitions' => array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'title' => array('type' => 'VARCHAR', 'constraint' => 255,  'null' => false, ),
				
				'widget_type' => array('type' => 'set', 'constraint' => array('table','chart','barchart','html','map_bubble','map_bubble_hosts','map_bubble_services','bargraph','count_boxes'),  'null' => false, ),
				'widget_area' => array('type' => 'set', 'constraint' => array('half', 'full', 'one_fourth'),  'null' => false, ),
				'widget_module' => array('type' => 'VARCHAR', 'constraint' => 100,  'null' => false, ),
				'widget_module_class' => array('type' => 'VARCHAR', 'constraint' => 255,  'null' => false, ),
				'options' => array('type' => 'text', 'null' => false, ),
				'icon_url' => array('type' => 'VARCHAR', 'constraint' => 255,  'null' => false, ),
				'created_on' => array('type' => 'INT', 'constraint' => 11,  'null' => false, ),
				'updated_on' => array('type' => 'INT', 'constraint' => 11,  'null' => false,  'default'=> 0),
				'created_by' => array('type' => 'INT', 'constraint' => 11,  'null' => false,  ),
				'updated_by' => array('type' => 'INT', 'constraint' => 11,  'null' => false,  'default'=> 0),
			)
			);
		
			$this->install_tables($tables);
			$settings = array(
			'dashboard_tour' => array(
				'title' => 'Dashboard Tour',
				'description' => 'Navaigation help for the Dashboard',
				'type' => 'radio',
				'default' => '0',
				'value' => '1',
				'options' => '1=Yes|0=No',
				'is_required' => 1,
				'is_gui' => 0,
				'module' => 'show_tour',
				'order' => 1000,
			),);
			
		foreach ($settings as $slug => $setting_info)
		{
			log_message('debug', '-- Settings: installing '.$slug);
			$setting_info['slug'] = $slug;
			if ( ! $this->db->insert('settings', $setting_info))
			{
				log_message('debug', '-- -- could not install '.$slug);

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
