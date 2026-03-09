<?php include 'header.php'; ?>

<div id="scrollSmoother-container">
    <section class="banner-two-area">
        <div class="banner-two-slider">
            <div class="banner-two-active swiper-container">
                <div class="swiper-wrapper">
                    <?php
                    $sliders = $pdo->query("SELECT * FROM sliders WHERE status = 1 ORDER BY sort_order ASC")->fetchAll();
                    if (empty($sliders)) {
                        // Fallback static slider
                        echo '<div class="banner-two-bg background-img position-absolute w-100 top-0 start-0 z-n1" data-background-image="assets/images/banner/banner-two-bg.jpg"></div>';
                    }
                    foreach ($sliders as $index => $slider):
                        ?>
                        <div class="swiper-slide position-relative">
                            <div class="banner-two-bg background-img position-absolute w-100 top-0 start-0 z-n1"
                                id="slider-bg-<?php echo $slider['id']; ?>"
                                data-background-image="uploads/sliders/<?php echo $slider['image']; ?>"></div>

                            <?php if (!empty($slider['video_url'])):
                                $vid_id = '';
                                if (strpos($slider['video_url'], 'v=') !== false) {
                                    $vid_id = explode('v=', $slider['video_url'])[1];
                                    if (strpos($vid_id, '&') !== false)
                                        $vid_id = explode('&', $vid_id)[0];
                                } elseif (strpos($slider['video_url'], 'youtu.be/') !== false) {
                                    $vid_id = explode('youtu.be/', $slider['video_url'])[1];
                                }
                                ?>
                                <div id="video-container-<?php echo $slider['id']; ?>"
                                    class="position-absolute w-100 h-100 top-0 start-0 z-n1 overflow-hidden"
                                    style="display:none;">
                                    <iframe
                                        style="width: 100vw; height: 56.25vw; min-height: 100vh; min-width: 177.77vh; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); pointer-events: none;"
                                        src="https://www.youtube.com/embed/<?php echo $vid_id; ?>?autoplay=1&mute=1&loop=1&playlist=<?php echo $vid_id; ?>&controls=0&showinfo=0&rel=0"
                                        frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                </div>
                                <script>
                                    setTimeout(function () {
                                        var bg = document.getElementById('slider-bg-<?php echo $slider['id']; ?>');
                                        var vid = document.getElementById('video-container-<?php echo $slider['id']; ?>');
                                        if(bg && vid) {
                                            bg.style.display = 'none';
                                            vid.style.display = 'block';
                                        }
                                    }, 3000);
                                </script>
                            <?php endif; ?>

                            <div class="container h-100 position-relative z-1">
                                <div class="row h-100 justify-content-center align-items-center text-center">
                                    <div class="col-xl-8">
                                        <div class="banner-content-wrapper">
                                            <h6
                                                class="section-subtitle font-sofia tw-text-2xl fw-normal tw-mb-12 text--white">
                                                <?php echo lang($slider['subtitle_tr'], $slider['subtitle_en']); ?>
                                            </h6>
                                            <h3 class="banner-two-title text-uppercase tw-mb-19 text--white"
                                                style="font-size: 8rem; line-height: 1;">
                                                <?php echo lang($slider['title_tr'], $slider['title_en']); ?>
                                            </h3>
                                            <div class="banner-two-button">
                                                <a class="primary-btn bg-main-two-600 text-main-600 tw-py-5 tw-px-18 fs-15 text-capitalize fw-bold font-heading tw-gap-2 d-inline-flex align-items-center tw-rounded-4xl"
                                                    href="<?php echo $slider['button_link']; ?>">
                                                    <?php echo lang($slider['button_text_tr'], $slider['button_text_en']); ?>
                                                    <i class="ph ph-arrow-up-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </section>

    <!-- About Section -->
    <?php if ($settings['about_status']): ?>
        <section class="about-three-area pt-140" style="padding-top: 140px; padding-bottom: 140px;">
            <div class="container">
                <div class="row">
                    <div class="col-xl-5 col-lg-7">
                        <div class="about-three-thumb position-relative z-1" data-aos="fade-up" data-aos-duration="1000"
                            data-aos-delay="200">
                            <img src="uploads/<?php echo $settings['about_img']; ?>" alt="thumb" class="w-100">
                            <?php if ($settings['about_img2']): ?>
                                <div class="about-three-thumb-two position-absolute z-1">
                                    <img src="uploads/<?php echo $settings['about_img2']; ?>" alt="thumb">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-xl-7">
                        <div class="about-three-wrapper" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
                            <h2 class="about-three-title tw-text-15 text-invert fw-normal tw-mb-13">
                                <?php echo lang('Prive | Hakkımızda', 'Prive | About Us'); ?>
                            </h2>
                            <div class="about-three-content-inner d-flex flex-column flex-md-row tw-gap-10 tw-md-gap-21">
                                <div>
                                    <h6
                                        class="about-subtitle section-subtitle font-sofia tw-text-6 fw-normal tw-mb-4 text-main-two-600">
                                        <?php echo lang('Bizi<br>Tanıyın', 'Get to Know Us'); ?>
                                    </h6>
                                </div>
                                <div>
                                    <p class="about-three-paragraph tw-text-lg fw-medium text-main-600 tw-mb-9">
                                        <?php echo lang($settings['about_content_tr'], $settings['about_content_en']); ?>
                                    </p>
                                    <div class="about-three-button">
                                        <a class="primary-btn bg-main-two-600 text-main-600 tw-py-4 tw-px-8 fs-15 text-uppercase fw-bold font-heading tw-gap-2 d-inline-flex align-items-center tw-rounded-4xl"
                                            href="about-us">
                                            <?php echo lang('Devamı', 'Read More'); ?><i class="ph ph-arrow-up-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>



    <!-- Featured Tours -->
    <section class="pb-140 position-relative z-1">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 text-center tw-mb-10" data-aos="fade-up">
                    <h6 class="section-subtitle font-sofia tw-text-2xl fw-normal tw-mb-4">
                        <?php echo lang('Prive | Özel Turlar', 'Prive | Private Tours'); ?>
                    </h6>
                    <h2 class="section-title fw-normal tw-mb-7">
                        <?php echo lang('Size Özel Turlarımız', 'Our Special Tours'); ?>
                    </h2>
                </div>
            </div>
            <div class="row row-gap-4">
                <?php
                $now = date('Y-m-d H:i:s');
                $tours = $pdo->query("SELECT * FROM tours WHERE status = 1 AND (end_date >= '$now' OR end_date IS NULL) ORDER BY id DESC LIMIT 6")->fetchAll();
                foreach ($tours as $tour):
                    ?>
                    <div class="col-xl-4 col-lg-6">
                        <div class="service-two-wrapper bg-white tw-p-4 tw-rounded-xl" data-aos="fade-up">
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
                            <div class="service-two-content tw-px-2">
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
                                        href="tour/<?php echo lang($tour['slug_tr'], $tour['slug_en']); ?>">
                                        <?php echo lang($tour['title_tr'], $tour['title_en']); ?>
                                    </a>
                                </h4>
                                <p class="service-two-paragraph tw-mb-5">
                                    <?php echo shorten(lang($tour['short_desc_tr'], $tour['short_desc_en']), 100); ?>
                                </p>
                                <div class="service-two-wrap tw-rounded-xl tw-py-4 tw-px-6">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="text-main-600 fw-medium">
                                            <?php echo lang($tour['duration_tr'], $tour['duration_en']); ?>
                                        </span>
                                        <a class="font-heading tw-text-sm text-uppercase text-main-600 fw-bold hover-text-secondary"
                                            href="tour/<?php echo lang($tour['slug_tr'], $tour['slug_en']); ?>">
                                            <?php echo lang('Detay', 'Detail'); ?> <i class="ph ph-arrow-up-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center tw-mt-10">
                <a class="primary-btn bg-main-600 text-white tw-py-4 tw-px-10 fs-15 text-uppercase fw-bold font-heading tw-rounded-4xl"
                    href="tours">
                    <?php echo lang('Tüm Turları Gör', 'View All Tours'); ?>
                </a>
            </div>
        </div>
    </section>


    <!-- Top Categories Section -->
    <section class="catagori-area py-140 position-relative z-1 mt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="section-wrapper text-center tw-pt-20 tw-mb-19" data-aos="fade-up"
                        data-aos-duration="1000" data-aos-delay="200">
                        <h6 class="section-subtitle font-sofia tw-text-2xl fw-normal tw-mb-4">
                            <?php echo lang('Prive Özel Turlar', 'Prive Private Tours'); ?>
                        </h6>
                        <h2 class="section-title fw-normal tw-mb-7 char-animation text-white">
                            <?php echo lang('Kategorilerimiz', 'Our Categories'); ?>
                        </h2>
                        <p class="section-paragraph tw-text-lg fw-normal text-white">
                            <?php echo lang('Size sunduğumuz özel tur kategorilerini keşfedin.', 'Discover the special tour categories we offer you.'); ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <?php
                $cats = $pdo->query("SELECT * FROM categories WHERE status = 1 ORDER BY id DESC LIMIT 5")->fetchAll();
                if (!empty($cats)) {
                    echo '<div class="col-xl-11">';
                    echo '<div class="catagori-widgets one wt-hover__widget d-flex justify-content-between tw-mb-10">';
                    foreach (array_slice($cats, 0, 3) as $i => $cat) {
                        $is_current = ($i == 0) ? 'current' : '';
                        $img = !empty($cat['image']) ? 'uploads/categories/' . $cat['image'] : 'assets/images/catagori/catagori-thumb2.jpg';
                        ?>
                        <div class="catagori-item <?php echo $is_current; ?> wt-widget__item wt-hover__reveal-item">
                            <a href="tours?cat=<?php echo $cat['id']; ?>">
                                <div class="catagori-content d-flex tw-gap-4 align-items-center">
                                    <?php if ($i > 0): ?><span><img src="assets/images/icon/catagori-plant.svg"
                                                alt="plant"></span><?php endif; ?>
                                    <h3
                                        class="catagori-title text-white tw-text-18 fw-normal text-decoration-underline hover-text-secondary">
                                        <?php echo lang($cat['name_tr'], $cat['name_en']); ?>
                                    </h3>
                                </div>
                            </a>
                            <div class="wt-hover__reveal-bg background-img" data-background-image="<?php echo $img; ?>"></div>
                        </div>
                        <?php
                    }
                    echo '</div></div>';

                    if (count($cats) > 3) {
                        echo '<div class="col-xl-6 col-lg-8 col-md-10">';
                        echo '<div class="catagori-widgets two wt-hover__widget d-flex justify-content-between">';
                        foreach (array_slice($cats, 3, 2) as $i => $cat) {
                            $is_current = ($i == 0) ? 'current' : '';
                            $img = !empty($cat['image']) ? 'uploads/categories/' . $cat['image'] : 'assets/images/catagori/catagori-thumb4.jpg';
                            ?>
                            <div class="catagori-item <?php echo $is_current; ?> wt-widget__item wt-hover__reveal-item">
                                <a href="tours?cat=<?php echo $cat['id']; ?>">
                                    <div class="catagori-content d-flex tw-gap-4 align-items-center">
                                        <?php if ($i > 0): ?><span><img src="assets/images/icon/catagori-plant.svg"
                                                    alt="plant"></span><?php endif; ?>
                                        <h3
                                            class="catagori-title text-white tw-text-18 fw-normal text-decoration-underline hover-text-secondary">
                                            <?php echo lang($cat['name_tr'], $cat['name_en']); ?>
                                        </h3>
                                    </div>
                                </a>
                                <div class="wt-hover__reveal-bg background-img" data-background-image="<?php echo $img; ?>"></div>
                            </div>
                            <?php
                        }
                        echo '</div></div>';
                    }
                }
                ?>
            </div>
        </div>
        <div class="catagori-shape position-absolute start-0 z-n1 w-100">
            <img src="assets/images/catagori/catagori-shape.png" alt="shape">
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallary-ip-area pt-140">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="section-wrapper text-center tw-pt-20 tw-mb-19" data-aos="fade-up"
                        data-aos-duration="1000" data-aos-delay="200">
                        <h6 class="section-subtitle font-sofia tw-text-2xl fw-normal tw-mb-4">Prive Özel Turlar
                        </h6>
                        <h2 class="section-title fw-normal tw-mb-7 char-animation text-dark">
                            <?php echo lang('Galeri', 'Gallery'); ?>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="row align-items-center masonry lightgallery-container">
                <?php
                $gallery = $pdo->query("SELECT * FROM gallery WHERE status = 1 ORDER BY id DESC LIMIT 6")->fetchAll();

                // The exact grid pattern classes array according to index.html design
                $grid_classes = ['col-xl-3', 'col-xl-6', 'col-xl-3', 'col-xl-3', 'col-xl-6', 'col-xl-3'];

                foreach ($gallery as $i => $item):
                    $class = $grid_classes[$i % 6];
                    $img_class = 'w-100 object-fit-cover tw-rounded-lg';
                    if ($class == 'col-xl-6') {
                        $img_class .= ' gallary-ip-thumb-height';
                    }

                    if ($item['type'] == 'photo') {
                        $url = "uploads/gallery/" . $item['file'];
                        $target = "uploads/gallery/" . $item['file'];
                        $thumb = $url;
                        $class_link = 'lightgallery';
                    } else {
                        // is youtube
                        $yt_id = '';
                        if (strpos($item['file'], 'v=') !== false) {
                            $yt_id = explode('v=', $item['file'])[1];
                            if (strpos($yt_id, '&') !== false)
                                $yt_id = explode('&', $yt_id)[0];
                        } elseif (strpos($item['file'], 'youtu.be/') !== false) {
                            $yt_id = explode('youtu.be/', $item['file'])[1];
                        }
                        $url = "https://www.youtube.com/watch?v=" . $yt_id;
                        $target = "https://www.youtube.com/watch?v=" . $yt_id;
                        $thumb = "https://img.youtube.com/vi/" . $yt_id . "/hqdefault.jpg";
                        $class_link = 'popup-video';
                    }
                    ?>
                    <div class="<?php echo $class; ?> col-lg-6 col-md-6 tw-mb-7" data-aos="fade-up">
                        <div class="gallary-ip-thumb position-relative z-1 overflow-hidden">
                            <img class="<?php echo $img_class; ?>" src="<?php echo $thumb; ?>" alt="gallery"
                                style="min-height:280px;">
                            <div class="gallary-ip-button position-absolute start-50 translate-middle">
                                <a class="<?php echo $class_link; ?> gallary-ip-btn tw-w-25 tw-h-25 lh-1 d-inline-flex align-items-center justify-content-center bg-main-600 rounded-circle"
                                    href="<?php echo $target; ?>"><span><img src="assets/images/icon/gallery-ip-button.svg"
                                            alt="button"></span></a>
                            </div>
                            <div class="gallary-ip-content position-absolute">
                                <h4 class="tw-text-2xl tw-mb-2 text-white">
                                    <?php echo lang($item['title_tr'], $item['title_en']); ?>
                                </h4>
                                <p class="gallary-ip-paragraph fw-medium">Prive Voyages</p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center tw-mt-10" style="position: relative; z-index: 20;">
                <a class="primary-btn bg-main-600 text-white tw-py-4 tw-px-10 fs-15 text-uppercase fw-bold font-heading tw-rounded-4xl"
                    href="gallery" style="position: relative; z-index: 21; display: inline-block;">
                    <?php echo lang('Tüm Galeriyi Gör', 'View All Gallery'); ?>
                </a>
            </div>
        </div>
    </section>


    <!-- FAQ Section -->
    <section class="faq-area pt-140 pb-70 position-relative z-1">
        <div class="container">
            <div class="row">
                <div class="col-xl-5">
                    <div class="section-wrapper tw-mb-14 faq-sticky">
                        <h6 class="section-subtitle font-sofia tw-text-2xl fw-normal tw-mb-4">Prive</h6>
                        <h2 class="section-title fw-normal tw-mb-7 char-animation">
                            <?php echo lang('Sıkça Sorulan Sorular', 'Frequently Asked Questions'); ?>
                        </h2>
                        <p class="section-paragraph tw-text-lg fw-normal">
                            <?php echo lang('Özel turlarımız hakkında en çok merak edilenler.', 'The most frequently asked questions about our private tours.'); ?>
                        </p>
                    </div>
                </div>
                <div class="col-xl-7">
                    <div class="faq-wrapper">
                        <div class="accordion" id="general_faqaccordion">
                            <?php
                            $faqs = $pdo->query("SELECT * FROM faqs WHERE status = 1 ORDER BY sort_order ASC, id DESC LIMIT 6")->fetchAll();
                            if (empty($faqs)) {
                                echo "<p>No FAQs available.</p>";
                            }
                            foreach ($faqs as $i => $faq):
                                $show = ($i == 0) ? 'show' : '';
                                $collapsed = ($i == 0) ? '' : 'collapsed';
                                ?>
                                <div class="accordion-item faq-accordion-item">
                                    <h2 class="accordion-header" id="faq_<?php echo $faq['id']; ?>">
                                        <button class="accordion-button faq-accordion-button <?php echo $collapsed; ?>"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#faq_collapse_<?php echo $faq['id']; ?>"
                                            aria-expanded="<?php echo ($i == 0) ? 'true' : 'false'; ?>"
                                            aria-controls="faq_collapse_<?php echo $faq['id']; ?>">
                                            <?php echo lang($faq['question_tr'], $faq['question_en']); ?>
                                        </button>
                                    </h2>
                                    <div id="faq_collapse_<?php echo $faq['id']; ?>"
                                        class="accordion-collapse collapse <?php echo $show; ?>"
                                        aria-labelledby="faq_<?php echo $faq['id']; ?>"
                                        data-bs-parent="#general_faqaccordion">
                                        <div class="accordion-body faq-accordion-body">
                                            <p><?php echo lang($faq['answer_tr'], $faq['answer_en']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="faq-bg-shape position-absolute start-0 z-n1">
            <div class="line_shape_3">
                <svg width="1920" height="287" viewBox="0 0 1920 287" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path id="line_path_3"
                        d="M0 286C235.807 161.804 715.277 -31.6361 948 104C1192.5 246.5 1698.62 102.064 1920 1"
                        stroke="#CEC7BE" stroke-dasharray="4 4" />
                    <g id="paper-plane_3">
                        <path
                            d="M8.52862 0.643979C8.65472 0.654731 12.5944 5.1037 15.7327 8.66873C16.0616 8.64319 16.3357 8.62234 16.3357 8.62234L16.6797 8.10704L19.1766 7.91312L19.3247 9.82093L16.9109 10.0082C17.5051 10.6843 18.0375 11.2905 18.4682 11.7814C21.1362 11.6535 26.2869 11.419 27.1597 11.4798C28.3465 11.5623 30.0418 12.128 30.0368 12.5126C30.0318 12.8972 28.4581 13.7513 27.5404 14.0673C26.8454 14.3064 21.1236 14.8358 18.3473 15.0812C17.9672 15.7098 17.5181 16.4498 17.0277 17.2557L19.8851 17.0334L20.0333 18.9412L17.5364 19.1351L17.1173 18.6791C17.1173 18.6791 16.5828 18.7209 16.1125 18.7568C13.4463 23.1189 10.1742 28.3838 9.92358 28.3613C9.49623 28.3236 8.49302 27.2688 8.49302 27.2688L9.96254 22.7816C9.52126 22.7861 9.19671 22.7262 9.18859 22.6259C9.17946 22.5123 9.58103 22.3871 10.1098 22.3303L10.8472 20.079C10.5083 20.0669 10.2756 20.0104 10.2686 19.9261C10.2611 19.828 10.5617 19.7202 10.9855 19.6563L12.4419 15.2098L6.2911 15.3333L3.00246 20.403L1.50247 20.2363L2.61746 15.6496L4.02107 15.0837C4.02107 15.0837 2.47224 15.1697 2.47782 14.7758C2.4834 14.3819 3.85476 14.0784 3.85476 14.0784L2.61875 13.8202L0.562775 9.68354L2.55228 8.81392L6.31432 13.4946L12.3097 12.4928L10.3991 8.9086C9.84767 8.93961 9.41072 8.87701 9.4014 8.76046C9.39341 8.6582 9.71898 8.54567 10.1719 8.48324L9.06011 6.39696C9.04924 6.39738 9.03867 6.39894 9.02779 6.39936C8.42442 6.44647 7.92762 6.38376 7.91735 6.25891C7.90894 6.14578 8.30449 6.02093 8.82844 5.96421L6.75475 2.07323C6.75475 2.07323 8.31696 0.625839 8.5282 0.6447L8.52862 0.643979Z"
                            fill="#113A75" />
                    </g>
                </svg>
            </div>
        </div>
    </section>

    <!-- Blog Section -->
    <section class="blog-two-area blog-panel-area pt-70 pb-140">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-xl-6 col-lg-7">
                    <div class="section-wrapper tw-mb-14" data-aos="fade-up" data-aos-duration="1000"
                        data-aos-delay="200">
                        <h6 class="section-subtitle font-sofia tw-text-2xl fw-normal tw-mb-4">
                            <?php echo lang('Blog', 'Blog'); ?>
                        </h6>
                        <h2 class="section-title fw-normal tw-mb-7 char-animation">
                            <?php echo lang('Özel Turlardan Haberler', 'News From Private Tours'); ?>
                        </h2>
                        <p class="tw-text-lg fw-normal">
                            <?php echo lang('En güncel turlarımızdan ve seyahat rotalarımızdan haberdar olun.', 'Stay informed about our latest tours and travel routes.'); ?>
                        </p>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4">
                    <div class="counter-button project-two-button d-flex justify-content-end flex-wrap"
                        data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
                        <a class="primary-btn bg-main-two-600 text-main-600 tw-py-4 tw-px-8 fs-15 text-uppercase fw-bold font-heading tw-gap-2 d-inline-flex align-items-center tw-rounded-4xl"
                            href="blog"><?php echo lang('Prive', 'Prive'); ?> <i class="ph ph-arrow-up-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <?php
                    $blogs = $pdo->query("SELECT * FROM blogs WHERE status = 1 ORDER BY id DESC LIMIT 3")->fetchAll();
                    foreach ($blogs as $blog):
                        $blog_url = "blog/" . lang($blog['slug_tr'], $blog['slug_en']);
                        ?>
                        <!-- blog item -->
                        <div class="row blog-panel tw-mb-8">
                            <div class="col-xl-12">
                                <div
                                    class="blog-two-wrapper tw-rounded-xl d-flex align-items-center justify-content-between tw-p-2">
                                    <div class="blog-two-content">
                                        <div class="blog-two-meta tw-mb-6 tw-ms-4">
                                            <ul class="d-flex tw-gap-10">
                                                <li class="fw-medium text-black">
                                                    <?php echo date('d', strtotime($blog['created_at'])) . ' ' . lang(getMonthNameTurkish(date('n', strtotime($blog['created_at']))), date('F', strtotime($blog['created_at']))) . ' ' . date('Y', strtotime($blog['created_at'])); ?>
                                                </li>
                                            </ul>
                                        </div>
                                        <h4 class="blog-two-title tw-text-9 text-capitalize fw-normal tw-mb-9">
                                            <a class="hover-text-secondary" href="<?php echo $blog_url; ?>">
                                                <?php echo lang($blog['title_tr'], $blog['title_en']); ?>
                                            </a>
                                        </h4>
                                        <div class="blog-two-button">
                                            <a class="text-black fw-medium d-inline-flex tw-gap-4"
                                                href="<?php echo $blog_url; ?>">
                                                <?php echo lang('Devamını Oku', 'Read More'); ?>
                                                <span><img src="assets/images/icon/blog-two-arrow.svg" alt="arrow"></span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="blog-two-thumb">
                                        <a href="<?php echo $blog_url; ?>">
                                            <img src="uploads/blog/<?php echo $blog['image']; ?>" alt="thumb"
                                                class="blog-static-img">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-area py-140 background-img position-relative z-1"
        data-background-image="assets/images/cta/cta-bg.jpg">
        <div class="container">
            <div class="row justify-content-center tw-pb-20">
                <div class="col-xl-10">
                    <div class="section-wrapper text-center position-relative z-1" data-aos="fade-up"
                        data-aos-duration="1000" data-aos-delay="200">
                        <h2 class="section-title fw-normal tw-mb-7 char-animation text-white char-animation"> <span
                                class="text-main-600 text-white">Prive</span><br>
                            <?php echo lang('Özel Turların Adresi', 'Address of Private Tours'); ?></h2>
                        <div class="gallery-button d-flex justify-content-center">
                            <a class="primary-btn bg-main-two-600 text-main-600 tw-py-4 tw-px-8 fs-15 text-capitalize fw-bold font-heading tw-gap-2 d-inline-flex align-items-center tw-rounded-4xl"
                                href="contact"><?php echo lang('Hemen İletişime Geçin', 'Contact Us Today'); ?> <i
                                    class="ph ph-arrow-up-right"></i></a>
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
            <img src="assets/images/cta/cta-bg-shape2.png" alt="shape">
        </div>
    </section>

    <!-- Instagram Section -->
    <section class="instagram-area pb-140 pt-140">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="text-center tw-mb-6">
                        <h6 class="instagram-title tw-text-2xl fw-normal text-capitalize">...Instagram...</h6>
                    </div>
                </div>
            </div>
            <div class="row row-cols-xl-6 row-cols-md-3 row-cols-sm-3 row-cols-1">
                <?php
                $insta = $pdo->query("SELECT * FROM instagram_feed WHERE status = 1 ORDER BY sort_order ASC, id DESC LIMIT 6")->fetchAll();
                if (empty($insta)) {
                    // Keep static images
                    for ($i = 1; $i <= 6; $i++) {
                        ?>
                        <div class="col">
                            <div class="instagram-wrapper" data-aos="fade-up" data-aos-duration="1000"
                                data-aos-delay="<?php echo $i * 100; ?>">
                                <div class="instagram-thumb position-relative z-1 overflow-hidden">
                                    <img class="tw-rounded-lg w-100"
                                        src="assets/images/instagram/instagram-thumb<?php echo $i < 6 ? $i : 5; ?>.jpg"
                                        alt="thumb">
                                    <div class="instagram-btn position-absolute z-1">
                                        <a href="#"><span><img src="assets/images/icon/instagram.svg"
                                                    alt="instagram"></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    foreach ($insta as $i => $item):
                        ?>
                        <div class="col">
                            <div class="instagram-wrapper" data-aos="fade-up" data-aos-duration="1000"
                                data-aos-delay="<?php echo ($i + 1) * 100; ?>">
                                <div class="instagram-thumb position-relative z-1 overflow-hidden">
                                    <img class="tw-rounded-lg w-100" src="uploads/instagram/<?php echo $item['image']; ?>"
                                        alt="thumb" style="aspect-ratio: 1/1; object-fit: cover;">
                                    <div class="instagram-btn position-absolute z-1">
                                        <a href="<?php echo $item['link']; ?>" target="_blank"><span><img
                                                    src="assets/images/icon/instagram.svg" alt="instagram"></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;
                } ?>
            </div>
        </div>
    </section>

</div>

<style>
    .tour-image-wrapper .hover-img {
        opacity: 0;
        transition: opacity 0.5s ease;
    }

    .tour-image-wrapper:hover .hover-img {
        opacity: 1;
    }
</style>

<?php include 'footer.php'; ?>

<script>
    $(document).ready(function () {
        // Refresh ScrollTrigger when FAQs are toggled to prevent "jumping"
        $('.accordion-button').on('click', function () {
            setTimeout(function () {
                if(typeof ScrollTrigger !== 'undefined') {
                    ScrollTrigger.refresh();
                }
            }, 400); // Wait for Bootstrap collapse animation to finish
        });
    });
</script>