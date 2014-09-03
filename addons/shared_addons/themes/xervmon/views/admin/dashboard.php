<!-- Add an extra div to allow the elements within it to be sortable! -->
<style>
    .img {
        background-color: #E7EBEF;
        border: 1px solid #E3E3E3;
        border-radius: 4px 4px 4px 4px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05) inset;
        margin-bottom: 20px;
        min-height: 20px;
        padding: 20px 10px 24px;
    }
    #quick_links img {
        width: 48px;
        height: 48px;
    }
    .img-small {
        max-width: 48px;
        max-height: 36px;
    }
    .inline-block {
        display: inline;
        display: inline-block;
    }
    .row-fluid-two-column [class*="span"]{
        margin: 0px;
        padding: 10px;
        -ms-box-sizing: border-box;
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }
    .row-fluid-two-column .span12 {
        margin-left:0px;
        clear:both;
    }
    .margin5 {
        margin: 5px;
    }
    .text-center {
        text-align: center;
    }
    .text-left {
        text-align: left;
    }
    .padding5 {
        padding:5px;
    }
    .ul-icon-block > li{
        display: inline-block;
    }
    .plain-label > li > a, .plain-label label{
        text-transform: initial;
        font-weight: normal;
    }
    .launchpadIcons img{
        max-height: 100%;
        -webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
        -moz-box-sizing: border-box;    /* Firefox, other Gecko */
        box-sizing: border-box;
    }
</style>

<div class="tabMain">
    <div id="dashboardTabHolder" class="tabMainTop"></div>
</div>
<br/>
<div id="sortable">

    <!-- Dashboard Widgets -->
    {{ widgets:area slug="dashboard" }}


    <!-- Begin Recent Comments -->
    <?php if (isset($recent_comments) AND is_array($recent_comments) AND $theme_options->pyrocms_recent_comments == 'yes') : ?>
    <div class="one_half">

         <div class="accordion-group draggable ">
            <div class="accordion-heading">
                <h4><?php echo lang('comments:recent_comments') ?></h4>
                <a class="pull-right toggle-accordion margin5 " data-toggle="tooltip" title="Toggle this element"><i class="icon-chevron-up"></i></a>
            </div>

            <section class="accordion-body collapse in lst">
<div class="Quicklink">
                <div class="content">
                    <ul id="widget-comments">

                        <?php if (count($recent_comments)): ?>
                            <?php foreach ($recent_comments as $comment): ?>
                                <li>
                                    <div class="comments-gravatar"><?php echo gravatar($comment->user_email) ?></div>
                                    <div class="comments-date"><?php echo format_date($comment->created_on) ?></div>
                                    <p>
                                        <?php echo sprintf(lang('comments:list_comment'), $comment->user_name, $comment->entry_title) ?>
                                        <span><?php echo (Settings::get('comment_markdown') AND $comment->parsed > '') ? strip_tags($comment->parsed) : $comment->comment ?></span>
                                    </p>
                                </li>
                            <?php endforeach ?>
                        <?php else: ?>
                            <?php echo lang('comments:no_comments') ?>
                        <?php endif ?>
                    </ul>
                </div></div>
            </section>
        </div>
    </div>
    <?php endif ?>
    <!-- End Recent Comments -->





    <?php if ((isset($analytic_visits) OR isset($analytic_views)) AND $theme_options->pyrocms_analytics_graph == 'yes'): ?>
    <script type="text/javascript">

    $(function($) {
            var visits = <?php echo isset($analytic_visits) ? $analytic_visits : 0 ?>;
            var views = <?php echo isset($analytic_views) ? $analytic_views : 0 ?>;

            var buildGraph = function() {
                $.plot($('#analytics'), [{ label: 'Visits', data: visits },{ label: 'Page views', data: views }], {
                    lines: { show: true },
                    points: { show: true },
                    grid: { hoverable: true, backgroundColor: '#fefefe' },
                    series: {
                        lines: { show: true, lineWidth: 1 },
                        shadowSize: 0
                    },
                    xaxis: { mode: "time" },
                    yaxis: { min: 0},
                    selection: { mode: "x" }
                });
            }
            // create the analytics graph when the page loads
            buildGraph();

            // re-create the analytics graph on window resize
            $(window).resize(function(){
                buildGraph();
            });

            function showTooltip(x, y, contents) {
                $('<div id="tooltip">' + contents + '</div>').css( {
                    position: 'absolute',
                    display: 'none',
                    top: y + 5,
                    left: x + 5,
                    'border-radius': '3px',
                    '-moz-border-radius': '3px',
                    '-webkit-border-radius': '3px',
                    padding: '3px 8px 3px 8px',
                    color: '#ffffff',
                    background: '#000000',
                    opacity: 0.80
                }).appendTo("body").fadeIn(500);
            }

            var previousPoint = null;

            $("#analytics").bind("plothover", function (event, pos, item) {
                $("#x").text(pos.x.toFixed(2));
                $("#y").text(pos.y.toFixed(2));

                    if (item) {
                        if (previousPoint != item.dataIndex) {
                            previousPoint = item.dataIndex;

                            $("#tooltip").remove();
                            var x = item.datapoint[0],
                                y = item.datapoint[1];

                            showTooltip(item.pageX, item.pageY,
                                        item.series.label + " : " + y);
                        }
                    }
                    else {
                        $("#tooltip").fadeOut(500);
                        previousPoint = null;
                    }
            });

        });
    </script>
    <div class="one_full">
        <section class="title">
            <h4>Statistics</h4>
        </section>
        <section class="item">
            <div class="content">
                <div id="analytics"></div>
            </div>
        </section>
    </div>

    <?php endif ?>
    <!-- End Analytics -->

    <!-- Begin RSS Feed -->
    

    <?php if ( isset($rss_items) AND $theme_options->pyrocms_news_feed == 'yes') : ?>
	
    <div id="feed" class="one_half">
          <div class="accordion-group draggable ">
        <div class="accordion-heading">
            <h4><?php  echo 'Activity Stream';//echo lang('cp:news_feed_title') ?></h4>
            <a class="pull-right toggle-accordion margin5 " data-toggle="tooltip" title="Toggle this element"><i class="icon-chevron-up"></i></a>
        </div>

        <section class="item">
		<div class="">
            <div class="accordion-body collapse in lst" style="overflow: auto;">
                <ul class="media-list text-left">
                    <?php
                    //echo '<pre>';
                    //print_r($rss_items);
                    //die();
                    foreach($rss_items as $rss_item): ?>
                    <li class="media">

                        <?php
                            $item_date    = strtotime($rss_item->get_date());
                            $item_month = date('M', $item_date);
                            $item_day    = date('j', $item_date);
                        ?>

                        <div class="pull-left">
                            <div class="date">
                                <div class="time">
                                    <span class="month">
                                        <?php echo $item_month ?>
                                    </span>
                                    <span class="day">
                                        <?php echo $item_day ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="post media-body">
                            <h4 class="media-heading"><?php echo anchor($rss_item->get_permalink(), $rss_item->get_title(), 'target="_blank"') ?></h4>

                            <p class='item_body'><?php echo $rss_item->get_description() ?></p>
                        </div>
                    </li>
                    <?php endforeach ?>
                </ul>
            </div></div>
        </section>
                  </div> 
    </div>
    <?php endif ?>
    <!-- End RSS Feed -->

	    <div class="one_half">

         <div class="accordion-group draggable ">
            <div class="accordion-heading">
                <h4><?php echo 'Xervmon Unified Cloud Console &trade;' ?></h4>
                <a class="pull-right toggle-accordion margin5 " data-toggle="tooltip" title="Toggle this element"><i class="icon-chevron-up"></i></a>
            </div>

            <section class="accordion-body collapse in lst">

                <div class="content text-left">
                	<?php file_partial('dashboard/CloudConsole'); ?>
                </div>
            </section>
        </div>
    </div>

	 <div class="one_half">

       <div class="accordion-group draggable ">
            <div class="accordion-heading">
                <h4><?php echo 'Service Status' ?></h4>
                <a class="pull-right toggle-accordion margin5 " data-toggle="tooltip" title="Toggle this element"><i class="icon-chevron-up"></i></a>
            </div>

            <section class="accordion-body collapse in lst">

                <div class="content">
                	
                    <div class="content text-left">
                	<?php file_partial('dashboard/status'); ?>
                	</div>
                </div>
            </section>
        </div>
    </div> 
	
	<!--
	<div class="one_half">

       <div class="accordion-group draggable ">
            <div class="accordion-heading">
                <h4><?php echo 'Users Management & Service Desk' ?></h4>
                <a class="pull-right toggle-accordion margin5 " data-toggle="tooltip" title="Toggle this element"><i class="icon-chevron-up"></i></a>
            </div>

            <section class="accordion-body collapse in lst">

                <div class="content">
                	
                    <div class="content text-left">
                	<?php //file_partial('dashboard/quicklinksDocs'); ?>
                	</div>
                </div>
            </section>
        </div>
    </div> 
	-->
   
    <!-- Begin Quick Links -->
   <?php file_partial('dashboard/quicklinks'); ?>
    <!-- End Quick Links -->
</div>
<!-- End sortable div -->
 <?php file_partial('dashboard/widgetMenu'); ?>
