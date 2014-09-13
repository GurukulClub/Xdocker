<noscript>
    <span>Xervmon requires that JavaScript be turned on for many of the functions to work correctly. Please turn JavaScript on and reload the page.</span>
</noscript>

<!--
{{ if global:environment == 'production' }}
-->
    <?php //$this->template->enable_minify(true);?>
<!--
{{ endif }}
-->
<div class="navbar navbar-inverse navbar-fixed-top clean" dir=<?php $vars = $this->load-> get_vars(); echo $vars['lang']['direction'] ?>>
    <div class="navbar-inner">
        <div class="container-fluid">
            <logo class="logoInside">
                <?php echo  Asset::img('logo.png', 'view site')?>
                <small><?php echo CMS_VERSION . ' ' . CMS_EDITION; ?></small>
            </logo>
            <!-- <?php echo anchor('https://www.xervmon.com','<span id="xervmon-logo"></span>', 'target="_blank"') ?>-->
            <ul class="xervmon-topList">
                <li class="xervmon-search">
                    <script type="text/javascript">
                        jQuery(function($) {
                            $(function() {
                                var cache = {}, lastXhr;
                                $(".js-search-query").autocomplete({
                                    minLength : 2,
                                    delay : 300,
                                    source : function(request, response) {
                                        var term = request.term;
                                        if ( term in cache) {
                                            response(cache[term]);
                                            return;
                                        }

                                        lastXhr = $.getJSON(SITE_URL + 'admin/search/ajax_autocomplete', request, function(data, status, xhr) {
                                            cache[term] = data.results;
                                            if (xhr === lastXhr) {
                                                response(data.results);
                                            }
                                        });
                                    },
                                    focus : function(event, ui) {
                                        // $("#searchform").val( ui.item.label);
                                        return false;
                                    },
                                    select : function(event, ui) {
                                        window.location.href = ui.item.url;
                                        return false;
                                    }
                                }).data("uiAutocomplete")._renderItem = function(ul, item) {
                                    return $("<li></li>").data("item.autocomplete", item).append('<a href="' + item.url + '">' + item.title + '</a><div class="keywords">' + item.keywords + '</div><div class="singular">' + item.singular + '</div>').appendTo(ul);
                                };
                            });
                        });
						
						
						  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
						  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
						  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
						  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

						  ga('create', 'UA-44775860-1', 'xervmon.com');
						  ga('send', 'pageview');
                    </script>
                    <input type="text" class="js-search-query input-large" id="nav-search" placeholder="Search" ontouchstart="" style="visibility:hidden">
                <!--    <i class="icon-search no-bootstrap-icon"></i>-->
                </li>
                <!--
                   TODO: Commented to hide this for V1.5. Need to implemente this for V2.0
                  <li class="xervmon-priority">
                    <a href="#highPriority" class="blink">2 High priority actions</a>
                </li>
                 -->
				<?php if( ($this->module_m->Installed($slug='hpcsi_reports')) == 1) : ?>
        			<li><div class="xervmon-contentBottom"><i style="padding-bottom:8px;" class="xervmon-hpIcon xervmon-socialIcons"></i></div></li>
   				<?php  elseif( ($this->module_m->Installed($slug='hpcsi_reports')) != 1) : ?>
					<li></li>
   				<?php endif; ?>

                <li class="xervmon-user">
                    <?php file_partial('navigation') ?>
                </li>
            </ul>
        </div>
    </div>
</div>
<style> logo.logoInside img {margin-top: 0px ! important;} </style>

