<?php  include('include/header.php') ?>
<style>
    i {
        color: #ff7607;
    }
</style>
<section class="page-header-contact padding">
    <div class="container">
        <div class="page-content-contact text-center">
            <h2><?php echo $page[$language]['contact'] ?></h2>
        </div>
    </div>
</section>
<section class="contact-section bg-grey padding">
    <div class="dots"></div>
    <div class="container">
        <div class="contact-wrap d-flex align-items-center row">
            <div class="contact-info">
                <?php echo $page[$language]['contact_des'] ?>
            </div>
            <div class="col-md-6 padding-15">
                <h3 class="w-75 mt-4"><span class="mr-3"><i class="fa fa-map-marker"></i></span>
                    <?php echo $page[$language]['contact_address2']  ?>
                </h3>
                <h4><span class="mr-3"><i class="fa fa-envelope"></i></span>m.h.al_amoudi@hotmail.com <br><span
                        class="mr-3"><i class="fa fa-phone"></i></span>+966 01 731 75088 <br><span class="mr-3"><i
                            class="fa fa-fax"></i></span>+966 01 731 75088 <br></h4>
            </div>
            <div class="col-md-6 padding-15">
                <h3 class="w-75"><span class="mr-3"><i class="fa fa-map-marker"></i></span>
                    <?php echo $page[$language]['contact_address1'] ?>
                </h3>
                <h4><span class="mr-3"><i class="fa fa-envelope"></i></span>y2007d@hotmail.com <br><span class="mr-3"><i
                            class="fa fa-phone"></i></span>+966 01 484 88881<br><span class="mr-3"><i
                            class="fa fa-fax"></i></span>106 <br></h4>
            </div>
        </div>
    </div>
</section><?php include('include/footer.php') ?>