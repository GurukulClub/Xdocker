<div class="tabbable">  
	<ul id="tabs" class="nav nav-tabs xervmon-contentTab text-left" data-tabs="tabs">  
		<!--
			<li class="active"><a href="#xervmonStatus" data-toggle="tab">Xervmon Status</a></li>  
		-->
		<li class="active"><a href="#amazonAws" data-toggle="tab">Amazon AWS Status</a></li>  
		<li class=""><a href="#rackspace" data-toggle="tab">Rackspace Cloud</a></li> 
		<li class=""><a href="#openstack" data-toggle="tab">OpenStack</a></li>  
	</ul>  
	<div class="tab-content">  
		<!--
		<div class="tab-wpane active" id="xervmonStatus">  
			<p> No downtime for Xervmon.</p>  
			
		</div>
		-->  
		<div class="tab-wpane active" id="amazonAws">  
			<iframe src="http://status.aws.amazon.com" width="100%" height="400" frameborder="0"> </iframe>
     
		</div>  
		<div class="tab-wpane" id="rackspace">  
			<iframe src="http://status.rackspace.com" width="100%" height="400" frameborder="0"> </iframe>
		</div>
		<div class="tab-wpane" id="openstack">  
			<iframe src="http://opencloudconsortium.org/tag/openstack/feed/" width="100%" height="400" frameborder="0"> </iframe>
		</div>  
	</div> 
</div>
<style>
/*
 .tab-wpane
 {
	height:400px !important;
	min-height:400px !important;
	overflow:auto;
 }*/
</style>
