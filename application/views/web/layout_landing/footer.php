<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 footer_col">
                <div class="footer_column footer_contact">
                    <div class="logo_container">
                        <div class="logo"><a  style="color: #fefefe" href="#">Alibaba Nnuts</a></div>
                    </div>
                    <div  style="color: #fefefe" class="footer_title">Got Question? Call Us 24/7</div>
                    <div class="footer_contact_text">
                        <address>
                            <p style="color: #fefefe" ><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;www.alibaba-nuts.online<br>An der Philippsschanze 17</p>
                            <p style="color: #fefefe" ><img src="<?=LAND_PATH?>images/mail.png" alt=""><a href="#">&nbsp;&nbsp;&nbsp;<?= $contact_details['email']; ?></a></p>
                            <p style="color: #fefefe" ><i class="fa fa-phone" aria-hidden="true"></i><a href="#">&nbsp;&nbsp;<?= $contact_details['mobile_no']; ?></a></p>
                        </address>
                    </div>
                    <div class="footer_social">
                        <ul>
                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-1"></div>
            <div class="col-lg-2">
                <div class="footer_column">
                    <div class="footer_title" style="color: #fefefe" >Customer Care</div>
                    <ul class="footer_list">
                        <li><a style="color: #fefefe" href="<?=base_url('about-us')?>"> <?=$this->lang->line('imprint')?></a> </li>
                        <li><a  style="color: #fefefe" style="color: #fefefe" href="<?=base_url('shop')?>"> <?=$this->lang->line('shop')?></a> </li>
                        <li><a style="color: #fefefe"  href="<?=base_url('brands')?>"> <?=$this->lang->line('brands')?></a> </li>
                        <li><a  style="color: #fefefe" href="<?=base_url('contact-us')?>"> <?=$this->lang->line('contact_us')?></a> </li>
                        <li><a  style="color: #fefefe" href="<?=base_url('faq')?>"><?=$this->lang->line('faq')?></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
<!--                <div class="newsletter_container d-flex flex-lg-row flex-column align-items-lg-center align-items-center justify-content-lg-start justify-content-center">-->
                    <div class="row" style="margin-bottom: 20px">
                        <div class="col-lg-12 newsletter_title_container">
                            <div class="newsletter_icon"><img src="<?= LAND_PATH ?>images/send.png" alt=""></div>
                            <div class="newsletter_title" style="color: #fefefe">Sign up for Newsletter</div>
                            <div class="newsletter_text"><p style="color: #fefefe">...and receive %20 coupon for first shopping.</p></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="newsletter_content clearfix">
                            <form action="#" class="newsletter_form">
                                <input type="email" class="newsletter_input" required="required"
                                       placeholder="Enter your email address">
                                <button class="newsletter_button">Subscribe</button>
                            </form>
                        </div>
                    </div>
            </div>
        </div>

    </div>
</footer>

<div class="copyright" style="background-color: #6C0202">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="copyright_container d-flex flex-sm-row flex-column align-items-center justify-content-start">
                    <div class="copyright_content" style="color: #fefefe">
                        Copyright &copy; <?php date('Y');?> Alibaba-nuts.online, All rights reserved
                    </div>
                    <div class="logos ml-sm-auto">
                        <ul class="logos_list">
                            <li><a href="#"><img src="<?=LAND_PATH?>images/logos_1.png" alt=""></a></li>
                            <li><a href="#"><img src="<?=LAND_PATH?>images/logos_2.png" alt=""></a></li>
                            <li><a href="#"><img src="<?=LAND_PATH?>images/logos_3.png" alt=""></a></li>
                            <li><a href="#"><img src="<?=LAND_PATH?>images/logos_4.png" alt=""></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script src="<?=LAND_PATH?>js/jquery-3.3.1.min.js"></script>
<script src="<?=LAND_PATH?>styles/bootstrap4/popper.js"></script>
<script src="<?=LAND_PATH?>styles/bootstrap4/bootstrap.min.js"></script>
<script src="<?=LAND_PATH?>plugins/greensock/TweenMax.min.js"></script>
<script src="<?=LAND_PATH?>plugins/greensock/TimelineMax.min.js"></script>
<script src="<?=LAND_PATH?>plugins/scrollmagic/ScrollMagic.min.js"></script>
<script src="<?=LAND_PATH?>plugins/greensock/animation.gsap.min.js"></script>
<script src="<?=LAND_PATH?>plugins/greensock/ScrollToPlugin.min.js"></script>
<script src="<?=LAND_PATH?>plugins/OwlCarousel2-2.2.1/owl.carousel.js"></script>
<script src="<?=LAND_PATH?>plugins/slick-1.8.0/slick.js"></script>
<script src="<?=LAND_PATH?>plugins/easing/easing.js"></script>
<script src="<?=LAND_PATH?>js/custom.js?v=1.23"></script>

<script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());

    gtag('config', 'UA-23581568-13');
</script>
</body>

</html>