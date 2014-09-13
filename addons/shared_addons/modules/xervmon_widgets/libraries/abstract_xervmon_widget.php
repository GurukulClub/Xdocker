<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Xervmon Widgets
 *
 * Methods that help determine a user's correct permission level
 *
 * @author        Xervmon Dev Team
 * @package        Xervmon\Core\Modules\xervmon_widgets
 */
require_once 'xervmon_widget.php';
class  Abstract_Xervmon_Widget implements Xervmon_Widget {
    //private $_widget_locations = array();

    public function __construct() {

    }

    public function table($params) {
        //Implementation
    }

    public function chart($params) {
        //implementation
    }
    public function barchart($params) {
        //implementation
    }

    public function html($params) {
        //Implementation
    }

    public function map_bubbles($params) {

    }

    public function count_boxes($params) {
        //implementation
    }

}
