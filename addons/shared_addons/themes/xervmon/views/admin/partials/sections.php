<?php
    require_once SHARED_ADDONPATH . 'helpers/SubMenuHelper.php';
?>
<?php if (count($module_details['sections']) > 0) : foreach ($module_details['sections'] as $name => $section): ?>
    <?php if(isset($section['name']) && isset($section['uri'])): ?>
        <li class="<?php if ($name === $active_section) echo 'active' ?>">
            <div class="dropdown">
                <a  href="<?php echo site_url($section['uri']); ?>" <?php echo isset($section['onclick'])? ' onclick="return '.$section['onclick'].';" ':'' ?> >
                    <?php echo (lang($section['name']) ? lang($section['name']) : $section['name']); ?>
                </a>
                <?php if(!empty($section['shortcuts'])) { ?>
                    <a class="dropdown-toggle" id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="<?php echo site_url($section['uri']); ?>">
                        <b class="caret"></b>
                    </a>
                <?php } ?>

                <?php if ($name === $active_section): ?>
                    <!-- <?php echo Asset::img('admin/section_arrow.png', ''); ?> -->
                <?php endif; ?>

                <?php if(!empty($section['shortcuts'])): ?>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                        <?php
                            $shotcuts = $section['shortcuts'];

                            foreach ($shotcuts as $shortcut)  {
                                $nested = (isset($shortcut['nested']) && is_array($shortcut['nested'])) ? $shortcut['nested'] : '';
                         ?>
                             <li <?php echo (!empty($nested))?' class="dropdown-submenu" ':''; ?> >
                                    <a  href="<?php echo site_url($shortcut['uri']); ?>" <?php echo isset($shortcut['onclick'])? ' onclick="'.$shortcut['onclick'].';" ':'' ?> >
                                        <?php echo (lang($shortcut['name']) ? lang($shortcut['name']) : $shortcut['name']); ?>
                                    </a>

                                    <?php
										if(!empty($nested)) {
								    ?>
											<ul class="dropdown-menu">
												<?php
												foreach($nested as $nArray) {
												?>
    												<li >
    													<a  href="<?php echo site_url($nArray['uri']); ?>" <?php echo isset($nArray['onclick'])? ' onclick="'.$nArray['onclick'].';" ':'' ?> >
                                            				<?php echo (lang($nArray['name']) ? lang($nArray['name']) : $nArray['name']); ?>
                                        				</a>

    												</li>

												<?php
												}
												 ?>
											</ul>
								    <?php
										}
									?>
                        	   </li>
                        	<?php
                        		}
                	        ?>
                    </ul>
                <?php endif; ?>
            </div>
            <span class="divider"></span>
        </li>
    <?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>

