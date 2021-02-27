<div class="page-breadcrumb bg-white">
    <div class="row">
        <div class="col-lg-3 col-md-4 col-xs-12 align-self-center">
            <h5 class="font-medium text-uppercase mb-0">Profile</h5>
        </div>
        <div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
            <nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
                <ol class="breadcrumb mb-0 justify-content-end p-0 bg-white">
                    <li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Profile</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="page-content container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <!-- Row -->
    <div class="row">
        <!-- Column -->
        <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-body">
                    <center class="mt-4"> 
                    <?php if(is_file(UPLOAD_PHYSICAL_PATH.'customers/'.$detail['profile_image']) && $detail['profile_image']!='')
                    {
                        $profile_image = UPLOAD_URL.'customers/'.$detail['profile_image'];
                    }else{
                        $profile_image = ADMIN_PATH.'images/users/default_user.jpg';
                    } ?>
                    <img src="<?=$profile_image?>" class="rounded-circle" width="150" />
                        <h4 class="card-title mt-2"><?=$detail['first_name'].' '.$detail['last_name']?></h4>
                        <h6 class="card-subtitle"><?=$detail['role']?></h6>
                    </center>
                </div>
                <div>
                    <hr> </div>
                <div class="card-body"> <small class="text-muted">Email address </small>
                    <h6><?=$detail['user_email']?></h6> <small class="text-muted pt-4 db">Phone</small>
                    <h6><?=$detail['contact_number']?></h6> 
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-8 col-xlg-9 col-md-7">

            <?php $this->load->view('admin/layout/validation-errors'); ?>
            <div class="card">
                <!-- Tabs -->
                <?php
                    $changePassDeactive = false;
                    if($this->session->flashdata('changePasswordTab')===NULL)
                    {
                        $changePassDeactive = true;
                    }
                ?>
                <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link <?=$changePassDeactive?'active':''?>" id="pills-profile-tab" data-toggle="pill" href="#last-month" role="tab" aria-controls="pills-profile" aria-selected="false">Update Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?=!($changePassDeactive)?'active':''?>" id="pills-setting-tab" data-toggle="pill" href="#previous-month" role="tab" aria-controls="pills-setting" aria-selected="false">Change Password</a>
                    </li>
                </ul>
                <!-- Tabs -->
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade <?=$changePassDeactive?'show active':''?>" id="last-month" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <div class="card-body">
                        	<h3>Update Profile</h3><br>
                            <?php
                                $attribute = array('name'=>'frmchange','class'=>'form-horizontal form-material','id'=>'frmchange');
                                echo form_open_multipart('',$attribute);
                            ?>
                                <div class="form-group">
                                    <label class="col-md-12">First Name</label>
                                    <div class="col-md-12">
                                        <?php
                                            $data = array(
                                            'name'          => 'first_name',
                                            'id'            => 'first_name',
                                            'required'        => "required",
                                            'placeholder'   => "First Name",
                                            'value'         => $detail['first_name'],
                                            'class'         =>"form-control form-control-line",
                                            );
                                            echo form_input($data);
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Last Name</label>
                                    <div class="col-md-12">
                                        <?php
                                            $data = array(
                                            'name'          => 'last_name',
                                            'id'            => 'last_name',
                                            'required'        => "required",
                                            'placeholder'   => "Last Name",
                                            'value'         => $detail['last_name'],
                                            'class'         =>"form-control form-control-line",
                                            );
                                            echo form_input($data);
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="example-email" class="col-md-12">Email</label>
                                    <div class="col-md-12">
                                        <?php
                                            $data = array(
                                            'name'          => 'user_email',
                                            'id'            => 'user_email',
                                            'type'          => "email",
                                            'disabled'      => "disabled",
                                            'placeholder'   => "Last Name",
                                            'value'         => $detail['user_email'],
                                            'class'         => "form-control form-control-line",
                                            );
                                            echo form_input($data);
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Phone No.</label>
                                    <div class="col-md-12">
                                        <?php
                                            $data = array(
                                            'name'          => 'contact_number',
                                            'id'            => 'contact_number',
                                            'type'          => "number",
                                            'required'      => "required",
                                            'placeholder'   => "Phone No.",
                                            'value'         => $detail['contact_number'],
                                            'class'         => "form-control form-control-line",
                                            );
                                            echo form_input($data);
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Profile Image : <small class="text-danger"><b>300x300px(JPG Format)</b></small></label>
                                    <div class="col-md-12">
                                        <input type="file" name="profile_image" accept="image/jpeg">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button class="btn btn-success">Update Profile</button>
                                    </div>
                                </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                    <div class="tab-pane fade  <?=!($changePassDeactive)?'show active':''?>" id="previous-month" role="tabpanel" aria-labelledby="pills-setting-tab">
                        <div class="card-body">
                            <h3>Change Password</h3><br>
                            <?php
                                $refferer_url = 'admin/change-password/'.$this->uri->segment(3);
                                $attribute = array('name'=>'changePassword','id'=>'changePassword');
                                echo form_open($refferer_url,$attribute);
                            ?>
                                <div class="form-group">
                                    <label class="col-md-12">Old Password</label>
                                    <div class="col-md-12">
                                        <?php
                                            $data = array(
                                                        'name'          => 'old_password',
                                                        'id'            => 'old_password',
                                                        'required'      => "required",
                                                        'placeholder'   => 'Old Password',
                                                        'class'         => "form-control padding_r0",
                                                        'type'          => "password",
                                                        'autocomplete'  => "nope"
                                                        );
                                            echo form_input($data);
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">New Password</label>
                                    <div class="col-md-12">
                                        <?php
                                            $data = array(
                                                        'name'          => 'password',
                                                        'id'            => 'password',
                                                        'required'      => "required",
                                                        'placeholder'   => 'New Password',
                                                        'class'         => "form-control form-control-line",
                                                        'type'          => "password",
                                                        'autocomplete'  => "nope"
                                                        );
                                            echo form_input($data);
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Confirm Password</label>
                                    <div class="col-md-12">
                                        <?php
                                            $data = array(                  
                                                        'name'          => 'confirmpassword',
                                                        'id'            => 'confirmpassword',
                                                        'required'      => "required",
                                                        'placeholder'   => 'Confirm Password',
                                                        'class'         => "form-control",
                                                        'type'          => "password",
                                                        'autocomplete'  => "nope"
                                                        );
                                            echo form_input($data);
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <?php 
                                            $class = 'class="btn btn-success"' ;
                                            echo form_submit('update','Update Password',$class);
                                        ?>
                                    </div>
                                </div>
                           <?php echo form_close(); ?>
                        </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
    </div>
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->