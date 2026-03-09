<?php include 'header.php'; ?>

<!-- ==================== Breadcrumb Start Here ==================== -->
<?php $banner = !empty($settings['banner_tours']) ? 'uploads/' . $settings['banner_tours'] : 'assets/images/breadcrumb/breadcrumb-bg.jpg'; ?>
<section class="breadcrumb-area background-img" data-background-image="<?php echo $banner; ?>">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div>
                    <h2 class="breadcrumb-title text-center tw-mb-6">
                        <?php echo lang('Turlarımız', 'Our Tours'); ?>
                    </h2>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ==================== Breadcrumb End Here ==================== -->

<section class="package-ip-area pt-140">
    <div class="container">
        <div class="row justify-content-between align-items-end tw-mb-10">
            <div class="col-xl-7 col-lg-8">
                <div data-aos-duration="1000" data-aos-delay="200">
                    <div class="tw-mb-6">
                        <p class="font-heading text-main-600 fw-bold text-capitalize">
                            <?php
                            $cat = isset($_GET['c']) ? (int) $_GET['c'] : 0;
                            $where = "status = 1";
                            if ($cat > 0)
                                $where .= " AND cat_id = $cat";
                            $now = date('Y-m-d H:i:s');
                            $where .= " AND (end_date >= '$now' OR end_date IS NULL)";
                            $stmt = $pdo->query("SELECT COUNT(*) FROM tours WHERE $where");
                            $total_tours = $stmt->fetchColumn();
                            ?>
                            <?php echo lang('Gösterilen', 'Showing'); ?> <span
                                class="text-main-two-600"><?php echo $total_tours; ?></span>
                            <?php echo lang('Tur', 'Tours'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <?php
            $tours = $pdo->query("SELECT * FROM tours WHERE $where ORDER BY id DESC")->fetchAll();
            $delay = 200;
            foreach ($tours as $tour):
                ?>
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="service-two-wrapper bg-white tw-p-4 tw-rounded-xl tw-mb-8" data-aos="fade-up"
                        data-aos-duration="1000" data-aos-delay="<?php echo $delay; ?>">
                        <div class="service-two-thumb tw-mb-5 position-relative overflow-hidden tour-image-wrapper">
                            <a href="tour/<?php echo lang($tour['slug_tr'], $tour['slug_en']); ?>">
                                <img class="tw-rounded-xl w-100 main-img"
                                    src="uploads/tours/<?php echo $tour['main_image']; ?>" alt="thumb">
                                <?php if (!empty($tour['hover_image'])): ?>
                                    <img class="tw-rounded-xl w-100 hover-img position-absolute top-0 start-0"
                                        src="uploads/tours/<?php echo $tour['hover_image']; ?>" alt="thumb">
                                <?php endif; ?>
                            </a>
                        </div>
                        <div class="service-two-content tw-px-2 tw-mb-2">
                            <div class="d-flex justify-content-between align-items-center tw-mb-2">
                                <span class="service-two-location"><i class="ph ph-map-pin"></i>
                                    <?php echo lang($tour['location_tr'], $tour['location_en']); ?></span>
                                <?php if (!empty($tour['start_date'])): ?>
                                    <span class="service-two-location"><i class="ph ph-calendar-blank"></i>
                                        <?php echo lang('Tur Tarihi: ', 'Tour Date: ') . date('d.m.Y', strtotime($tour['start_date'])); ?></span>
                                <?php endif; ?>
                            </div>
                            <h4 class="tw-text-8 fw-normal text-capitalize tw-mb-2">
                                <a class="hover-text-secondary"
                                    href="tour/<?php echo lang($tour['slug_tr'], $tour['slug_en']); ?>"><?php echo lang($tour['title_tr'], $tour['title_en']); ?></a>
                            </h4>
                            <p class="service-two-paragraph tw-mb-5">
                                <?php echo shorten(lang($tour['short_desc_tr'], $tour['short_desc_en']), 100); ?>
                            </p>
                            <div class="service-two-wrap tw-rounded-xl tw-py-4 tw-px-6">
                                <div class="service-two-star d-flex tw-gap-6 tw-pb-4 tw-mb-6">
                                    <span
                                        class="text-main-600 fw-medium"><?php echo lang($tour['duration_tr'], $tour['duration_en']); ?></span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                                    <div class="service-two-price">
                                        <!-- Price placeholer to match the design aesthetics strictly -->
                                    </div>
                                    <div>
                                        <a class="font-heading tw-text-sm text-uppercase text-main-600 fw-bold hover-text-secondary"
                                            href="tour/<?php echo lang($tour['slug_tr'], $tour['slug_en']); ?>">
                                            <?php echo lang('Detay', 'Detail'); ?> <i
                                                class="tw-text-base ph ph-arrow-up-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $delay += 100;
                if ($delay > 400)
                    $delay = 200;
            endforeach;
            ?>
        </div>
    </div>
</section>


<section class="cta-area py-140 background-img position-relative z-1"
    data-background-image="assets/images/cta/cta-bg.jpg">
    <div class="container">
        <div class="row justify-content-center tw-pb-20">
            <div class="col-xl-10">
                <div class="section-wrapper text-center position-relative z-1" data-aos="fade-up"
                    data-aos-duration="1000" data-aos-delay="200">
                    <h2 class="section-title fw-normal tw-mb-7 char-animation text-white char-animation"> <span
                            class="text-main-600">Let’sCapture</span> BeautyoftheWorld</h2>
                    <div class="gallery-button d-flex justify-content-center">
                        <a class="primary-btn bg-main-two-600 text-main-600 tw-py-4 tw-px-8 fs-15 text-capitalize fw-bold font-heading tw-gap-2 d-inline-flex align-items-center tw-rounded-4xl"
                            href="contact.php">Booking Today <i class="ph ph-arrow-up-right"></i></a>
                    </div>
                    <div class="gallery-shape">
                        <img class="gallery-shape-1 position-absolute start-0 z-n1"
                            src="assets/images/gallery/gallery-shape1.png" alt="shape">
                        <img class="gallery-shape-2 position-absolute end-0 z-n1"
                            src="assets/images/gallery/gallery-shape2.png" alt="shape">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="cta-bg-shape position-absolute start-0 z-n1">
        <img src="assets/images/cta/cta-bg-shape.png" alt="shape">
    </div>
</section>

<section class="instagram-area">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="text-center tw-mb-6">
                    <h6 class="instagram-title tw-text-2xl fw-normal text-capitalize">...want to become a dontation
                        partner & contribution...</h6>
                </div>
            </div>
        </div>
        <div class="row row-cols-xl-6 row-cols-md-3 row-cols-sm-3 row-cols-1">
            <div class="col">
                <div class="instagram-wrapper" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                    <div class="instagram-thumb position-relative z-1 overflow-hidden">
                        <img class="tw-rounded-lg" src="assets/images/instagram/instagram-thumb1.jpg" alt="thumb">
                        <div class="instagram-btn position-absolute z-1">
                            <a href="#"><span><img src="assets/images/icon/instagram.svg" alt="instagram"></span></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="instagram-wrapper" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
                    <div class="instagram-thumb position-relative z-1 overflow-hidden">
                        <img class="tw-rounded-lg" src="assets/images/instagram/instagram-thumb2.jpg" alt="thumb">
                        <div class="instagram-btn position-absolute z-1">
                            <a href="#"><span><img src="assets/images/icon/instagram.svg" alt="instagram"></span></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="instagram-wrapper" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400">
                    <div class="instagram-thumb position-relative z-1 overflow-hidden">
                        <img class="tw-rounded-lg" src="assets/images/instagram/instagram-thumb3.jpg" alt="thumb">
                        <div class="instagram-btn position-absolute z-1">
                            <a href="#"><span><img src="assets/images/icon/instagram.svg" alt="instagram"></span></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="instagram-wrapper" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="500">
                    <div class="instagram-thumb position-relative z-1 overflow-hidden">
                        <img class="tw-rounded-lg" src="assets/images/instagram/instagram-thumb4.jpg" alt="thumb">
                        <div class="instagram-btn position-absolute z-1">
                            <a href="#"><span><img src="assets/images/icon/instagram.svg" alt="instagram"></span></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="instagram-wrapper" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="600">
                    <div class="instagram-thumb position-relative z-1 overflow-hidden">
                        <img class="tw-rounded-lg" src="assets/images/instagram/instagram-thumb5.jpg" alt="thumb">
                        <div class="instagram-btn position-absolute z-1">
                            <a href="#"><span><img src="assets/images/icon/instagram.svg" alt="instagram"></span></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="instagram-wrapper">
                    <div class="instagram-thumb position-relative z-1 overflow-hidden" data-aos="fade-up"
                        data-aos-duration="1000" data-aos-delay="700">
                        <img class="tw-rounded-lg" src="assets/images/instagram/instagram-thumb6.jpg" alt="thumb">
                        <div class="instagram-btn position-absolute z-1">
                            <a href="#"><span><img src="assets/images/icon/instagram.svg" alt="instagram"></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .tour-image-wrapper img.main-img {
        transition: opacity 0.5s ease;
    }

    .tour-image-wrapper .hover-img {
        opacity: 0;
        transition: opacity 0.5s ease;
    }

    .tour-image-wrapper:hover .main-img {
        opacity: 0;
    }

    .tour-image-wrapper:hover .hover-img {
        opacity: 1;
    }
</style>

<?php include 'footer.php'; ?>