<?php include 'header.php'; ?>

<!-- Breadcrumb -->
<?php $banner = !empty($settings['banner_gallery']) ? 'uploads/' . $settings['banner_gallery'] : 'assets/images/banner/breadcrumb-bg.jpg'; ?>
<section class="breadcrumb-area background-img" data-background-image="<?php echo $banner; ?>">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div>
                    <h2 class="breadcrumb-title text-center tw-mb-6">
                        <?php echo lang('Galeri', 'Gallery'); ?>
                    </h2>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="gallary-ip-area pt-140 pb-100">
    <div class="container">
        <?php
        $has_content = false;

        // 1. Get tours that have items in gallery or videos
        $tours = $pdo->query("
            SELECT DISTINCT t.id, t.title_tr, t.title_en 
            FROM tours t
            LEFT JOIN tour_gallery tg ON t.id = tg.tour_id
            LEFT JOIN tour_videos tv ON t.id = tv.tour_id
            WHERE tg.id IS NOT NULL OR tv.id IS NOT NULL
            ORDER BY t.id DESC
        ")->fetchAll();

        foreach ($tours as $tour):
            $tour_id = $tour['id'];
            $tour_title = lang($tour['title_tr'], $tour['title_en']);

            // Fetch items for this tour
            $photos = $pdo->query("SELECT image FROM tour_gallery WHERE tour_id = $tour_id")->fetchAll();
            $videos = $pdo->query("SELECT video_url FROM tour_videos WHERE tour_id = $tour_id")->fetchAll();

            $items = [];
            foreach ($photos as $p) {
                $items[] = ['type' => 'photo', 'file' => $p['image'], 'title' => $tour_title, 'folder' => 'tours'];
            }
            foreach ($videos as $v) {
                $items[] = ['type' => 'video', 'file' => $v['video_url'], 'title' => $tour_title];
            }

            if (empty($items))
                continue;
            $has_content = true;
            ?>
            <div class="tour-gallery-category tw-mb-20">
                <h2 class="tw-text-3xl tw-mb-10 text-uppercase fw-bold border-start border-4 border-main-600 tw-ps-4"
                    data-aos="fade-right">
                    <?php echo $tour_title; ?>
                </h2>
                <div class="row align-items-center masonry lightgallery-container">
                    <?php
                    $grid_classes = ['col-xl-3', 'col-xl-6', 'col-xl-3', 'col-xl-3', 'col-xl-6', 'col-xl-3'];
                    foreach ($items as $i => $item):
                        $class = $grid_classes[$i % 6];
                        $img_class = 'w-100 object-fit-cover tw-rounded-lg';
                        if ($class == 'col-xl-6') {
                            $img_class .= ' gallary-ip-thumb-height';
                        }

                        if ($item['type'] == 'photo') {
                            $target = "uploads/" . ($item['folder'] ?? 'gallery') . "/" . $item['file'];
                            $thumb = $target;
                            $class_link = 'lightgallery';
                        } else {
                            $yt_id = '';
                            if (strpos($item['file'], 'v=') !== false) {
                                $yt_id = explode('v=', $item['file'])[1];
                                if (strpos($yt_id, '&') !== false)
                                    $yt_id = explode('&', $yt_id)[0];
                            } elseif (strpos($item['file'], 'youtu.be/') !== false) {
                                $yt_id = explode('youtu.be/', $item['file'])[1];
                            }
                            $target = "https://www.youtube.com/watch?v=" . $yt_id;
                            $thumb = "https://img.youtube.com/vi/" . $yt_id . "/hqdefault.jpg";
                            $class_link = 'popup-video';
                        }
                        ?>
                        <div class="<?php echo $class; ?> col-lg-6 col-md-6 tw-mb-7" data-aos="fade-up"
                            data-aos-delay="<?php echo ($i % 3) * 100; ?>">
                            <div class="gallary-ip-thumb position-relative z-1 overflow-hidden">
                                <img class="<?php echo $img_class; ?>" src="<?php echo $thumb; ?>" alt="gallery"
                                    style="min-height:280px;">
                                <div class="gallary-ip-button position-absolute start-50 translate-middle">
                                    <a class="<?php echo $class_link; ?> gallary-ip-btn tw-w-25 tw-h-25 lh-1 d-inline-flex align-items-center justify-content-center bg-main-600 rounded-circle text-white"
                                        href="<?php echo $target; ?>">
                                        <span><i class="ph-bold ph-eye tw-text-2xl"></i></span>
                                    </a>
                                </div>
                                <div class="gallary-ip-content position-absolute">
                                    <h4 class="tw-text-2xl tw-mb-2 text-white">
                                        <?php echo $item['title']; ?>
                                    </h4>
                                    <p class="gallary-ip-paragraph fw-medium"><?php echo $settings['title']; ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?php
        // 2. Get general gallery items
        $general_items = $pdo->query("SELECT * FROM gallery WHERE status = 1 ORDER BY id DESC")->fetchAll();
        if (!empty($general_items)):
            $has_content = true;
            ?>
            <div class="tour-gallery-category tw-mb-20">
                <h2 class="tw-text-3xl tw-mb-10 text-uppercase fw-bold border-start border-4 border-main-600 tw-ps-4"
                    data-aos="fade-right">
                    <?php echo lang('Genel Galeri', 'General Gallery'); ?>
                </h2>
                <div class="row align-items-center masonry lightgallery-container">
                    <?php
                    $grid_classes = ['col-xl-3', 'col-xl-6', 'col-xl-3', 'col-xl-3', 'col-xl-6', 'col-xl-3'];
                    foreach ($general_items as $i => $item):
                        $class = $grid_classes[$i % 6];
                        $img_class = 'w-100 object-fit-cover tw-rounded-lg';
                        if ($class == 'col-xl-6') {
                            $img_class .= ' gallary-ip-thumb-height';
                        }

                        if ($item['type'] == 'photo') {
                            $target = "uploads/gallery/" . $item['file'];
                            $thumb = $target;
                            $class_link = 'lightgallery';
                        } else {
                            $yt_id = '';
                            if (strpos($item['file'], 'v=') !== false) {
                                $yt_id = explode('v=', $item['file'])[1];
                                if (strpos($yt_id, '&') !== false)
                                    $yt_id = explode('&', $yt_id)[0];
                            } elseif (strpos($item['file'], 'youtu.be/') !== false) {
                                $yt_id = explode('youtu.be/', $item['file'])[1];
                            }
                            $target = "https://www.youtube.com/watch?v=" . $yt_id;
                            $thumb = "https://img.youtube.com/vi/" . $yt_id . "/hqdefault.jpg";
                            $class_link = 'popup-video';
                        }
                        ?>
                        <div class="<?php echo $class; ?> col-lg-6 col-md-6 tw-mb-7" data-aos="fade-up"
                            data-aos-delay="<?php echo ($i % 3) * 100; ?>">
                            <div class="gallary-ip-thumb position-relative z-1 overflow-hidden">
                                <img class="<?php echo $img_class; ?>" src="<?php echo $thumb; ?>" alt="gallery"
                                    style="min-height:280px;">
                                <div class="gallary-ip-button position-absolute start-50 translate-middle">
                                    <a class="<?php echo $class_link; ?> gallary-ip-btn tw-w-25 tw-h-25 lh-1 d-inline-flex align-items-center justify-content-center bg-main-600 rounded-circle text-white"
                                        href="<?php echo $target; ?>">
                                        <span><i class="ph-bold ph-eye tw-text-2xl"></i></span>
                                    </a>
                                </div>
                                <div class="gallary-ip-content position-absolute">
                                    <h4 class="tw-text-2xl tw-mb-2 text-white">
                                        <?php echo lang($item['title_tr'], $item['title_en']); ?>
                                    </h4>
                                    <p class="gallary-ip-paragraph fw-medium"><?php echo $settings['title']; ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!$has_content): ?>
            <div class="text-center tw-py-20">
                <h3><?php echo lang('Galeri henüz boş.', 'Gallery is currently empty.'); ?></h3>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
    .gallary-ip-thumb-height {
        height: 580px !important;
    }

    .tour-gallery-category:not(:last-child) {
        border-bottom: 1px dashed #eee;
        padding-bottom: 40px;
    }
</style>

<?php include 'footer.php'; ?>