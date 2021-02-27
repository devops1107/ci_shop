<div id="content">
	
    <section class="inner-content">
    	<div class="container">
        	<!-- ****************** Contact Us Section	****************** -->
        	<div class="contact-us">
            	<div class="heading"><span><?=$this->lang->line('contact_us')?></span></div>
                <div class="contact-form">
                	<div class="row">
                        <div class="col-md-12">
                            <?php 
                            if(!empty($this->session->flashdata('flashSuccess')))
                            {
                                print '<div class="alert alert-success">'.$this->session->flashdata('flashSuccess').'</div>';
                            }   ?>
                        </div>
                        <form method="post" action="">
                    	<div class="col-md-6">
                        	<div class="form-group">
                                <label><?=$this->lang->line('name')?> *</label>
                                <input type="text" name="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label><?=$this->lang->line('mobile')?> *</label>
                                <input type="number" name="mobile" class="form-control">
                            </div>
                            <div class="form-group">
                                <label><?=$this->lang->line('email')?> *</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                        	<div class="form-group">
                                <label><?=$this->lang->line('message')?> *</label>
                                <textarea name="message" class="form-control"></textarea>
                            </div>
                            <div class="form-group text-right">
                            	<button type="submit" class="btn btn-info btn-large"><?=$this->lang->line('submit')?></button> 
                            </div>
                        </div>
                    	</form>
                    </div>
                </div> 
                
            </div>
            <div class="heading"><span><?=$this->lang->line('contact_details')?></span></div>
            <div class="row">
                <div class="col-md-4 text-center">
                    <h4><?=$this->lang->line('email')?></h4>
                    <a class="text-dark" href="#"><?= $contact_details['email']; ?></a>
                </div>
                <div class="col-md-4 text-center">
                    <h4><?=$this->lang->line('phone_number')?></h4>
                    <a class="text-dark" href="#"><?= $contact_details['mobile_no']; ?></a>
                </div>
                <div class="col-md-4 text-center">
                    <h4><?=$this->lang->line('address')?></h4>
                    <a class="text-dark" href="#"><?= $contact_details['address']; ?></a>
                </div>
                
            </div>
    		  
        </div>
    </section>

    
</div>

