<div id="content">
        	
    <section class="inner-content">
    	<div class="container">
        	<div class="row">
            	<div class="col-md-12">
                    <div class="heading">
                        <span><?=$this->lang->line('my_cart')?></span>
                    </div>
                    
                    <div class="my-cart" id="myCartDetails">
                        <?php 
                        $this->load->view('web/my-cart-ajax');    
                        ?>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
    
</div>

