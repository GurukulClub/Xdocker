<?php
//echo '<pre>';
//print_r($menu_items);
//die();
//'lang:global:profile'
$prof = array();
if (isset($menu_items[lang('global:profile')])) {
    $prof = $menu_items[lang('global:profile')];
    unset($menu_items[lang('global:profile')]);
}
?>

Welcome, <?=$this -> current_user -> display_name; ?>
<span class="settings"></span>
<div class="settingsDetail">
    <ul class="clean">
        <li>
            <a href="<?=site_url() ?>/admin/UserProfile/edit"> <span class="editProfile"></span> <?=lang('edit_profile_label') ?> </a>
        </li>
        <li>
            <a href="<?=site_url(); ?>/admin/logout"> <span class="logout"></span> <?=lang('logout_label') ?> </a>
        </li>
    </ul>
</div>
