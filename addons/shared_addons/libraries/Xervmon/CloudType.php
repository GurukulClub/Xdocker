<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 * @author  Xervmon Dev Team
 * @package Xervmon\Core\Modules\managecloud\Controllers
 */
 
 class CloudType
 {
 	const RACKSPACE_CLOUD 					= 'Rackspace Cloud';
	const AWS_CLOUD			 			    = 'Amazon AWS';
 	const HP_CLOUD 			  				= 'HP Cloud';
 	const HP_CSM 			  				= 'HP CloudSystem';
	const OPENSTACK							= 'OpenStack';
	const DIGITAL_OCEAN						= 'DigitalOcean';
	
	const GCE								= 'GCE';
	const VCLOUD							= 'vCloud';
	const CLOUDSTACK						= 'CloudStack';
	const SOFTLAYER							= 'Softlayer';
	const WINDOWS_AZURE						= 'Windows Azure';
 	const IGEN 					  			= 'I_gen';
 	const IIGEN 							= 'II_gen';
	const CD 								= 'cd';
	
 	const RACK_IGEN_US_AUTH_URL 			= 'https://auth.api.rackspacecloud.com/v1.0';
	const RACK_IGEN_UK_AUTH_URL 		    = 'https://lon.auth.api.rackspacecloud.com/v1.0';
	const RACK_IIGEN_US_AUTH_URL 		    = 'https://identity.api.rackspacecloud.com/v2.0';
	const RACK_IIGEN_UK_AUTH_URL 			= 'https://lon.identity.api.rackspacecloud.com/v2.0';
	
	const RACK_REGION_IAD = 'IAD';
	const RACK_REGION_DFW = 'DFW';
	const RACK_REGION_LON = 'LON';
	const RACK_REGION_ORD = 'ORD';
	const RACK_REGION_SYD = 'SYD';
	const RACK_REGION_HKG = 'HKG';
	
	const RACK_REGION_US = 'US';
	const RACK_REGION_UK = 'UK';
	
	const RACK_REGION_USIAD = 'US-IAD';
	const RACK_REGION_USDFW = 'US-DFW';
	const RACK_REGION_USORD = 'US-ORD';
	
	const RACK_REGION_UKLON = 'UK-LON';
	
	//Asia Pacific
	const RACK_REGION_AUSSYD = 'AUS-SYD';
	const RACK_REGION_HKHKG = 'HK-HKG';

	
	const SERVICE_TYPE_IMAGES			    	= 'Images';
	
	
	const SERVICE_TYPE_RACK_CLOUD_SERVER	= 'CloudServers';
	const SERVICE_TYPE_RACK_VOLUME			= 'Volumes';
	const SERVICE_TYPE_RACK_LOAD_BALANCER	= 'Load Balancers';
	const SERVICE_TYPE_RACK_SW_CLOUD_SERVER	= 'HA-Load Balancers';
	const SERVICE_TYPE_RACK_CLOUD_DATABASE	= 'CloudDatabases';
	const SERVICE_TYPE_BASIC_WO_LAUNCH		= 'basic_wo_launch';
	const OBJECT_STORAGE					= 'objectStorage';
	const SERVICE_TYPE_RACK_OBJECT_STORAGE	= 'Object Storage';
	const SERVICE_TYPE_RACK_SNAPSHOTS		= 'Snapshots';
	const SERVICE_TYPE_RACK_IMAGES		= 'Images';
	
	
	const SERVICE_TYPE_AWS_EC2_INSTANCES 		= 'EC2';
	const SERVICE_TYPE_AWS_VOLUMES 		 		= 'EBS';
	const SERVICE_TYPE_AWS_SECURITY_GROUPS  	= 'SecurityGroups';
	const SERVICE_TYPE_AWS_KEYPAIRS			  	= 'KeyPairs';
	const SERVICE_TYPE_AWS_IP_ADDRESSES 		= 'EIP';
	const SERVICE_TYPE_AWS_RESERVED_INSTANCES   = 'RI';
	const SERVICE_TYPE_AWS_OBJECT_STORAGE	    = 'S3';

	const SERVICE_TYPE_AWS_ELB	    = 'ELB';
	
	const SERVICE_TYPE_AWS_RDS	    = 'RDS';
	
	const SERVICE_TYPE_OPENSTACK_CLOUD_SERVER	= 'Compute';
	const SERVICE_TYPE_OPENSTACK_VOLUME			= 'Cinder';
	const SERVICE_TYPE_OPENSTACK_SW_CLOUD_SERVER	= 'HA-Load Balancer';
	const SERVICE_TYPE_RACK_CLOUD_CONTAINER	= 'Containers';
	
	const SERVICE_TYPE_DIGITAL_OCEAN_DROPLETS	    = 'Droplets';
	const SERVICE_TYPE_DIGITAL_OCEAN_SNAPSHOTS	    = 'Snapshots';
	const SERVICE_TYPE_DC_KEYPAIRS = 'SSH Keys';
	const SERVICE_TYPE_DC_DOMAINS = 'DNS';
	
	// VPC const
	const SERVICE_TYPE_AWS_VPC	    = 'VPC';
	const SERVICE_TYPE_AWS_VPC_SUBNETS	    = 'Subnets';
	const SERVICE_TYPE_AWS_VPC_ROUTETABLE	    = 'RouteTables';
	const SERVICE_TYPE_AWS_VPC_INTERNETGATEWAY	    = 'Gateway';
	const SERVICE_TYPE_AWS_VPC_DHCP	    = 'DHCP';
	const SERVICE_TYPE_AWS_VPC_ELASTICIP	    = 'Address';
	const SERVICE_TYPE_AWS_VPC_NETWORKACL	    = 'ACL';
	const SERVICE_TYPE_AWS_VPC_SECURITYGROUP	    = 'SecurityGroup';
	const SERVICE_TYPE_AWS_VPC_CUSTOMERGATEWAY	    = 'CustomerGateway';
	const SERVICE_TYPE_AWS_VPC_VIRTUALPRIVATEGATEWAY	    = 'VPGateway';
	const SERVICE_TYPE_AWS_VPC_VPNCONNECTION	    = 'VPN';
	const SERVICE_TYPE_AWS_DB_SUBNET	    = 'DBSubnet';
	
	//Auto Scaling
	const SERVICE_TYPE_AWS_AUTOSCALING	    = 'AutoScaling';
	const SERVICE_TYPE_AWS_LAUNCHCONFIG	    = 'LaunchConfiguration';
	
	//GCE
 	const SERVICE_TYPE_GCE_VMS    = 'VMS';
	const SERVICE_TYPE_DISKS  =  'Disks';
	const SERVICE_TYPE_NETWORKS  =  'Netoworks';
	const SERVICE_TYPE_LOADBALANCING  =  'Loadbalancing';
	const SERVICE_TYPE_ZONES  =  'Zones';
	const SERVICE_TYPE_OPERATIONS  =  'Operations';
 }