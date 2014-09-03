<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 * @author  Xervmon Dev Team
 * @package Xervmon\Core\Modules\managecloud\Controllers
 */

require_once (SHARED_ADDONPATH . 'helpers/UserHelper.php');
class Admin extends Admin_Controller {
    /** @var string The current active section */
    protected $section = 'xervmon_widgets';

    /**
     * Every time this controller controller is called should:
     * - load the blog and blog_categories models
     * - load the keywords and form validation libraries
     * - set the hours, minutes and categories template variables.
     */
    public function __construct() {
        parent::__construct();

        $this -> lang -> load(array('xervmon_widgets'));
        $this -> load -> config('xervmon_widgets/xervmon_widgets');
        $this -> load -> model($this -> config -> item('models'));
    }

    public function add() {
        echo 'demo widget';
    }

    public function edit($id) {

    }

    public function index() {

    }

    /* Get the list of custom tabs for the current user */
    public function getDashboardTabs() {
        $result = $this -> xervmon_widget_user_tab_m -> get_tabs($this -> current_user -> id);
        print(json_encode(array(
            'status' => 'success',
            'message' => $result
        )));
    }

    /* Create a new tab */
    public function addNewTab() {
        $tabName = $this -> input -> post('tab_name');
        $success = $this -> xervmon_widget_user_tab_m -> add($tabName);
        print(json_encode(array(
            'status' => !empty($success) ? 'success' : 'error',
            'message' => array("id" => $success)
        )));
    }

    /* Delete a tab */
    public function deleteTab() {
        $tabID = $this -> input -> post('id');
        $success = $this -> xervmon_widget_user_tab_m -> delete($tabID);
        //Also delete related widgets
        $widgets = $this -> xervmon_user_widget_m -> getWidgetsByTabID($tabID);
        foreach ($widgets as $widget) {
            $success = $success && $this -> xervmon_user_widget_m -> delete($widget -> id);
        }
        print(json_encode(array(
            'status' => !empty($success) ? 'success' : 'error',
            'message' => "Deleted successfully"
        )));
    }

    /* Get a list of widget types */
    public function getWidgetTypes() {
        $get = $this -> input -> get();
        if(empty($get))
        {
             print(json_encode(array(
                'status' =>  'error',
                'message' => array()
            )));
            return;
        }
        $widgetType = $this -> input -> get('widget_type');
        $widgets = $this -> xervmon_widget_definition_m -> getWidgetsByType($widgetType);
        $enabledWidgets = array();
        if(!empty($widgets))
        {

            foreach($widgets as $widget)
            {
                $widgetModule = $widget -> widget_module;
                $slugArray = explode("/", $widgetModule);
                if(!empty($slugArray) && isset($slugArray[0]))
                {
                    if((array_key_exists($slugArray[0], $this->permissions) OR $this->current_user->group == 'admin')
                    AND module_enabled($slugArray[0]))
                    {
                        $enabledWidgets[] = $widget;
                    }
                }
                else
                {
                    log_message('error', 'Slug cannot determined with the widget '. $widget -> title);
                }

            }
        }
        print(json_encode(array(
            'status' => !empty($enabledWidgets) ? 'success' : 'error',
            'message' => $enabledWidgets
        )));
    }

    /* Get details for a widget type (a.k.a get the widget definition) */
    public function getWidgetTypeDetails() {
        $widgetTypeID = $this -> input -> get('id');
        $widgetDefinition = $this -> xervmon_widget_definition_m -> get($widgetTypeID);
        print(json_encode(array(
            'status' => !empty($widgetDefinition) ? 'success' : 'error',
            'message' => $widgetDefinition
        )));
    }

    /* Get all widgets belonging to a certain tab */
    public function getWidgetsForTab() {
        $tabID = $this -> input -> get('tab_id');
        $widgets = $this -> xervmon_user_widget_m -> getWidgetsByTabID($tabID);
        print(json_encode(array(
            'status' => !empty($widgets) ? 'success' : 'error',
            'message' => $widgets
        )));
    }

    /* Delete a widget */
    public function deleteWidget() {
        $widgetID = $this -> input -> post('id');
        $success = $this -> xervmon_user_widget_m -> delete($widgetID);
        print(json_encode(array(
            'status' => !empty($success) ? 'success' : 'error',
            'message' => "Deleted successfully"
        )));
    }

    /* Add a new widget */
    public function addNewWidget() {
        $tabID = $this -> input -> post('tab_id');
        $widgetTypeID = $this -> input -> post('widget_type_id');
        $widgetID = $this -> xervmon_user_widget_m -> addWidgetOfType($tabID, $widgetTypeID, $this -> xervmon_widget_definition_m -> get($widgetTypeID));
        print(json_encode(array(
            'status' => !empty($widgetID) ? 'success' : 'error',
            'message' => $this -> xervmon_user_widget_m -> get($widgetID)
        )));
    }

    public function getWidgetFormData() {
        $widgetID = $this -> input -> get('id');
        $widget = $this -> xervmon_user_widget_m -> get($widgetID);
        $widgetDefinition = array();
        if ($widget) {
            $widgetDefinition = $this -> xervmon_widget_definition_m -> get($widget -> widget_id);
        }
        print(json_encode(array(
            'status' => !empty($widget) ? 'success' : 'error',
            'message' => array(
                "widget" => $widget,
                "definition" => $widgetDefinition,
            )
        )));
    }

    // Set a user widget's title
    public function setWidgetTitle() {
        $widgetID = $this -> input -> post('pk');
        $title = $this -> input -> post('value');
        $success = $this -> xervmon_user_widget_m -> setWidgetTitle($widgetID, $title);
        print(json_encode(array(
            'status' => !empty($success) ? 'success' : 'error',
            'message' => $success
        )));
    }

    // Set a user widget's custom options
    public function setWidgetOptions() {
        $widgetID = $this -> input -> post('id');
        $options = $this -> input -> post('value');
        if (!is_string($options)) {
            $options = json_encode($options);
        }
        $success = $this -> xervmon_user_widget_m -> setWidgetOptions($widgetID, $options);
        print(json_encode(array(
            'status' => !empty($success) ? 'success' : 'error',
            'message' => $success
        )));
    }

    // Get content for the given widget ID
    public function getWidgetContent() {
        $widgetID = $this -> input -> get('id');
        $widget = $this -> xervmon_user_widget_m -> get($widgetID);
        $widgetDefinition = array();
        if ($widget) {
            $widgetDefinition = $this -> xervmon_widget_definition_m -> get($widget -> widget_id);
        }
        print(json_encode(array(
            'status' => !empty($widget) ? 'success' : 'error',
            'message' => array(
                "widget" => $widget,
                "widgetDefinition" => $widgetDefinition,
                "content" => $widget ? $this -> renderWidget((array)$widgetDefinition) : ""
            )
        )));
    }

    private function renderWidget($params) {

        require_once (SHARED_ADDONPATH . 'modules/' . $params['widget_module']);
        $class = $params['widget_module_class'];
        //echo $class; die();

        $obj = new $class();
        if (!empty($params) && isset($params['widget_type'])) {
            switch($params['widget_type']) {
                case "table" :
                    $data = $obj -> table($params);
                    break;
                case "chart" :
                    $data = $obj -> chart($params);
                    break;
                case "barchart" :
                    $data = $obj -> barchart($params);
                    break;
                case "count_boxes" :
                    $data = $obj -> count_boxes($params);
                    break;
                case "map_bubble" :
                case "map_bubble_hosts" :
                case "map_bubble_services" :
                    $data = $obj -> map_bubbles($params);
                    break;
                default :
                case "html" :
                    $data = $obj -> html($params);
                    break;
            }
        }
        return $data;
    }

}
