<!-- ==================== Footer Start Here ==================== -->
<footer class="footer footer-two position-relative z-1 pt-140">
    <div class="footer-bg background-img position-absolute w-100 h-100 top-0 start-0 z-n1"
        data-background-image="assets/images/footer/footer-two-bg.jpg"></div>
    <div class="container container-two">
        <div class="footer-wrapper border-top border-dashed border-0 tw-pt-20 tw-pb-20 position-relative z-1">
            <div class="row gy-5">
                <div class="col-xl-3 col-lg-6 col-sm-6 col-xs-6">
                    <div class="footer-col-one" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                        <img src="assets/images/logo/logo.png" alt="Logo" class="max-w-200-px tw-mb-5">
                        <div class="d-flex flex-column">
                            <a href="mailto:<?php echo $settings['email']; ?>"
                                class="font-heading fw-normal tw-text-sm text-main-600 tw-mb-3 d-inline-block hover-text-secondary">
                                <?php echo $settings['email']; ?>
                            </a>
                            <a href="tel:<?php echo $settings['phone']; ?>"
                                class="font-heading fw-bold tw-text-xl text-main-600 tw-mb-5 d-inline-block hover-text-secondary">
                                <?php echo $settings['phone']; ?>
                            </a>
                        </div>
                        <div class="tw-hover-btn-wrapper d-inline-block">
                            <a class="tw-btn-circle tw-hover-btn-item tw-hover-btn" href="contact">
                                <span class="d-flex flex-column justify-content-center">
                                    <span class="tw-btn-circle-icon"><img src="assets/images/icon/footer-arrow.svg"
                                            alt="arrow"></span>
                                    <span
                                        class="text-white fw-bold"><?php echo lang('Hadi Gidelim', 'Let\'s Go'); ?></span>
                                </span>
                                <i class="tw-btn-circle-dot"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-sm-6 col-xs-6">
                    <div class="footer-col-two text-center" data-aos="fade-up" data-aos-duration="1000"
                        data-aos-delay="300">
                        <h4 class="cursor-big tw-text-2xl tw-mb-8">
                            <?php echo lang('Bültene Abone Ol', 'Subscribe to Newsletter'); ?>
                        </h4>
                        <form id="newsletterForm" class="tw-mt-6 position-relative">
                            <input type="email" id="subscriberEmail" name="email" required
                                class="form-control footer-form-input tw-ps-9 tw-pe-13 tw-h-12 focus-tw-placeholder-text-hidden tw-placeholder-transition-2 tw-mb-4 focus-border-main-600 tw-placeholder-text-neutral-300"
                                placeholder="<?php echo lang('E-posta...', 'Email...'); ?>">
                            <button type="submit"
                                class="footer-form-email position-absolute top-50 tw--translate-y-50 start-0 tw-ps-4 tw-text-sm">
                                <i class="ph ph-envelope"></i>
                            </button>
                            <div id="newsletterMessage" class="tw-text-sm tw-mt-2"></div>
                        </form>
                        <p class="text-main-600 fw-medium tw-mb-4">
                            <?php echo lang('Gizlilik Politikası | KVKK', 'Privacy Policy | KVKK'); ?>
                        </p>
                        <ul class="d-flex justify-content-center tw-gap-2">
                            <li>
                                <a href="<?php echo $settings['instagram']; ?>" target="_blank"
                                    class="tw-w-10 tw-h-10 d-inline-flex align-items-center justify-content-center bg-white tw-rounded-lg text-main-600 tw-text-xl"><i
                                        class="ph-bold ph-instagram-logo"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-8 col-md-12 col-sm-12 col-xs-12">
                    <div class="footer-right d-flex tw-gap-8">
                        <div class="footer-col-three" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400">
                            <h4 class="cursor-big tw-text-2xl tw-mb-8">
                                <?php echo lang('Özel Turlar', 'Private Tours'); ?>
                            </h4>
                            <ul class="d-flex flex-column tw-gap-4">
                                <?php
                                $ftours = $pdo->query("SELECT * FROM tours WHERE status = 1 AND is_featured = 1 LIMIT 5")->fetchAll();
                                foreach ($ftours as $ft):
                                    ?>
                                    <li><a href="tour/<?php echo lang($ft['slug_tr'], $ft['slug_en']); ?>"
                                            class="footer-link hover-underline">
                                            <?php echo lang($ft['title_tr'], $ft['title_en']); ?>
                                        </a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="footer-col-four" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="500">
                            <h4 class="cursor-big tw-text-2xl tw-mb-8">
                                <?php echo lang('Kurumsal', 'Corporate'); ?>
                            </h4>
                            <ul class="d-flex flex-column tw-gap-4">
                                <li><a href="about-us" class="footer-link hover-underline">
                                        <?php echo lang('Hakkımızda', 'About Us'); ?>
                                    </a></li>
                                <li><a href="corporate/mission-vision" class="footer-link hover-underline">
                                        <?php echo lang('Misyon & Vizyon', 'Mission & Vision'); ?>
                                    </a></li>
                                <li><a href="corporate/sustainability" class="footer-link hover-underline">
                                        <?php echo lang('Sürdürülebilirlik', 'Sustainability'); ?>
                                    </a></li>
                                <li><a href="blog" class="footer-link hover-underline">
                                        <?php echo lang('Blog', 'Blog'); ?>
                                    </a></li>
                                <li><a href="gallery" class="footer-link hover-underline">
                                        <?php echo lang('Galeri', 'Gallery'); ?>
                                    </a></li>
                                <li><a href="contact" class="footer-link hover-underline">
                                        <?php echo lang('Biz Ulaşın', 'Contact Us'); ?>
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="container">
        <div class="border-top border-dashed border-0 tw-py-8">
            <div class="container container-two">
                <div class="footer-copyright-wrap d-flex align-items-center justify-content-center">
                    <p class="fw-normal text-main-600" style="margin: 0; padding: 0;">Copyright © 2026 <span
                            class="fw-semibold">Prive by ITC | SIO</span></p>
                </div>
            </div>
        </div>
    </div>
</footer>

</div>

<!-- Jquery js -->
<script src="assets/js/jquery-3.7.1.min.js"></script>
<!-- phosphor Js -->
<script src="assets/js/phosphor-icon.js"></script>
<!-- Bootstrap Bundle Js -->
<script src="assets/js/boostrap.bundle.min.js"></script>
<!-- appear Js -->
<script src="assets/js/appear.min.js"></script>
<!-- Nice Select Js -->
<script src="assets/js/nice-select.js"></script>
<!-- swiper bundle Js -->
<script src="assets/js/swiper-bundle.js"></script>
<!-- slick slider Js -->
<script src="assets/js/slick.js"></script>
<!-- counter Js -->
<script src="assets/js/purecounter.js"></script>
<!-- knob Js -->
<script src="assets/js/jquery-knob.js"></script>
<!-- wow js -->
<script src="assets/js/gsap.min.js"></script>
<!-- Aos js -->
<script src="assets/js/aos.js"></script>
<!-- isotope js -->
<script src="assets/js/isotope.pkgd.min.js"></script>
<!-- imagesloaded js -->
<script src="assets/js/imagesloaded.pkgd.min.js"></script>
<!-- wow js -->
<script src="assets/js/wow.js"></script>
<!-- range-slider js -->
<script src="assets/js/range-slider.js"></script>
<!-- SplitText -->
<script src="assets/js/SplitText.min.js"></script>
<!-- Scroll Trigger -->
<script src="assets/js/ScrollTrigger.min.js"></script>
<!-- ScrollSmoother -->
<script src="assets/js/ScrollSmoother.min.js"></script>
<!-- custom GSAP -->
<script src="assets/js/custom-gsap.js"></script>

<!-- Popup Libraries -->
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.2/css/lightgallery-bundle.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.2/lightgallery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

<script src="assets/js/main.js"></script>

<script>
    $(document).ready(function () {
        // LightGallery Init
        if($('.lightgallery-container').length > 0) {
            $('.lightgallery-container').each(function () {
                lightGallery(this, {
                    selector: '.lightgallery',
                    download: false
                });
            });
        }

        // Magnific Popup for Videos
        if($('.popup-video').length > 0) {
            $('.popup-video').magnificPopup({
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false,
                fixedContentPos: false
            });
        }
        // Newsletter Subscription
        $('#newsletterForm').on('submit', function (e) {
            e.preventDefault();
            const email = $('#subscriberEmail').val();
            const $msg = $('#newsletterMessage');
            const $btn = $(this).find('button');

            $btn.prop('disabled', true).css('opacity', '0.5');
            $msg.text('...').removeClass('text-success text-danger');

            $.ajax({
                url: 'subscribe.php',
                type: 'POST',
                data: { email: email },
                dataType: 'json',
                success: function (response) {
                    $msg.text(response.message);
                    if(response.status === 'success') {
                        $msg.addClass('text-success').removeClass('text-danger');
                        $('#subscriberEmail').val('');
                    } else {
                        $msg.addClass('text-danger').removeClass('text-success');
                    }
                },
                error: function () {
                    $msg.text('Error occurred.').addClass('text-danger');
                },
                complete: function () {
                    $btn.prop('disabled', false).css('opacity', '1');
                }
            });
        });
    });
</script>

</body>

</html>