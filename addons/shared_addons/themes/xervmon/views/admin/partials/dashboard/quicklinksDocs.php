<div class="tabbable">  
	<ul id="tabs" class="nav nav-tabs xervmon-contentTab text-left" data-tabs="tabs">  
		<li class="active"><a href="#users" data-toggle="tab">Users, Groups/Roles & Permissions</a></li>  
		<li><a href="#service" data-toggle="tab">Service Desk</a></li>  
	</ul>  
	<div id="my-tab-content" class="tab-content"> 
		<div class="tab-wpane active" id="users">  
			<h4>
				<div style="width:91%;">
						Manage Users, Groups/Roles, Permissions</div> <span class="span1"> 
							
		             </span>
		         </h4>
			<ul class="row-fluid addOnsList content"> 	
			<p>
			<li class="span5">	
	                		<div class="addOns"> 
								<ul>
									<img src="<?= './addons/shared_addons/themes/xervmon/img/addons/UserProfile - Edit Profile.png' ?>"></img>
									<h5><?php echo '<a href="'.site_url().'/admin/UserProfile/Edit"> Edit Profile</a>' ; ?></h5>
									<p>	<?php echo 'Edit your profile' ?></p>
									<p>	<?php echo  '2.0.0' ?></p>
									
								</ul>	
				  			</div>
						</li>
				
				<?php if(array_key_exists('groups', $this->permissions) OR $this->current_user->group == 'admin'): ?>
                  <li class="span5">	
		                		<div class="addOns"> 
									<ul>
										<img src="<?= './addons/shared_addons/themes/xervmon/img/addons/Community.png' ?>"></img>
										<h5><?php echo '<a href="'.site_url().'/admin/groups"> Groups </a>' ; ?></h5>
										<p>	<?php echo 'Manage Groups & Permissions' ?></p>
										<p>	<?php echo  '2.0.0' ?></p>
										<li>
											<a href="<?= site_url().'/admin/groups' ?>" title="<?php echo 'Users'; ?>">
												<img src="<?= base_url().SHARED_ADDONPATH .'/themes/xervmon/img/List.png'; ?>"/>
											</a> |
											<a  href="<?= site_url().'/admin/groups/create' ?>" title="<?php echo 'Add User'; ?>">
												<img src="<?= base_url().SHARED_ADDONPATH .'/themes/xervmon/img/add.png'; ?>"/>
											</a>
										</li>
									</ul>	
					  			</div>
							</li>
						<?php endif ?>
				</p>
			</ul>
			
			
		</div>  
		<div class="tab-wpane" id="service">  
		<p>
		<ul>
			<li>
					<strong>Service Desk</strong><br/>
					Setup status, priority, service types and budget. Users can create service ticket
					assign to a group of users or individual users. 
					Deployment can be attached to the service desk, there by enabling collaborations
					among all stake holders on a single system. Deployments can be automated once service tickets are approved by assigned stakeholders.
					
				</li>
		</ul>	
		</p>  
		</div>  
		
	</div> 
</div>

<style>
/* .tab-wpane{
	height:300px !important;
min-height:300px !important;
overflow:auto;
}*/

</style>