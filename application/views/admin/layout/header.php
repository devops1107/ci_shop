<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?=ADMIN_PATH?>images/favicon.ico">
    <title><?=$title?></title>

    <!--c3 CSS -->
    
    <link href="<?=ADMIN_PATH?>extra-libs/c3/c3.min.css" rel="stylesheet">
    <!-- needed css -->
    <link href="<?=ADMIN_PATH?>css/style.min.css" rel="stylesheet">
    <link href="<?=ADMIN_PATH?>css/custom.css" rel="stylesheet">
    <script src="<?=ADMIN_PATH?>libs/jquery/dist/jquery.min.js"></script>
	<script src="<?=ADMIN_PATH?>libs/bootstrap/dist/js/popper.min.js"></script>
	<script src="<?=ADMIN_PATH?>libs/bootstrap/dist/js/bootstrap.min.js"></script>


</head>

<body>

    <?php 
    /*$notifications_arr    =   admin_notification();
    $read_data = admin_notification_read();*/
    $read_status = '';//$read_data['read_status'];
    $new_msgs = '';//$read_data['new_msgs'];
    //pr($notifications_arr,1); 
?>

    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header">
                    <!-- This is for the sidebar toggle which is visible on mobile only -->
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                    <a class="navbar-brand" href="<?=base_url('admin/dashboard')?>">
                        <img src="<?=ADMIN_PATH?>images/logo.png" alt="homepage" class="logo" />
                    </a>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Toggle which is visible on mobile only -->
                    <!-- ============================================================== -->
                    <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
                </div>
				
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse collapse" id="navbarSupportedContent">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-left mr-auto">
                        <li class="nav-item d-none d-md-block"><a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)" data-sidebartype="mini-sidebar"><i class="mdi mdi-menu font-18"></i></a></li>
                        <!-- ============================================================== -->
                        <!-- Messages -->
                        <!-- ============================================================== -->
                        <!-- <li class="nav-item dropdown">
							<?php $read_data=array();
								//$read_data = admin_notification_read();
									//pr($read_data,1); ?>
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href=""  id="notifications_nav" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="font-18 mdi mdi-bell"></i>
                                <div class="notify">
                                    <span class="<?= ($read_status)?"heartbit":""?>" id="notifications_heartbit"></span>
                                    <span class="<?= ($read_status)?"point":""?>" id="notifications_point"></span>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-left mailbox animated bounceInDown" aria-labelledby="2">
                                <ul class="list-style-none">
									<?php if(!empty($notifications_arr)){ ?>
										<li>
											<div class="drop-title border-bottom">You have <?=$new_msgs;?> new notification</div>
										</li>
									<?php }else{ ?>
										<li>
											<div class="drop-title border-bottom">You have no notification</div>
										</li>
									<?php } ?>
                                    <li>
                                        <div class="message-center message-body">
											<?php 
											if(!empty($notifications_arr)){
												foreach($notifications_arr as $notification){
													$today = date('Y-m-d');
													$notification_time = "";
													if(date('Y-m-d',strtotime($notification['created_on'])) == $today){
														$notification_time = date('H:i A',strtotime($notification['created_on']));
													}else{
														$notification_time = date('d-M-Y H:i A',strtotime($notification['created_on']));
													}
												?>
											
													
													<a href="<?=$notification['notification_link']?>" class="message-item">
														<span class="user-img"> <img src="<?=$notification['image']?>" alt="user" class="rounded-circle">  </span>
														<span class="mail-contnet">
															<h5 class="message-title"><?=$notification['notification_title']?></h5> <span class="mail-desc"><?=$notification['notification_message']?></span> <span class="time"><?=$notification_time;?></span> </span>
													</a>
											<?php } 
											} ?>
                                            
                                        </div>
                                    </li>
									<?php if(!empty($notifications_arr)){ ?>
										<li>
											<a class="nav-link text-center link text-dark" href="<?=base_url('admin/notifications')?>"> <b>See all Notifications</b> <i class="fa fa-angle-right"></i> </a>
										</li>
									<?php } ?>
                                </ul>
                            </div>
                        </li>  -->
                        
                    </ul>
                    <!-- ============================================================== -->
                    <!-- Right side toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-right">
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php 
                                $profileImg = $this->site_santry->get_auth_data('profile_image');
                                if(is_file(UPLOAD_PHYSICAL_PATH.'customers/'.$profileImg) && $profileImg!='')
                                {
                                    $profile_image = UPLOAD_URL.'customers/'.$profileImg;
                                }else{
                                    $profile_image = ADMIN_PATH.'images/users/default_user.jpg';
                                } 
                            ?>
                                <img src="<?=$profile_image?>" alt="user" class="rounded-circle" width="31">
                                <span class="ml-2 user-text font-medium"><?=$this->site_santry->get_auth_data('first_name')?></span><span class="fas fa-angle-down ml-2 user-text"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                                <div class="d-flex no-block align-items-center p-3 mb-2 border-bottom">
                                    <div class=""><img src="<?=$profile_image?>" alt="user" class="rounded" width="80"></div>
                                    <div class="ml-2">
                                        <h4 class="mb-0"><?=$this->site_santry->get_auth_data('first_name').' '.$this->site_santry->get_auth_data('last_name');?></h4>
                                        <p class=" mb-0 text-muted"><?=$this->site_santry->get_auth_data('email')?></p>
                                        <a href="<?=base_url('admin/profile')?>" class="btn btn-sm btn-danger text-white mt-2 btn-rounded">View Profile</a>
                                    </div>
                                </div>
                                <a class="dropdown-item" href="<?=base_url('admin/profile')?>"><i class="ti-user mr-1 ml-1"></i> My Profile</a>
                                <!-- <a class="dropdown-item" href="javascript:void(0)"><i class="ti-email mr-1 ml-1"></i> Inbox</a> -->
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?=base_url('admin/logout')?>"><i class="fa fa-power-off mr-1 ml-1"></i> Logout</a>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->



      
      <!--sidebar start-->
      <?php $this->load->view('admin/layout/sidebar');?>



