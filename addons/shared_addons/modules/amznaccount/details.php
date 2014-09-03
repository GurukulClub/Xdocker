<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Amazon Accounts module
 *
 * @author  Xervmon Dev Team
 * @package Xervmon\Core\Modules\AmazonAccount
 */
class Module_AmznAccount extends Module
{
	public $version = '2.0.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Amazon Account',
			),
			'description' => array(
				'en' => 'Amazon Account - Captures details needed for Amazon Account.',
			),
			'frontend' => false,
			'backend' => true,
			'skip_xss' => true,
			'menu' => 'accounts',

			'roles' => array(
				'add_amznaccount', 'edit_amznaccount', 'delete_amznaccount'
			),

			'sections' => array(
				'accounts' => array(
					'name' => 'amznaccount:list_title',
					'uri' => 'admin/amznaccount',
					'shortcuts' => array(
						array(
							'name' => 'amznaccount:create_title',
							'uri' => 'admin/amznaccount/create',
							'class' => 'add',
						),
					),
				),
				'keys' => array(
					'name' => 'amznaccount:cloud',
					'uri' => 'admin/Cloud/index',
				),
				
			),
		);
	}

	public function install()
	{
		$this->dbforge->drop_table('amzn_account_status_log');
		$this->dbforge->drop_table('amzn_account');
		
		
		$ret = $this->install_tables(array(
			'amzn_accounts' => array(
				'id' => array('type' => 'INT', 'constraint' => 11,  'auto_increment' => true, 'primary' => true),
				'name' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => false, 'unique' => true),
				'cloud_provider' => array('type' => 'VARCHAR', 'constraint' => 25, 'null' => false, 'default' => 'Amazon AWS'),
				'api_key' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => false),
				'secret_key' => array('type' => 'VARCHAR', 'constraint' => 100, 'default' => ''),
				'account_id' => array('type' => 'VARCHAR', 'constraint' => 25, 'null' => false, 'unique' => true),
				'support_cost_management' => array('type' => 'ENUM', 'constraint' => array('1', '0'), 'default' => '0',  'null' => false, ),
				'bucket_name' => array('type' => 'VARCHAR', 'constraint' => 100,  'null' => true ),
				'user_id' => array('type' => 'INT', 'constraint' => 11,  'null' => false, ),
				'created_on' => array('type' => 'INT', 'constraint' => 11,  'null' => false, ),
				'updated_on' => array('type' => 'INT', 'constraint' => 11,  'null' => false,  'default'=> 0),
				'created_by' => array('type' => 'INT', 'constraint' => 11,  'null' => false,  ),
				'updated_by' => array('type' => 'INT', 'constraint' => 11,  'null' => false,  'default'=> 0),
			),
			'amzn_account_pem' => array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'amazon_account_id' => array('type' => 'INT', 'constraint' => 11, 'null' => false),
				'key_pair_name' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => false, 'unique' => true),
				'is_pem_available' => array('type' => 'ENUM', 'constraint' => array(1, 0), 'default' => 0,  'null' => false, ),
				'pem_string' => array('type' => 'text',   'null' => true, ),
				'pem_location' => array('type' => 'text',   'null' => true, ),
				'user_id' => array('type' => 'INT', 'constraint' => 11,  'null' => false, ),
				'created_on' => array('type' => 'INT', 'constraint' => 11,  'null' => false, ),
				'updated_on' => array('type' => 'INT', 'constraint' => 11,  'null' => false,  'default'=> 0),
				'created_by' => array('type' => 'INT', 'constraint' => 11,  'null' => false,  ),
				'updated_by' => array('type' => 'INT', 'constraint' => 11,  'null' => false,  'default'=> 0),
			),
			
		));
		
		$this -> db -> query('ALTER TABLE  '. $this -> db->dbprefix('amzn_accounts'). ' AUTO_INCREMENT = 3000');
		$this -> db -> query('ALTER TABLE  '. $this -> db->dbprefix('amzn_account_pem'). ' AUTO_INCREMENT = 3500');
		
		$widgetDefinitions = array(
			array(
				'title' => 'AWSBilling Metadata',
				'widget_type' => 'table',
				'widget_area' => 'full',
				'widget_module' => 'amznaccount/widgets/AWSBillingSummaryMetadataWidget.php',
				'widget_module_class' => 'AWSBillingSummaryMetadataWidget',
				'options' => '',
				'icon_url' => BASE_URL().'/addons/shared_addons/themes/xervmon/img/amazon-accountAddons.png',
				'created_on' => 0,
				'created_by' => 0,
				'updated_on' => 0,
				'updated_by' => 0,
			),
			
			
			);
			
			$service_types = array(
			array(
				'id'	=> 6,
				'title' => 'EC2',
				'description' => '',
				'service_provider' => 'Amazon AWS',
				'service_type_cloud_provider' => 'Amazon AWS-EC2',
				'enabled' => 1,
				'created_on' => 0,
				'created_by' => 0,
				'updated_on' => 0,
				'updated_by' => 0,
			
			),
			array(
				'id'	=> 7,
				'title' => 'EBS',
				'description' => '',
				'service_provider' => 'Amazon AWS',
				'service_type_cloud_provider' => 'Amazon AWS-EBS',
				'enabled' => 1,
				'created_on' => 0,
				'created_by' => 0,
				'updated_on' => 0,
				'updated_by' => 0,
			
			),array(
				'id'	=> 8,
				'title' => 'ELB',
				'description' => '',
				'service_provider' => 'Amazon AWS',
				'service_type_cloud_provider' => 'Amazon AWS-ELB',
				'enabled' => 1,
				'created_on' => 0,
				'created_by' => 0,
				'updated_on' => 0,
				'updated_by' => 0,
			
			),array(
				'id'	=> 9,
				'title' => 'RDS',
				'description' => '',
				'service_provider' => 'Amazon AWS',
				'service_type_cloud_provider' => 'Amazon AWS-RDS',
				'enabled' => 1,
				'created_on' => 0,
				'created_by' => 0,
				'updated_on' => 0,
				'updated_by' => 0,
			
			),
			
			array(
				'id'	=> 19,
				'title' => 'VPC-SPS',
				'description' => 'Single Public Subnet',
				'service_provider' => 'Amazon AWS',
				'service_type_cloud_provider' => 'Amazon AWS-VPC-SPS',
				'enabled' => 1,
				'created_on' => 0,
				'created_by' => 0,
				'updated_on' => 0,
				'updated_by' => 0,
			),
			array(
				'id'	=> 20,
				'title' => 'VPC-PPS',
				'description' => 'Public Private Subnet',
				'service_provider' => 'Amazon AWS',
				'service_type_cloud_provider' => 'Amazon AWS-VPC-PPS',
				'enabled' => 1,
				'created_on' => 0,
				'created_by' => 0,
				'updated_on' => 0,
				'updated_by' => 0,
			),
			array(
				'id'	=> 21,
				'title' => 'VPC-PPS-HWVPN',
				'description' => 'VPC with Public and Private Subnets and Hardware VPN Access',
				'service_provider' => 'Amazon AWS',
				'service_type_cloud_provider' => 'Amazon AWS-VPC-PPS',
				'enabled' => 1,
				'created_on' => 0,
				'created_by' => 0,
				'updated_on' => 0,
				'updated_by' => 0,
			),
			array(
				'id'	=> 22,
				'title' => 'VPC-PSO-HWVPN',
				'description' => 'VPC with a Private Subnet Only and Hardware VPN Access',
				'service_provider' => 'Amazon AWS',
				'service_type_cloud_provider' => 'Amazon AWS-VPC-PPS',
				'enabled' => 1,
				'created_on' => 0,
				'created_by' => 0,
				'updated_on' => 0,
				'updated_by' => 0,
			),
			
			);
			
		foreach ($service_types as $service_type)
		{
			if ( ! $this->db->insert('service_types', $service_type))
			{
				return false;
			}
		}
		
		// Install the emailTemplate
		$emailTemplates = array(
			array(
				'slug' => 'amznaccount_created',
				'name' => 'Amazon Created',
				'description' => 'Amazon Created',
				'subject' => '{{ settings:site_name }} :: Amazon created by {{ name }}',
				'body' => '{{ settings:site_name }} :: Amazon created by {{ name }}<br />Account ID: {{ account_id}}',
				'lang' => 'en',
				'is_default' => '0',
				'module' => '',
			),
			array(
				'slug' => 'amznaccount_updated',
				'name' => 'Amazon Updated',
				'description' => 'Amazon Updated',
				'subject' => '{{ settings:site_name }} :: Amazon Updated by {{ name }}',
				'body' => '{{ settings:site_name }} :: Amazon created by {{ name }}<br />Account ID: {{ account_id}}',
				'lang' => 'en',
				'is_default' => '0',
				'module' => '',
			),
			array(
				'slug' => 'amznaccount_deleted',
				'name' => 'Amazon Deleted',
				'description' => 'Amazon Deleted',
				'subject' => '{{ settings:site_name }} :: Amazon Deleted by {{ name }}',
				'body' => '{{ settings:site_name }} :: Amazon created by {{ name }}<br />Account ID: {{ account_id}}',
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

		$plannedConnections = "INSERT INTO core_deployment_planner_connections (serverType, ".
					" cloudProvider, allowableTargets, additionalFormDataFields, serverIcon, serverBackground, maxConnections) VALUES ".
		"('EC2', 'Amazon AWS', '[\"ELB\", \"HA-Load Balancer\", \"RDS\", \"VPC-SPS\"]', '[\"server_name\", \"no_instances\", \"region_geo\", \"flavorId\", \"nameValueTags\", \"ec2_network\", \"ec2_vpcsecurityGroup\", \"subnet_vpc\", \"region_az\",  \"iam_role\",  \"shutdwon_behavior\", ".
		"  \"enable_termination_protection\", \"enable_aws_monitoring\", \"xervmon_monitoring\", \"kernelID\", \"ramDiskId\", \"server_cost\", \"monthly_cost\",\"image_id\", \"image_name\", \"nameValueTags\",\"localkeyPairName\",\"KeyPairs\"]', 'addons/shared_addons/themes/xervmon/img/Cloud/DeploymentDesigner/icons/cloudServer.png', 'addons/shared_addons/themes/xervmon/img/Cloud/DeploymentDesigner/boxes/server.png', -1), ".
		" ('EBS', 'Amazon AWS', '[\"EC2\"]', '[\"vol_name\", \"region_geo\", \"region_az\", \"flavorId\", \"snapshot\", \"volume_type\", \"delete_on_termination\", \"server_cost\", \"snapshot_name\", \"monthly_cost\", \"vol_size\", \"device_type\"]', 'addons/shared_addons/themes/xervmon/img/Cloud/DeploymentDesigner/icons/volume.png', 'addons/shared_addons/themes/xervmon/img/Cloud/DeploymentDesigner/boxes/volume.png', 1), ".
		" ('RDS', 'Amazon AWS', '[\"EC2\"]', '[\"server_name\", \"dbName\", \"dbusername\", \"dbpassword\",\"dbconfirm_password\", \"multi_az\", \"region_geo\", \"ec2_network\", \"db_subnet_vpc\", \"ec2_vpcsecurityGroup\" , \"rds_optionGroup\" , \"rds_parameterGroup\" , \"license_model\", \"dbVersion\", \"dbInstanceType\", \"auto_minor_version_upgrade\", \"use_provisioned_iops\", \"provisionined_iops\",\"server_cost\", \"monthly_cost\", \"disk_size\", \"db_name\", \"username\", \"password\", \"multi_eab\", \"multi_brp\", \"multi_backupWindow\", \"multi_backupStartTime\", \"multi_backupDuration\", \"multi_maintenanceWindow\", \"multi_startDay\", \"multi_maintenanceStartTime\", \"multi_maintenanceDuration\", \"multi_backupStartTimeMin\", \"multi_maintenanceStartTimeMin\"]', 'addons/shared_addons/themes/xervmon/img/Cloud/DeploymentDesigner/icons/cloudDatabase.png', 'addons/shared_addons/themes/xervmon/img/Cloud/DeploymentDesigner/boxes/database.png', -1), ".
		" ('ELB', 'Amazon AWS', '[]', '[\"lb_name\",\"lb_inside\",\"protocol\",\"port\",\"ping_protocol\",\"ping_port\", \"ping_path\", \"response_time_out\",  \"health_check_interval\",  \"unhealthy_threshold\",  \"healthy_threshold\", \"region_geo\", \"ec2_network\", \"ec2_vpcsecurityGroup\", \"subnet_vpc\", \"server_cost\",\"monthly_cost\",\"listenerConfiguration\",\"region_az\"]', 'addons/shared_addons/themes/xervmon/img/Cloud/DeploymentDesigner/icons/loadBalancer.png', 'addons/shared_addons/themes/xervmon/img/Cloud/DeploymentDesigner/boxes/loadBalancer.png', -1),".
		" ('VPC-SPS', 'Amazon AWS', '[\"EC2\", \"ELB\", \"RDS\"]', '[\"ipCidrBlock\",\"vpcName\",\"publicSubnet\", \"availabilityZone\",\"subnetName\",\"enableDnsHostnames\", \"hardwareTenancy\", \"region_geo\", \"region_az\"]', 'addons/shared_addons/themes/xervmon/img/Cloud/DeploymentDesigner/icons/VPC.png', 'addons/shared_addons/themes/xervmon/img/Cloud/DeploymentDesigner/boxes/loadBalancer.png', -1),".
		" ('VPC-PPS', 'Amazon AWS', '[\"EC2\", \"ELB\", \"RDS\"]', '[\"ipCidrBlock\",\"vpcName\",\"publicSubnet\",\"availabilityZone\",\"publicSubnetName\", \"privateSubnet\", \"privateAvailabilityZone\", \"privateSubnetName\", \"instanceType\",\"localkeyPairName\", \"enableDnsHostnames\", \"hardwareTenancy\", \"region_geo\", \"region_az\"]', 'addons/shared_addons/themes/xervmon/img/Cloud/DeploymentDesigner/icons/VPC.png', 'addons/shared_addons/themes/xervmon/img/Cloud/DeploymentDesigner/boxes/loadBalancer.png', -1),".
		" ('VPC-PPS-HWVPN', 'Amazon AWS', '[\"EC2\", \"ELB\", \"RDS\"]', '[\"ipCidrBlock\",\"vpcName\",\"publicSubnet\",\"availabilityZone\",\"publicSubnetName\", \"privateSubnet\", \"privateAvailabilityZone\", \"privateSubnetName\", \"instanceType\",\"keyPairName\", \"enableDnsHostnames\", \"hardwareTenancy\", \"customerGatewayIp\",\"customerGatewayName\", \"vpcConnectionName\", \"routingPairs\", \"region_geo\", \"region_az\"]', 'addons/shared_addons/themes/xervmon/img/Cloud/DeploymentDesigner/icons/VPC.png', 'addons/shared_addons/themes/xervmon/img/Cloud/DeploymentDesigner/boxes/loadBalancer.png', -1),".
		" ('VPC-PSO-HWVPN', 'Amazon AWS', '[\"EC2\", \"ELB\", \"RDS\"]', '[\"ipCidrBlock\",\"vpcName\", \"privateSubnet\", \"privateAvailabilityZone\", \"privateSubnetName\", \"instanceType\",\"keyPairName\", \"enableDnsHostnames\", \"hardwareTenancy\", \"customerGatewayIp\",\"customerGatewayName\", \"vpcConnectionName\", \"routingPairs\", \"region_geo\", \"region_az\"]', 'addons/shared_addons/themes/xervmon/img/Cloud/DeploymentDesigner/icons/VPC.png', 'addons/shared_addons/themes/xervmon/img/Cloud/DeploymentDesigner/boxes/loadBalancer.png', -1);";
		
		$this->db->query($plannedConnections);
		
		
		// Install the settings
		$settings = array(
			array(
				'slug' => 'enable_aws_billing',
				'title' => 'Spend Intelligence',
				'description' => 'Delivers Intelligence on how you are doing on spending',
				'type' => 'radio',
				'default' => '1',
				'value' => '1',
				'options' => '1=Yes|0=No',
				'is_required' => 1,
				'is_gui' => 1,
				'module' => 'AWS_Billing',
				'order' => 0,
			),
			
			array(
				'slug' => 'services_for_billing',
				'title' => 'Enabled Services',
				'description' => 'Services that are enabled for Billing/Spend Analytics',
				'type' => 'checkbox',
				'default' => 'aws-billing-csv,aws-billing-detailed-line-items,aws-cost-allocation',
				'value' => '',
				'options' => 'aws-billing-csv=aws-billing-csv|aws-billing-detailed-line-items=aws-billing-detailed-line-items|aws-cost-allocation=aws-cost-allocation',
				'is_required' => 0,
				'is_gui' => 1,
				'module' => 'AWS_Billing',
				'order' => 0,
			),
			
			array(
				'slug' => 'enable_resource_visualization',
				'title' => 'AWS Resources Visualization',
				'description' => 'Visualize AWS Deployments',
				'type' => 'radio',
				'default' => '1',
				'value' => '1',
				'options' => '1=Yes|0=No',
				'is_required' => 0,
				'is_gui' => 1,
				'module' => 'AWS_Visualization',
				'order' => 0,
			),
			
			array(
				'slug' => 'resources_for_visualization',
				'title' => 'Enabled Resources',
				'description' => 'Services that are enabled  for Visualization',
				'type' => 'checkbox',
				'default' => 'EC2,EBS,RDS,ELB,S3',
				'value' => '',
				'options' => 'EC2=EC2|EBS=EBS|RDS=RDS|ELB=ELB|S3=S3',
				'is_required' => 0,
				'is_gui' => 1,
				'module' => 'AWS_Visualization',
				'order' => 0,
			),
			
			);
			
	foreach ($settings as $setting)
		{
			if ( ! $this->db->insert('settings', $setting))
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
