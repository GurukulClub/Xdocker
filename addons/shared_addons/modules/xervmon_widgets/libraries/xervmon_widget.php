<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Permissions Plugin
 *
 * Methods that help determine a user's correct permission level
 *
 * @author        PyroCMS Dev Team
 * @package        PyroCMS\Core\Modules\Variables\Plugins
 */

interface Xervmon_Widget {
    public function table($params);

    public function chart($params);

    public function barchart($params);

    public function html($params);

    public function map_bubbles($params);

    public function count_boxes($params);
}
