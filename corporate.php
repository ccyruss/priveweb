<?php include 'header.php'; ?>

<?php
$type = isset($_GET['type']) ? $_GET['type'] : 'mission-vision';
$title = '';
$content = '';
$banner_key = 'banner_about';

if ($type == 'mission-vision') {
    $title = lang('Misyon & Vizyon', 'Mission & Vision');
    $content = lang($settings['corporate_content_tr'], $settings['corporate_content_en']);
    $banner_key = 'banner_mission_vision';
} elseif ($type == 'sustainability') {
    $title = lang('Sürdürülebilirlik', 'Sustainability');
    $content = lang($settings['sustainability_content_tr'], $settings['sustainability_content_en']);
    $banner_key = 'banner_sustainability';
} else {
    $title = lang('Kurumsal', 'Corporate');
    $content = lang($settings['corporate_content_tr'], $settings['corporate_content_en']);
    $banner_key = 'banner_about';
}

$banner_img = !empty($settings[$banner_key]) ? 'uploads/' . $settings[$banner_key] : 'assets/images/banner/breadcrumb-bg.jpg';
?>

<!-- Breadcrumb -->
<section class="breadcrumb-area background-img" data-background-image="<?php echo $banner_img; ?>">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div>
                    <h2 class="breadcrumb-title text-center tw-mb-6">
                        <?php echo $title; ?>
                    </h2>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="about-three-area pt-140 pb-140">
    <div class="container">
        <div class="row">
            <div class="col-xl-5">
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
                <div class="about-three-wrapper" data-aos="fade-up">
                    <h2 class="about-three-title tw-text-15 text-invert fw-normal tw-mb-13">
                        <?php echo lang('Prive | ', 'Prive | ') . $title; ?>
                    </h2>
                    <div class="about-three-paragraph tw-text-lg fw-medium text-main-600 tw-mb-9">
                        <?php echo nl2br($content ?? ''); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>