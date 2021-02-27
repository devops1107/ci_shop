<section id="main-content">
          <section class="wrapper min_height">
              <!-- page start-->
              <div class="row">
                  <aside class="profile-nav col-lg-3">
                      <section class="panel">
                        <form method="post" class="changeImageForm" id="myForm" enctype="multipart/form-data">
                          <div class="user-heading round">
                              <a href="javascript:void(0)">
                                  <img id="click_image" src="<?= (is_file(UPLOAD_PHYSICAL_PATH.'user-images/'.$detail['profile_image']) && $detail['profile_image'] !="")?UPLOAD_URL.'user-images/'.$detail['profile_image']:UPLOAD_URL.'user-images/no_image.png' ?>" />
                                  <input type="file" name="profile_image" id="profile_image" accept="image/jpeg" style="display:none;"/>
                              </a>
                              <h1><?=$detail['first_name'].' '.$detail['last_name']?></h1>
                              <p><?=$detail['user_email']?></p>
                          </div>
                        </form>  

                          <ul class="nav nav-pills nav-stacked">
                              <li  class="active"><a href="<?=base_url()?>admin/edit-profile"> <i class="fa fa-edit"></i> Edit profile</a></li>
                              <li><a href="<?=base_url()?>admin/change-password"> <i class="fa fa-unlock-alt"></i> Change password </a></li>
                          </ul>

                      </section>
                  </aside>
                  <aside class="profile-info col-lg-9">
                      <section class="panel">
                          
                          <div class="panel-body bio-graph-info">
                              <h1> Profile Info</h1>
							
                              <?php
                                  $attribute = array('name'=>'frmuser','id'=>'frmuser');
                                  echo form_open('admin/edit-profile',$attribute);
                                  $this->load->view('admin/layout/validation-errors');?>    
								    <div class="row">
                                  <div class="form-group col-md-6">
                                      <label  class="control-label">First Name</label>
									    <?php
										  $data = array(
												'name'      => 'first_name',
												'id'      => 'first_name',
												'required'    =>"required",
												'placeholder' => 'First Name',
												'class'     => "form-control padding_r0",
												'value'     => $detail['first_name']
											  );
										  echo form_input($data);
										?>
                                  </div>
                                  <div class="form-group col-md-6">
                                        <label class="control-label">Last Name</label>
									    <?php
										  $data = array(
												'name'      => 'last_name',
												'id'      => 'last_name',
												'required'    =>"required",
												'placeholder' => 'Last Name',
												'class'     => "form-control padding_r0",
												'value'     => $detail['last_name']
											  );
										  echo form_input($data);
										?>
                                  </div>
                                  <div class="form-group col-md-6">
                                      <label class="control-label">Email</label>
                                          <?php
                                              $data = array(
                                                    'name'      => 'user_email',
                                                    'id'      => 'user_email',
                                                    'type' => 'email',
                                                    'required'    =>"required",
                                                    'placeholder' => 'User Email',
                                                    'class'     => "form-control padding_r0",
                                                    'value'     => $detail['user_email']
                                                  );
                                              echo form_input($data);
                                          ?>
                                  </div>
                                  <div class="form-group col-md-6">
                                      <label  class="control-label">Mobile</label>
                                          <?php
                                              $data = array(
                                                    'name'      => 'contact_number',
                                                    'id'      => 'contact_number',
                                                    'required'    =>"required",
                                                    'placeholder' => 'Mobile Number',
                                                    'class'     => "form-control padding_r0",
                                                    'value'     => $detail['contact_number']
                                                  );
                                              echo form_input($data);
                                          ?>
                                  </div>

                                  <div class="form-group col-md-6">
                                      <label  class="control-label">Last Login Date Time</label>
                                          <?php
                                                $data = array(
                                                      'class'     => "form-control padding_r0",
                                                      'disabled'    => "disabled ",
                                                      'value'     => date("m-d-Y H:i:s",strtotime($detail['admin_last_login_dt']))
                                                      );
                                                echo form_input($data);
                                           ?>
                                  </div>

                                  <div class="form-group col-md-6">
                                      <label  class="control-label">Modify Date</label>
                                             <?php
                                                $data = array(
                                                      'class'     => "form-control padding_r0",
                                                      'disabled'    => "disabled ",
                                                      'value'     => date('m-d-Y',strtotime($detail['admin_modify_dt']))
                                                      );
                                                echo form_input($data);
                                              ?>
                                  </div>
                                  <div class="form-group col-md-6">
                                          <?php $js = 'class="btn btn-success"';
                                           echo form_submit('update','Save',$js); ?>
                                          <a href="<?=base_url()?>admin/dashboard"><button type="button" class="btn btn-default">Cancel</button></a>
                                  </div>
								  </div>
                               <?=form_close();?>
                          
                          </div>
                      </section>
                     
                  </aside>
              </div>

              <!-- page end-->
          </section>
      </section>

<script type="text/javascript">
  
  $('.changeImageForm').on('submit',function(e){

    var formData = new FormData(this);
    $.ajax({
      url: "<?php echo base_url('admin/Users/changeProfileImage')?>",
      type: "POST",
      data: formData,
      dataType: 'json',
      success: function (response) {
        if(response.error == 'no')
        {
          $('#click_image').attr('src',response.msg);
        }else{
          alert(response.msg);
        }
      },
      cache: false,
      contentType: false,
      processData: false
    });
    e.preventDefault();
  });
  
  $('#profile_image').on('change',function(e){
   // alert($(this).val());
    $('#myForm').submit();
  }); 


  $('#click_image').click(function(){
    $(this).parents('.changeImageForm').find('#profile_image').trigger('click');
  });

</script>



