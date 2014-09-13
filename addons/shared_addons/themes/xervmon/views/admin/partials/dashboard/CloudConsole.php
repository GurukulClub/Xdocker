<div class="tabbable">  
	<ul id="tabs" class="nav nav-tabs xervmon-contentTab text-left" data-tabs="tabs" >  
		<li class="active"><a href="#cloud" data-toggle="tab">Cloud</a></li>  
		<li><a href="#deployment" data-toggle="tab">Deployment Designer</a></li>  
		<li><a href="#system" data-toggle="tab">System Monitor</a></li>  
	</ul>  
		<div id="my-tab-content" class="tab-content"> 
		<div class="tab-wpane active" id="cloud">  
			<p>
			<h4>
				<div style="width:91%;">
						Steps to start managing cloud console across all providers</div> 
		         </h4>
			<ul>
		
				<li>
					1. <strong>Account Management for Your providers</strong>
					Retrieve your account credentials for your cloud providers and make sure, the credentials 
					have correct access to create/edit and delete assets on the cloud provider for ease of control.
				</li>
				<br/>
				<li>
					2. <strong>Image Management for Amazon AWS</strong>
					If your provider is Amazon AWS, then make sure the images from your accounts are synced with XervPaaS repository. XervPaaS is responsible for
					orechestration and depoyment of work loads and automation. Hence the images that need to be supported, XervPaaS need to have access to the images.
					This way, users on Xervmon managemenent do not need to spend time chosing their images all the time. 
					
				</li>
				<br/>
				<li>
					3. <strong> Key Management</strong> 
					With Xervmon, you can create key pairs or you can upload your keys. Then we provide capabilities to sync your keys across providers, there by delivering portability 
					and ease of management across cloud providers.
					
				</li>
				<br/>
			</ul>
		
		</p>  
		</div>  
		<div class="tab-wpane" id="deployment">  
		<p>
			<h4>
				<div style="width:91%;">
						Deploy, Clone/Blueprint/Migrate your assets from one provider to another (regions/AZs)</div> 
		         </h4>
			<ul>
		
				<li>
					1. <strong>Select your provider and then Drag & Drop IaaS Asset</strong>
					Provisioniong & Deployment is very easy with drag and drop feature. Then you configure and size the deployment, select the account and region and provision.
					Then track the progress on Log Manager. It is all seamless and does not need user intervention. We also support XervmonBroker Installation during the booting up servers.
				</li>
				<li>
					2. <strong>Clone</strong>
					You can clone an existing/completed deployment. On clone, a blue print of the deployed assets are created within Xervmon repository. Then you can
					either migrate them to another region within the same provider or migrate them to another provider completely.
					
				</li>
				<li>
					3. <strong> Migration</strong> 
					Either cloned or original deployment(draft status) can be migrated from one provider to another.
					
				</li>
		
			</ul>
		
		</p>  
		</div>  
		<div class="tab-wpane" id="system">  
		<p>
			<h4>
				<div style="width:91%;">
						Steps to download, Install XervmonBroker for monitoring</div> <span class="span1"> 
							
		             </span>
		         </h4>
			<ul>
		
				<li>
					1. Grab the Username & Xervmon Key from <a href="admin/UserProfile/edit"> Edit Profile</a>
				</li>
				<li>
					2. The Tenant identifier for your account can be grabbed from the URL (https://xyzcorp.xervmon.com/admin/login) from your browser. <br/>
					<strong>xyzcorp</strong> is the tenant Identifier</br> <strong>Linux OS</strong>
					<br/>
					Option 1 : Use the Python script available from </br>
					<a href="https://github.com/sseshachala/xervmonbroker_agents/blob/master/xervmon_install.py">Xervmon_Installer</a>
					python xervmon_install.py --key=api_key --user=username --tenant=tenant_name	</br>
			
					Option 2: Down load the agent for your favorite distro (yum or deb based or tar.gz)</br>
					<a href="https://github.com/sseshachala/xervmonbroker_agents/raw/master/distros/v3.0/xervmon-broker-agent_1.2.3i2-2_all.deb"> DEB </a></br>
					<a href="https://github.com/sseshachala/xervmonbroker_agents/raw/master/distros/v3.0/xervmon_broker-agent-1.2.3i2-1.noarch.rpm"> YUM </a></br>
					</br>
					Then - <a href="http://xyzcorp.xervmon.com/index.php/admin/SystemMonitor/index"> enable your host machine for monitoring by providing the host name and ip address </a>
			
					</a>
					</li>
		
			</ul>
		
		</p>  
		</div>  
	</div> 
</div>
<style>
h4 {
	display: flex;
	display: -webkit-box;
	display: -ms-flexbox;
}
/*
 .tab-wpane{
	height:300px !important;
min-height:300px !important;
overflow:auto;
}*/
</style>