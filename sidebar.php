<?php

/* Check the absolute path to the Social Auto Poster directory. */
if ( !defined( 'SAP_APP_PATH' ) ) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

?>

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<!-- Sidebar user panel -->

		<!-- sidebar menu: : style can be found in sidebar.less -->
		<ul class="sidebar-menu" data-widget="tree">

			<?php
			global $sap_common;
			$SAP_Mingle_Update = new SAP_Mingle_Update();
			$license_data = $SAP_Mingle_Update->get_license_data();

	
			$role = isset( $_SESSION['user_details'] ) ? $_SESSION['user_details'] : array();
			if( $role['role'] == 'user' && ! empty( $license_data['license_key'] ) ) { ?>
                <li class="<?php echo ($match['name'] == 'dashboard') ? 'active':'';?>">
                    <a href="<?php echo $router->generate('dashboard'); ?>">
                        <i class="fa fa-tachometer"></i> <span><?php echo $sap_common->lang('dashboard'); ?></span>
                    </a>
                </li>
				<li class="treeview <?php echo in_array($match['name'], ['quick_posts_add', 'quick_save_post', 'quick_posts', 'logs']) ? 'active' : ''; ?>">
					<a href="<?php echo $router->generate('quick_posts'); ?>" class="post-menu">
						<i class="fa fa-pencil-square-o"></i>
						<span><?php eLang('post'); ?></span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>

					<ul class="treeview-menu">
						<li class="<?php echo in_array($match['name'], ['quick_posts_add', 'quick_save_post']) ? 'active' : ''; ?>">
							<a href="<?php echo $router->generate('quick_posts_add'); ?>" class="menu-item">
								<?php eLang('quick_single_post'); ?>
							</a>
						</li>
						<li class="<?php echo ($match['name'] == 'quick_posts') ? 'active' : ''; ?>">
							<a href="<?php echo $router->generate('quick_posts'); ?>" class="menu-item">
								<?php eLang('quick_posts_report'); ?>
							</a>
						</li>
						<li class="<?php echo ($match['name'] == 'logs') ? 'active' : ''; ?>">
							<a href="<?php echo $router->generate('logs'); ?>" class="menu-item">
								<?php eLang('posting_logs'); ?>
							</a>
						</li>
					</ul>
				</li>
				<li class="treeview <?php echo strstr($match['name'], 'crawlers')|| strstr($match['name'], 'CPG') || strstr($match['name'], 'crawler_logs') ? 'active' : ''; ?>">
					<a href="<?php echo $router->generate('crawlers'); ?>" class="crawler-menu">
						<i class="fa fa-crosshairs"></i>
						<span><?php echo $sap_common->lang('crwlr_title'); ?></span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>

					<ul class="treeview-menu">
						<li class="<?php echo ($match['name'] == 'crawlers') ? 'active' : ''; ?>">
							<a href="<?php echo $router->generate('crawlers'); ?>" class="menu-item">
								<?php echo $sap_common->lang('crwlr_title'); ?>
							</a>
						</li>
						<li class="<?php echo strstr($match['name'], 'CPG') ? 'active' : ''; ?>">
							<a href="<?php echo $router->generate('CPG'); ?>" class="menu-item">
								<?php eLang('cpg_title'); ?>
							</a>
						</li>
						<li class="<?php echo strstr($match['name'], 'crawler_logs') ? 'active' : ''; ?>">
							<a href="<?php echo $router->generate('crawler_logs'); ?>" class="menu-item">
								<?php eLang('crawler_logs_title'); ?>
							</a>
						</li>
					</ul>
				</li>
                <li class="treeview <?php echo (strstr($match['name'], 'bots')) || ($match['name'] == 'bots_profiles') ? 'active' : ''; ?>">
					<a href="<?php echo $router->generate('bots'); ?>" class="bots-menu">
						<i class="fa fa-user-secret"></i>
						<span><?php eLang('bots_title'); ?></span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo ($match['name'] == 'bots') ? 'active' : ''; ?>">
							<a href="<?php echo $router->generate('bots'); ?>" class="menu-item">
								<?php eLang('bots_title'); ?>
							</a>
						</li>
						<li class="<?php echo ($match['name'] == 'bots_profiles') ? 'active' : ''; ?>">
							<a href="<?php echo $router->generate('bots_profiles'); ?>" class="menu-item">
								<?php eLang('bots_profiles_title'); ?>
							</a>
						</li>
                        <li class="<?php echo ($match['name'] == 'bots_logs') ? 'active' : ''; ?>">
                            <a href="<?php echo $router->generate('bots_logs'); ?>" class="menu-item">
                                <?php eLang('bots_logs_title'); ?>
                            </a>
                        </li>
					</ul>
				</li>

                <li class="<?php echo strstr($match['name'],'calender') ? "active":"";?>">
                    <a href="<?php echo $router->generate('calender'); ?>" class="calender-menu">
                        <i class="fa fa-calendar"></i>
                        <span><?php elang('calender_title'); ?></span>
                        <span class="pull-right-container">
                    </a>
                </li>
				<li class="<?php echo ($match['name'] == 'settings') ? "active":"";?>">
					<a href="<?php echo $router->generate('settings'); ?>">
						<i class="fa fa-cog"></i> <span><?php echo $sap_common->lang('setup'); ?></span>
					</a>
				</li>
				<li class="<?php echo ($match['name'] == 'report') ? "active":"";?>">
					<a href="<?php echo $router->generate('report'); ?>">
						<i class="fa fa-pie-chart"></i> <span><?php echo $sap_common->lang('report'); ?></span>
					</a>
				</li>
				<li class="<?php echo ($match['name'] == 'debug') ? "active":"";?>">
					<a href="<?php echo $router->generate('debug'); ?>">
						<i class="fa fa-bug"></i> <span><?php echo $sap_common->lang('debug_logs'); ?></span>
					</a>
				</li>
<!--				<li class="--><?php //echo ( $match['name'] == 'your-subscription' ) ? "active":"";?><!--">-->
<!--					<a href="--><?php //echo $router->generate('your-subscription'); ?><!--" class="subscription-menu">-->
<!--						<i class="fa fa-id-card"></i> -->
<!--						<span>--><?php //echo $sap_common->lang('your_subscription'); ?><!--</span>-->
<!--						<span class="pull-right-container">-->
<!--								-->
<!--					</a>-->
<!--				</li>-->
				<!-- <li class="<?php echo ( $match['name'] == 'page' ) ? "active":"";?>">
					<a href="<?php echo $router->generate('page'); ?>" class="simple-page-menu">
						<i class="fa fa-id-card"></i> 
						<span><?php echo $sap_common->lang('simple_page'); ?></span>
						<span class="pull-right-container">
					</a>
				</li> -->



			<?php } else if ( $role['role'] != 'user' && empty( $license_data['license_key'] ) ) { ?>

					<li class="<?php echo ($match['name'] == 'mingle_update') ? "active":"";?>">
						<a href="<?php echo $router->generate('mingle_update'); ?>">
							<i class="fa fa-refresh"></i> <span><?php echo $sap_common->lang('license_and_updates'); ?></span>
							<?php 
							if( !empty($_SESSION['Update_version'] ) && $_SESSION['Update_version']  > SAP_VERSION ){ ?>
							<span class="pull-right-container">
								<span class="label fa <?php if( $sap_common->sap_is_license_activated() ){ echo 'fa-cloud-download bg-red'; }?> pull-right">&nbsp;</span>
							</span>
							<?php } ?>
						</a>
					</li>

			<?php } else if ( $role['role'] == 'user' && empty( $license_data['license_key'] ) ) { ?>
					<li class="<?php echo ($match['name'] == 'mingle_update') ? "active":"";?>">
						<a href="<?php echo $router->generate('mingle_update'); ?>">
							<i class="fa fa-refresh"></i> <span><?php echo $sap_common->lang('admin_licence_not_register'); ?></span>
							<?php 
							if( !empty($_SESSION['Update_version'] ) && $_SESSION['Update_version']  > SAP_VERSION ){ ?>
							<span class="pull-right-container">
								<span class="label fa <?php if( $sap_common->sap_is_license_activated() ){ echo 'fa-cloud-download bg-red'; }?> pull-right">&nbsp;</span>
							</span>
							<?php } ?>
						</a>
					</li>


			<?php } else { ?>

				<li class="<?php echo ($match['name'] == 'admin') ? 'active':'';?>">
                    <a href="<?php echo $router->generate('admin'); ?>">
                        <i class="fa fa-tachometer"></i> <span><?php eLang('dashboard'); ?></span>
                    </a>
                </li>
				<li class="<?php echo ($match['name'] == 'plan_list' || $match['name'] == 'add_plan') ? "active":"";?>">
					<a href="<?php echo $router->generate('plan_list'); ?>">
						<i class="fa fa-id-badge"></i> <span><?php echo $sap_common->lang('membership_levels'); ?></span>            
					</a>
				</li>	
				<li class="<?php echo ($match['name'] == 'member_list' || $match['name'] == 'add_member') ? "active":"";?>">
					<a href="<?php echo $router->generate('member_list'); ?>">
						<i class="fa fa-user-plus"></i> <span><?php echo $sap_common->lang('customers'); ?></span>            
					</a>
				</li>

				<li class="<?php echo ($match['name'] == 'membership_list' || $match['name'] == 'add_membership' ) ? "active":"";?>">
					<a href="<?php echo $router->generate('membership_list'); ?>">
						<i class="fa fa-id-card"></i><span><?php echo $sap_common->lang('memberships'); ?></span>            
					</a>
				</li>

				<li class="<?php echo ($match['name'] == 'payments' || $match['name'] == 'add-payment') ? "active":"";?>">
					<a href="<?php echo $router->generate('payments'); ?>">
						<i class="fa fa-credit-card-alt"></i> <span><?php echo $sap_common->lang('payments'); ?></span>            
					</a>
				</li>

				<li class="<?php echo ($match['name'] == 'coupons' || $match['name'] == 'add-coupon') ? "active":"";?>">
					<a href="<?php echo $router->generate('coupons'); ?>">
						<i class="fa fa-tag"></i> <span><?php echo $sap_common->lang('coupons'); ?></span>            
					</a>
				</li>
			
				<li class="<?php echo ($match['name'] == 'general_settings') ? "active":"";?>">
					<a href="<?php echo $router->generate('general_settings'); ?>">
						<i class="fa fa-cog"></i> <span><?php echo $sap_common->lang('general_settings'); ?></span>            
					</a>
				</li>

				<?php //license_and_updates ?>
				<li class="<?php echo strstr($match['name'],'crawlers') ? "active":"";?>">
					<a href="<?php echo $router->generate('crawlers_pendings'); ?>" class="crawler-menu">
						<i class="fa fa-crosshairs"></i>
						<span><?php echo $sap_common->lang('crwlr_title'); ?></span>
						<span class="pull-right-container">
					</a>
				</li>
				<li class="<?php echo ($match['name'] == 'quick_posts_users') ? 'active':'';?>">
                    <a href="<?php echo $router->generate('quick_posts_users'); ?>">
                        <i class="fa fa-area-chart"></i> <span><?php eLang('users_quick_posts'); ?></span>
                    </a>
                </li>
                <li class="<?php echo strstr($match['name'],'bots_import_excel_user_and_bot') ? "active":"";?>">
                    <a href="<?php echo $router->generate('bots_import_excel_user_and_bot'); ?>" class="bots-import-excel-menu">
                        <i class="fa fa-user-secret"></i>
                        <span><?php elang('bots_import_excel_title'); ?></span>
                        <span class="pull-right-container">
                    </a>
                </li>
			<?php } ?>
		</ul>
	</section>
	<!-- /.sidebar -->
</aside>
