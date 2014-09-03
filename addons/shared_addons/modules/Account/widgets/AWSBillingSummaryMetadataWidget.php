<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Permissions Plugin
 *
 * Methods that help determine a user's correct permission level
 *
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Modules\Variables\Plugins
 */
require_once SHARED_ADDONPATH . 'modules/xervmon_widgets/libraries/abstract_xervmon_widget.php';

class  AWSBillingSummaryMetadataWidget extends Abstract_Xervmon_Widget 
{
    private $ci;
    public function __construct() 
    {
        parent::__construct();
        $this -> ci = ci();
    }

    public function table($params) 
    {
		$this->ci->load->library('reports_builder/AWSBilling');
		$awsBilling = new AWSBilling($this->ci->load->model('amznaccount/amznaccount_m'));
		return $awsBilling->getMetadata();
    }
}
