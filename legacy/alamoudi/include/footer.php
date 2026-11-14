<section class="widget-section padding" style="direction: rtl;">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-sm-12 sm-padding">
                <div class="widget-content">
                    <a href="index-2.html"><img src="img/logo-light.png" alt="brand"
                            style="width: 66%; margin-top: -39px" /></a>
                    <p>
                        <?php echo $footer[$language]['footer_des']  ?>
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-sm-12 sm-padding" style="direction: rtl;">
                <div class="widget-content" style="width: 40%; margin-left: 50px">
                    <h4> <?php echo $footer[$language]['company']  ?></h4>
                    <ul class="widget-links">
                        <li><a href="index.php"><?php echo $top_nav[$language]['Home']  ?></a></li>
                        <li><a href="index.php#about"><?php echo $top_nav[$language]['about']  ?></a></li>
                        <li><a href="team.php"><?php echo $top_nav[$language]['our_team']  ?></a></li>
                        <li><a href="projects.php"><?php echo $top_nav[$language]['our_projects']  ?></a></li>
                        <li><a href="contact.php"><?php echo $top_nav[$language]['contact']  ?></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-sm-12 sm-padding">
                <div class="widget-content">
                    <?php  echo $footer[$language]['address'] ?>
                </div>
            </div>
        </div>
    </div>
</section>
<footer class="footer-section align-center">
    <div class="container">
        <?php echo $footer[$language]['Copyright'] ?>
    </div>
</footer>
<a data-scroll href="#header" id="scroll-to-top"><i class="arrow_carrot-up"></i></a>
<script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
<script src="js/vendor/jquery-1.12.4.min.js"></script>
<script src="js/vendor/bootstrap.min.js"></script>
<script src="js/vendor/tether.min.js"></script>
<script src="js/vendor/headroom.min.js"></script>
<script src="js/vendor/owl.carousel.min.js"></script>
<script src="js/vendor/smooth-scroll.min.js"></script>
<script src="js/vendor/venobox.min.js"></script>
<script src="js/vendor/jquery.ajaxchimp.min.js"></script>
<script src="js/vendor/slick.min.js"></script>
<script src="js/vendor/waypoints.min.js"></script>
<script src="js/vendor/odometer.min.js"></script>
<script src="js/vendor/wow.min.js"></script>
<script src="js/main.js"></script>
<script type="">
    function setLanguage() {
        var language = document.getElementById('language').value;
        window.location.href = 'http://localhost/Alamoudi/?language=' + language;
        console.log(language);
    }
</script>
</body>

<!--index - 2. html 29 Nov 2019 03: 33: 41 GMT-->

</html>