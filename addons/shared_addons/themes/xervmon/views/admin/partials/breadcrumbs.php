 <?php require_once(SHARED_ADDONPATH.'helpers/SubMenuHelper.php'); ?>
<?php  if($module_details['slug'] == '') : ?>
<ul class="breadcrumb">
    <li><a href="<?php echo site_url().'/admin/dashboard' ?>" ><?php echo('Dashboard'); ?></a><span class="divider"></span></li>
</ul>
<?php elseif(!empty($module_details['slug'])) : ?>
<ul class="breadcrumb">
        <li class="active"><?php echo lang('cp:nav_' . $module_details['menu']); ?><span class="divider">/</span><a href=<?php echo 'index.php/admin/' . $module_details['slug'] ?> ><?php echo $module_details['name']; ?></a></li>
    <?php
        $url = SubMenuHelper::getCurrentUrl();
        $subIndex = strrpos($url, $module_details['slug']);
        if($subIndex !== FALSE) {
            $subIndex += strlen($module_details['slug']) + 1;
            $parts = explode('/', substr($url, $subIndex));
            $pathString = '';
            $lastIndex = count($parts) - 1;
            $index = 0;
            foreach($parts as $item) {
                if(!$item) continue;
                if($lastIndex===$index && is_numeric($item)) continue;

                $pathString .= '/'.$item;

                echo '<li class="active">'.lang('cp:nav_' . $item).'<span class="divider"> / </span>';

                if($lastIndex!==$index)  echo '<a href="index.php/admin/' . $module_details['slug'].$pathString.'" >';

                echo ucfirst($item);

                if($lastIndex!==$index) echo '</a>';

                echo '</li>';

                $index++;
            }
        }
    ?>
</ul>
<?php endif; ?>
