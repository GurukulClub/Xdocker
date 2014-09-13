<?php
    Asset::js('jquery/jquery-1.10.2.min.js');
    Asset::js('jquery/jquery-migrate-1.2.1.min.js');
    Asset::js_inline('jQuery.noConflict();');
    Asset::js('jquery/jquery-ui-1.10.3.custom.min.js');
    Asset::js('jquery/jquery.colorbox.js');
    Asset::js('jquery/jquery.cooki.js');
    Asset::js('jquery/jquery.slugify.js');
    Asset::js('jquery/chosen.jquery.min.js');

    Asset::js('bootstrap/bootstrap.js');

    // Flat-UI
    Asset::js(array(
        'bootstrap/flat/flatui-checkbox.js',
        'bootstrap/flat/flatui-radio.js',
        'bootstrap/flat/bootstrap-select.js',
        'bootstrap/flat/bootstrap-switch.js',
        'bootstrap/flat/jquery.tagsinput.js',
        'bootstrap/flat/jquery.ui.touch-punch.min.js',
        'bootstrap/flat/jquery.placeholder.js',
        'bootstrap/flat/jquery.stacktable.js',
         /* 'bootstrap/flat/application.js', */
    ));

    Asset::js('bootstrap/bootstrap-colorpicker.min.js');

    Asset::js(array(
        'codemirror/codemirror.js',
        'codemirror/mode/css/css.js',
        'codemirror/mode/htmlmixed/htmlmixed.js',
        'codemirror/mode/javascript/javascript.js',
        'codemirror/mode/markdown/markdown.js',
        'plugins.js',
        'scripts.js'
    ));

    /* Viewport height and width units pollyfill - disabled in favour of a custom solution for the layout where we need it
    Asset::js('misc/vimtokenizer.js');
    Asset::js('misc/vimparser.js');
    Asset::js('misc/vminpoly.js');*/

    Asset::js('bootstrap/date.js');
?>

<?php
    if (isset($analytic_visits) OR isset($analytic_views)) :
        Asset::js('jquery/jquery.excanvas.min.js');
        Asset::js('jquery/jquery.flot.js');
    endif;
?>

<?php Asset::css('bootstrap/bootstrap-modal.css'); ?>
<?php Asset::js('bootstrap/bootstrap-modal.js'); ?>
<?php Asset::js('bootstrap/bootstrap-modalmanager.js'); ?>

<?php Asset::js('bootstrap/bootbox.min.js'); ?>
<?php Asset::js('jquery/jquery.dataTables.min.js'); ?>
<?php Asset::js('jquery/flotr2.min.js'); ?>

<?php Asset::js('jquery/jquery-sDashboard.js'); ?>
<?php Asset::js('misc/xervmon_utils.js'); ?>
<?php Asset::css('visualization/nv.d3.css')
?>
<?php Asset::js('misc/visualization/d3.v3.js')
?>
<?php Asset::js('misc/visualization/nv.d3.js')
?>

<?php Asset::js('misc/visualization/topojson.v1.min.js')
?>
<?php Asset::js('misc/visualization/datamaps.all.min.js')
?>

<?php Asset::css('jquery/jquery.tablesorter.pager.css')
?>
<?php Asset::css('bootstrap/tablesorter.theme.bootstrap.css')
?>
<?php Asset::js('jquery/jquery.tablesorter.js')
?>
<?php Asset::js('jquery/jquery.tablesorter.pager.js')
?>
<?php Asset::js('jquery/jquery.tablesorter.widgets.js')
?>

<?php  Asset::js('misc/selectivizr-min.js'); ?>

<?php Asset::css('jquery/atooltip.css'); ?>
<?php Asset::css('common/responsiveStyle.css'); ?>
<?php Asset::css('common/style.css'); ?>

<?php Asset::js('jquery/jquery.atooltip.js'); ?>
<?php Asset::js('misc/script.js'); ?>

<?php Asset::css('bootstrap/bootstrap-editable.css'); ?>
<?php Asset::js('bootstrap/bootstrap-editable-min.js'); ?>

<?php Asset::js('pnotify/jquery.pnotify.min.js'); ?>

<?php Asset::css('pnotify/jquery.pnotify.default.css'); ?>

<?php
    // var_dump("Module", $this -> module);
    //echo var_dump("Router stuff", $this -> router, $this -> router -> class, $this -> router -> method);
    // echo "Current Module: ".$this->router->fetch_module()." ;";
    // $moduleName = $this -> router -> fetch_module();
    if ($this -> module == '' && $this -> router -> class == 'admin' && $this -> router -> method == 'index') {
        // Load this script only on the dashboard (admin/index)
        Asset::add_path('xervmon_widgets', 'addons/shared_addons/modules/xervmon_widgets/');
        Asset::js('xervmon_widgets::xervmon_widget.js');
        Asset::css('bootstrap/bootstrap-tour.min.css');
        Asset::js('bootstrap/bootstrap-tour.min.js');
    }
?>

<?php file_partial('headerJS'); ?>

<?php Asset::css(array(
        'plugins.css',
        'jquery/colorbox.css',
        'codemirror.css',
        'animate/animate.min.css'
    ));
?>

<?php echo Asset::render(); ?>

<!--[if lt IE 9]>
<?php echo Asset::css('ie8.css', null, 'ie8'); ?>
<?php echo Asset::render_css('ie8'); ?>
<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<?php if ($module_details['sections']):?>
<style>
</style>
<?php endif; ?>

<?php echo $template['metadata']; ?>
