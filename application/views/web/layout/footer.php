<?php //pr($this->site_santry->get_web_auth_data(),1);
$login_data = $this->site_santry->get_web_auth_data();
?>
<!-- ****************** Footer Section ****************** -->
        <footer id="footer">
            <div class="container">
                <div class="top-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h4><?=$this->lang->line('address')?></h4>
                                    <address>
                                        <p><i class="fa fa-map-marker" aria-hidden="true"></i><?= $contact_details['address']; ?></p>
                                        <p><i class="fa fa-envelope-o" aria-hidden="true"></i><a href="#"><?= $contact_details['email']; ?></a></p>
                                        <p><i class="fa fa-phone" aria-hidden="true"></i><a href="#"><?= $contact_details['mobile_no']; ?></a></p>
                                    </address>
                                </div>
                                <div class="col-sm-6">
                                    <h4><?=$this->lang->line('useful_links')?></h4>
                                    <ul class="list-unstyled">
                                        <li><a href="<?=base_url('about-us')?>"> <?=$this->lang->line('imprint')?></a> </li>
                                        <li><a href="<?=base_url('shop')?>"> <?=$this->lang->line('shop')?></a> </li>
                                        <li><a href="<?=base_url('brands')?>"> <?=$this->lang->line('brands')?></a> </li>
                                        <li><a href="<?=base_url('contact-us')?>"> <?=$this->lang->line('contact_us')?></a> </li>
                                        <li><a href="<?=base_url('faq')?>"><?=$this->lang->line('faq')?></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="news-letter">
                                <h4><?=$this->lang->line('get_latest')?></h4>
                                <p><?=$this->lang->line('newsletter')?></p>
                                <form class="form-inline">
                                    <div class="form-group">
                                        <input type="email" class="form-control" placeholder="<?=$this->lang->line('email')?>">
                                    </div>
                                    <button type="submit" class="btn btn-default"><?=$this->lang->line('submit')?></button>
                                </form>
                            </div>
                            <h4><?=$this->lang->line('connect_with_us')?></h4>
                            <ul class="list-inline social-links">
                                <li><a target="_blank" href="https://m.facebook.com/profile.php?id=577370512619566&ref=content_filter"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                <li><a target="_blank" href="https://instagram.com/alibaba_nuts?igshid=9c25cw54dcix"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="bottom-footer">
                    <div class="row">
                        <div class="col-md-4">
                            <span class="copyright"><?=$this->lang->line('copyrights')?></span>
                        </div>
                        <div class="col-md-8">
                            <ul class="list-inline">
                                <li><a href="<?=base_url('privacy-policy')?>"> <?=$this->lang->line('privacy_policy')?></a> </li>
                                <li><a href="<?=base_url('terms-and-conditions')?>"> <?=$this->lang->line('terms_conditions')?></a> </li>
                            </ul>
                        </div>
                    </div>
                    
                </div> 
            </div>
        </footer>
    </div>
    
    
    <!-- JavaScript files -->
    <script src="<?=WEB_PATH?>js/jquery-1.12.4.min.js"></script> <!-- jquery-1.12.4.min js-->
    <script src="<?=WEB_PATH?>js/bootstrap.min.js"></script> <!-- bootstrap.min js-->
    <script src="<?=WEB_PATH?>js/slick.min.js"></script> <!-- slick slider js-->
    <script src="<?=WEB_PATH?>js/waypoints.min.js"></script> 
    <script src="<?=WEB_PATH?>js/jquery.counterup.min.js"></script><!-- counter js -->
    <script src="<?=WEB_PATH?>js/custom.js"></script> <!-- custom js--> 

</body>
</html>