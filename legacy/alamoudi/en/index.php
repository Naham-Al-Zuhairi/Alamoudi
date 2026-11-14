<?php include('include/header.php') ?>
<div id="main-slider" class="dl-slider">
  <div class="single-slide">
    <div class="bg-img kenburns-top-right" style="background-image: url(../img/slider-1.jpg)"></div>
    <div class="overlay"></div>
    <div class="slider-content-wrap d-flex align-items-center text-left">
      <div class="container">
        <div class="slider-content">
          <div class="dl-caption medium">
            <div class="inner-layer">
              <div data-animation="fade-in-right" data-delay="1s">
                <?php echo $carousel[$language]['Alamoudi'] ?>
              </div>
            </div>
          </div>
          <div class="dl-caption big">
            <div class="inner-layer">
              <div data-animation="fade-in-right" data-delay="2s">
                <?php echo $carousel[$language]['title1'] ?>
              </div>
            </div>
          </div>
          <div class="dl-caption big">
            <div class="inner-layer">
              <div data-animation="fade-in-right" data-delay="2.5s">

              </div>
            </div>
          </div>
          <div class="dl-caption small">
            <div class="inner-layer">
              <div data-animation="fade-in-right" data-delay="3s">
                <?php echo $carousel[$language]['description'] ?>
              </div>
            </div>
          </div>
          <div class="dl-btn-group">
            <div class="inner-layer">
              <a href="projects.php" class="dl-btn" data-animation="fade-in-left"
                data-delay="3.5s"><?php echo $carousel[$language]['view_projects'] ?><i class="arrow_right"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="single-slide">
    <div class="bg-img kenburns-top-right" style="background-image: url(../img/slider-2.jpg)"></div>
    <div class="overlay"></div>
    <div class="slider-content-wrap d-flex align-items-center text-center">
      <div class="container">
        <div class="slider-content">
          <div class="dl-caption medium">
            <div class="inner-layer">
              <div data-animation="fade-in-top" data-delay="1s">
                <?php echo $carousel[$language]['Alamoudi'] ?>
              </div>
            </div>
          </div>
          <div class="dl-caption big">
            <div class="inner-layer">
              <div data-animation="fade-in-bottom" data-delay="2s">
                <?php echo $carousel[$language]['title2'] ?>
              </div>
            </div>
          </div>
          <div class="dl-caption big">
            <div class="inner-layer">
              <div data-animation="fade-in-bottom" data-delay="2.5s">

              </div>
            </div>
          </div>
          <div class="dl-caption small">
            <div class="inner-layer">
              <div data-animation="fade-in-bottom" data-delay="3s">
                <?php echo $carousel[$language]['description'] ?>
              </div>
            </div>
          </div>
          <div class="dl-btn-group">
            <div class="inner-layer">
              <a href="projects.php" class="dl-btn" data-animation="fade-in-bottom"
                data-delay="3.5s"><?php echo $carousel[$language]['view_projects'] ?><i class="arrow_right"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="single-slide">
    <div class="bg-img kenburns-top-right" style="background-image: url(../img/slider-3.jpg)"></div>
    <div class="overlay"></div>
    <div class="slider-content-wrap d-flex align-items-center text-right">
      <div class="container">
        <div class="slider-content">
          <div class="dl-caption medium">
            <div class="inner-layer">
              <div data-animation="fade-in-right" data-delay="1s">
                <?php echo $carousel[$language]['Alamoudi'] ?>
              </div>
            </div>
          </div>
          <div class="dl-caption big">
            <div class="inner-layer">
              <div data-animation="fade-in-right" data-delay="2s">
                <?php echo $carousel[$language]['title3'] ?>
              </div>
            </div>
          </div>
          <div class="dl-caption big">
            <div class="inner-layer">
              <div data-animation="fade-in-right" data-delay="2.5s">

              </div>
            </div>
          </div>
          <div class="dl-caption small">
            <div class="inner-layer">
              <div data-animation="fade-in-right" data-delay="3s">
                <?php echo $carousel[$language]['description'] ?>
              </div>
            </div>
          </div>
          <div class="dl-btn-group">
            <div class="inner-layer">
              <a href="projects.php" class="dl-btn" data-animation="fade-in-right"
                data-delay="3.5s"><?php echo $carousel[$language]['view_projects'] ?><i class="arrow_right"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<section class="service-section section-2 bg-grey padding" id="about">
  <div class="dots"></div>
  <div class="container">
    <div class="row d-flex align-items-center">
      <div class="col-lg-6 sm-padding">
        <div class="service-content wow fadeInLeft">
          <span><?php echo $about[$language]['about'] ?> </span>
          <h2><?php echo $about[$language]['about_us'] ?> </h2>
          <p>
            <?php echo $about[$language]['description1'] ?>
          </p>
          <p>
            <?php echo $about[$language]['description2'] ?>
          </p>
        </div>
      </div>
      <div class="col-lg-6 sm-padding">
        <div class="row services-list">
          <div class="col-md-6 padding-15">
            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="100ms">
              <i class="flaticon-factory"></i>
              <h3> <?php echo $about[$language]['flaticon_title1'] ?></h3>
              <p>
                <?php echo $about[$language]['flaticon_description1'] ?>
              </p>
            </div>
          </div>
          <div class="col-md-6 padding-15 offset-top">
            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="300ms">
              <i class="flaticon-worker"></i>
              <h3><?php echo $about[$language]['flaticon_title2'] ?></h3>
              <p>
                <?php echo $about[$language]['flaticon_description2'] ?>
              </p>
            </div>
          </div>
          <div class="col-md-6 padding-15">
            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="400ms">
              <i class="flaticon-gear"></i>
              <h3><?php echo $about[$language]['flaticon_title3'] ?></h3>
              <p>
                <?php echo $about[$language]['flaticon_description3'] ?>
              </p>
            </div>
          </div>
          <div class="col-md-6 padding-15 offset-top">
            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="500ms">
              <i class="flaticon-worker-1"></i>
              <h3><?php echo $about[$language]['flaticon_title4'] ?></h3>
              <p>
                <?php echo $about[$language]['flaticon_description4'] ?>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="testimonial-section bg-grey padding">
  <div class="dots"></div>
  <div class="container">
    <div class="section-heading text-center mb-40 wow fadeInUp" data-wow-delay="100ms">
      <span><?php echo $achievements[$language]['achievements'] ?></span>
      <h2><?php echo $achievements[$language]['achievements_des'] ?></h2>
    </div>
    <div id="testimonial-carousel" class="testimonial-carousel box-shadow owl-carousel">
      <?php for($i=1 ; $i<20 ; $i++) {?>
      <div class="testi-item d-flex align-items-center text-center ">
        <div class="testi-content">
          <p>
            <?php 
           echo  $achievements[$language]["achievement$i"] ?>
          </p>
        </div>
      </div>
      <?php } ?>


    </div>
  </div>
</section>
<section class="counter-section padding">
  <div class="container">
    <div class="row counter-wrap text-center">

      <div class=" col-lg-6 col-sm-12 padding-15 d-flex justify-content-center">
        <div class="counter-content wow fadeInUp" data-wow-delay="300ms">
          <div class="counter">
            <span class="odometer" data-count="17">00</span>
          </div>
          <h4><?php echo $counter[$language]['experience'] ?></h4>
        </div>
      </div>
      <div class="col-lg-6 col-sm-12 padding-15 d-flex justify-content-center">
        <div class="counter-content wow fadeInUp" data-wow-delay="400ms">
          <div class="counter">
            <span class="odometer" data-count="27">00</span>
          </div>
          <h4><?php echo $counter[$language]['projects'] ?></h4>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="projects-section padding">
  <div class="container">
    <div class="row d-flex align-items-center">
      <div class="col-lg-8 col-md-6 sm-padding">
        <div class="section-heading mb-40">
          <span><?php echo $projects[$language]['projects'] ?></span>
          <h2>
            <?php echo $projects[$language]['project_des'] ?>
          </h2>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 sm-padding text-right">
        <a href="projects.php" class="default-btn"> <?php echo $projects[$language]['view_all'] ?></a>
      </div>
    </div>
    <div id="projects-carousel" class="projects-carousel box-shadow owl-carousel">
      <div class="project-item">
        <img src="../img/project-1.jpg" alt="projects" />
        <div class="overlay"></div>
        <a href="../img/project-1.jpg" class="view-icon img-popup" data-gall="project">
          <i class="fas fa-expand"></i></a>
        <div class="projects-content">
          <a href="#" class="category"> <?php echo $projects[$language]['Architecture'] ?></a>
        </div>
      </div>
      <div class="project-item">
        <img src="../img/project-2.jpg" alt="projects" />
        <div class="overlay"></div>
        <a href="../img/project-2.jpg" class="view-icon img-popup" data-gall="project">
          <i class="fas fa-expand"></i></a>
        <div class="projects-content">
          <a href="#" class="category"> <?php echo $projects[$language]['Architecture'] ?></a>

        </div>
      </div>
      <div class="project-item">
        <img src="../img/project-3.jpg" alt="projects" />
        <div class="overlay"></div>
        <a href="../img/project-3.jpg" class="view-icon img-popup" data-gall="project">
          <i class="fas fa-expand"></i></a>
        <div class="projects-content">
          <a href="#" class="category"> <?php echo $projects[$language]['Architecture'] ?></a>
        </div>
      </div>
      <div class="project-item">
        <img src="../img/project-4.jpg" alt="projects" />
        <div class="overlay"></div>
        <a href="../img/project-4.jpg" class="view-icon img-popup" data-gall="project">
          <i class="fas fa-expand"></i></a>
        <div class="projects-content">
          <a href="#" class="category"> <?php echo $projects[$language]['Architecture'] ?></a>
        </div>
      </div>
      <div class="project-item">
        <img src="../img/project-5.jpg" alt="projects" />
        <div class="overlay"></div>
        <a href="../img/project-5.jpg" class="view-icon img-popup" data-gall="project">
          <i class="fas fa-expand"></i></a>
        <div class="projects-content">
          <a href="#" class="category"> <?php echo $projects[$language]['Architecture'] ?></a>
        </div>
      </div>
    </div>
  </div>
</section>


<?php include('include/footer.php') ?>