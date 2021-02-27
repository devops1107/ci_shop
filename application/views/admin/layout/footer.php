        
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="footer text-center">
            © 2020 baaba.de. All rights reserved. Designed and Developed by
            <a href="https://w3ondemand.com">Forthpro Infosolution</a>.
        </footer>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
</div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- customizer Panel -->
    <!-- ============================================================== -->
    
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    
    <!-- apps -->
    <script src="<?=ADMIN_PATH?>js/app.min.js"></script>
    <script src="<?=ADMIN_PATH?>js/app.init.minimal.js"></script>
    <script src="<?=ADMIN_PATH?>js/app-style-switcher.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="<?=ADMIN_PATH?>libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="<?=ADMIN_PATH?>extra-libs/sparkline/sparkline.js"></script>
    
    <!--Menu sidebar -->
    <script src="<?=ADMIN_PATH?>js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="<?=ADMIN_PATH?>js/custom.min.js"></script>

    <script type="application/javascript">
    /** After windod Load */
    $(window).bind("load", function() {
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove();
            });
        }, 5000);
    });

    </script>


</html>
