<?php include 'header.php';
$slug = clean($_GET['s'] ?? '');
$now = date('Y-m-d H:i:s');
$stmt = $pdo->prepare("SELECT * FROM tours WHERE (slug_tr = ? OR slug_en = ?) AND status = 1 AND (end_date >= ? OR end_date IS NULL)");
$stmt->execute([$slug, $slug, $now]);
$tour = $stmt->fetch();

if (!$tour)
    redirect('tours');
?>

<!-- ==================== Breadcrumb Start Here ==================== -->
<section class="breadcrumb-area background-img"
    data-background-image="uploads/tours/<?php echo !empty($tour['banner_image']) ? $tour['banner_image'] : $tour['main_image']; ?>">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div>
                    <h2 class="breadcrumb-title text-center tw-mb-6 char-animation">
                        <?php echo lang($tour['title_tr'], $tour['title_en']); ?>
                    </h2>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ==================== Breadcrumb End Here ==================== -->

<section class="page pt-140">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div
                    class="package-details-top d-flex justify-content-between align-items-end tw-pb-6 tw-mb-15 flex-wrap row-gap-3">
                    <div data-aos-duration="1000" data-aos-delay="200">
                        <div class="d-flex align-items-center tw-gap-5 tw-mb-5">
                            <?php if ($tour['is_featured']): ?>
                                <div>
                                    <span
                                        class="bg-main-two-600 fw-medium tw-pt-1 tw-pb-2 tw-px-5 tw-rounded-3xl"><?php echo lang('Öne Çıkan', 'Featured'); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="tw-mb-3">
                            <h2 class="tw-text-13 char-animation">
                                <?php echo lang($tour['title_tr'], $tour['title_en']); ?>
                            </h2>
                        </div>
                        <div>
                            <ul class="d-flex tw-gap-6 flex-wrap row-gap-3">
                                <li class="d-flex align-items-center tw-gap-2"><span><img
                                            src="assets/images/icon/package-details-top-icon1.svg" alt="clock"></span>
                                    <?php echo lang($tour['duration_tr'], $tour['duration_en']); ?></li>
                                <?php if ($tour['max_guests']): ?>
                                    <li class="d-flex align-items-center tw-gap-2"><span><img
                                                src="assets/images/icon/package-details-top-icon2.svg" alt="clock"></span>
                                        <?php echo lang('Maks Misafir:', 'Max Guests:'); ?>
                                        <?php echo $tour['max_guests']; ?>
                                    </li>
                                <?php endif; ?>
                                <?php if (!empty($tour['start_date'])): ?>
                                    <li class="d-flex align-items-center tw-gap-2"><span><i
                                                class="ph ph-calendar-blank text-main-600 tw-text-xl"></i></span>
                                        <?php echo lang('Tur Tarihi:', 'Tour Date:'); ?>
                                        <?php echo date('d.m.Y', strtotime($tour['start_date'])); ?>
                                    </li>
                                <?php endif; ?>
                                <li class="d-flex align-items-center tw-gap-2"><span><img
                                            src="assets/images/icon/package-details-top-icon3.svg" alt="clock"></span>
                                    <?php echo lang($tour['location_tr'], $tour['location_en']); ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$gallery = $pdo->prepare("SELECT * FROM tour_gallery WHERE tour_id = ?");
$gallery->execute([$tour['id']]);
$images = $gallery->fetchAll();
if (count($images) > 0):
    ?>
    <div class="package-details-area tw-pb-22">
        <div class="container-fluid gx-5">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="package-details-slide position-relative z-index-1" data-aos-duration="1000"
                        data-aos-delay="200">
                        <div class="package-details-active swiper-container">
                            <div class="package-details-swiper-wrapper swiper-wrapper">
                                <?php foreach ($images as $img): ?>
                                    <div class="position-relative z-index-1 swiper-slide">
                                        <div>
                                            <img class="tw-rounded-lg w-100" style="height: 400px; object-fit: cover;"
                                                src="uploads/tours/<?php echo $img['image']; ?>" alt="gallery">
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="package-details-arrow-box">
                            <button class="slider-prev"><i class="ph ph-arrow-left"></i></button>
                            <button class="slider-next"><i class="ph ph-arrow-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
$videos = $pdo->prepare("SELECT * FROM tour_videos WHERE tour_id = ?");
$videos->execute([$tour['id']]);
$vids = $videos->fetchAll();
if (count($vids) > 0):
    ?>
    <div class="package-details-area tw-pb-22">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="tw-text-10 tw-mb-8"><?php echo lang('Tur Videoları', 'Tour Videos'); ?></h2>
                </div>
                <?php foreach ($vids as $v):
                    // Convert watch URL to embed URL
                    $v_url = $v['video_url'];
                    if (strpos($v_url, 'watch?v=') !== false) {
                        $v_url = str_replace('watch?v=', 'embed/', $v_url);
                    } else if (strpos($v_url, 'youtu.be/') !== false) {
                        $v_url = str_replace('youtu.be/', 'youtube.com/embed/', $v_url);
                    }
                    ?>
                    <div class="col-md-6 mb-4">
                        <div class="ratio ratio-16x9">
                            <iframe src="<?php echo $v_url; ?>" title="YouTube video" allowfullscreen></iframe>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<section>
    <div class="container">
        <div class="row">
            <div class="col-xl-8">
                <div class="tw-mb-7">

                    <div class="tw-mb-14" data-aos-duration="1000" data-aos-delay="200">
                        <h2 class="tw-text-10 tw-mb-4"><?php echo lang('Açıklama:', 'Description:'); ?></h2>
                        <div class="tw-text-lg tw-w-845-px">
                            <?php echo lang($tour['content_tr'], $tour['content_en']); ?>
                        </div>
                    </div>

                    <?php if (!empty($tour['advance_facilities_tr']) || !empty($tour['advance_facilities_en'])): ?>
                        <div class="tw-mb-14" data-aos-duration="1000" data-aos-delay="300">
                            <h2 class="tw-text-10 tw-mb-4"><?php echo lang('Gelişmiş Tesisler', 'Advance Facilities'); ?>
                            </h2>
                            <div class="tw-text-lg tw-w-845-px">
                                <?php echo lang($tour['advance_facilities_tr'], $tour['advance_facilities_en']); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($tour['expect_desc_tr']) || !empty($tour['expect_desc_en'])): ?>
                        <div class="tw-mb-10" data-aos-duration="1000" data-aos-delay="300">
                            <h2 class="tw-text-10 tw-mb-4"><?php echo lang('Sizi Neler Bekliyor', 'What to Expect'); ?></h2>
                            <div class="tw-text-lg tw-w-845-px">
                                <?php echo lang($tour['expect_desc_tr'], $tour['expect_desc_en']); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php
                    $expects = $pdo->query("SELECT * FROM tour_expect_list WHERE tour_id = " . $tour['id'])->fetchAll();
                    if (count($expects) > 0):
                        ?>
                        <div class="destination-details-list package-details-list tw-mb-10" data-aos-duration="1000"
                            data-aos-delay="300">
                            <ul>
                                <?php foreach ($expects as $e): ?>
                                    <li
                                        class="font-heading fw-bold text-main-600 text-capitalize tw-text-lg tw-mb-5 tw-ps-2 tw-ms-5">
                                        <?php echo lang($e['text_tr'], $e['text_en']); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Times -->
                    <div class="tw-mb-26 tw-w-810-px" data-aos-duration="1000" data-aos-delay="300">
                        <h2 class="tw-text-10 tw-mb-10"><?php echo lang('Tur Bilgileri', 'Tour Information'); ?></h2>
                        <div>
                            <?php if (!empty($tour['departure_location_tr'])): ?>
                                <div
                                    class="package-details-schedule d-flex justify-content-between tw-pb-6 tw-mb-6 flex-wrap row-gap-3">
                                    <span
                                        class="font-heading text-main-600 tw-text-lg fw-bold"><?php echo lang('Kalkış/Dönüş Yeri', 'Departure/Return Location'); ?></span>
                                    <span class="font-heading tw-text-lg fw-medium"
                                        style="color: #4B5563;"><?php echo lang($tour['departure_location_tr'], $tour['departure_location_en']); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($tour['departure_time'])): ?>
                                <div
                                    class="package-details-schedule d-flex justify-content-between tw-pb-6 tw-mb-6 flex-wrap row-gap-3">
                                    <span
                                        class="font-heading text-main-600 tw-text-lg fw-bold"><?php echo lang('Kalkış Saati', 'Departure Time'); ?></span>
                                    <span class="font-heading tw-text-lg fw-medium"
                                        style="color: #4B5563;"><?php echo $tour['departure_time']; ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($tour['return_time'])): ?>
                                <div
                                    class="package-details-schedule d-flex justify-content-between tw-pb-6 tw-mb-6 flex-wrap row-gap-3">
                                    <span
                                        class="font-heading text-main-600 tw-text-lg fw-bold"><?php echo lang('Dönüş Saati', 'Return Time'); ?></span>
                                    <span class="font-heading tw-text-lg fw-medium"
                                        style="color: #4B5563;"><?php echo $tour['return_time']; ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php
                    $includes = $pdo->query("SELECT * FROM tour_includes WHERE tour_id = " . $tour['id'])->fetchAll();
                    if (count($includes) > 0):
                        ?>
                        <div class="package-details-included tw-mb-26" data-aos-duration="1000" data-aos-delay="300">
                            <h2 class="tw-text-10 tw-mb-8">
                                <?php echo lang('Dahil Olanlar / Hariç Olanlar', 'Included / Exclude'); ?>
                            </h2>
                            <div class="clearfix">
                                <ul class="d-flex flex-wrap">
                                    <?php foreach ($includes as $inc): ?>
                                        <li class="w-50 tw-text-lg fw-normal tw-mb-4 d-inline-flex align-items-center tw-gap-3">
                                            <span>
                                                <?php if ($inc['is_included']): ?>
                                                    <img src="assets/images/icon/package-details-Included.svg" alt="included">
                                                <?php else: ?>
                                                    <i class="ph ph-x text-danger tw-text-xl"></i>
                                                <?php endif; ?>
                                            </span>
                                            <?php echo lang($inc['text_tr'], $inc['text_en']); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php
                    $amenities = $pdo->query("SELECT * FROM tour_amenities WHERE tour_id = " . $tour['id'])->fetchAll();
                    if (count($amenities) > 0):
                        ?>
                        <div class="package-details-amenities tw-mb-26" data-aos-duration="1000" data-aos-delay="300">
                            <h2 class="tw-text-10 tw-mb-8"><?php echo lang('Tur Olanakları', 'Tour Amenities'); ?></h2>
                            <div class="clearfix">
                                <ul class="d-flex flex-wrap row-gap-2">
                                    <?php foreach ($amenities as $amn): ?>
                                        <li
                                            class="w-25 tw-text-base fw-medium tw-mb-6 d-inline-flex align-items-center tw-gap-3">
                                            <span><img src="assets/images/icon/package-details-amenities.svg"
                                                    alt="amenity"></span>
                                            <?php echo lang($amn['text_tr'], $amn['text_en']); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php
                    $itineraries = $pdo->query("SELECT * FROM tour_itineraries WHERE tour_id = " . $tour['id'] . " ORDER BY day_number ASC")->fetchAll();
                    if (count($itineraries) > 0):
                        ?>
                        <div class="tw-mb-16" data-aos-duration="1000" data-aos-delay="300">
                            <h2 class="tw-text-10 tw-mb-8"><?php echo lang('Tur Planı :', 'Tour Plan :'); ?></h2>

                            <?php foreach ($itineraries as $itn): ?>
                                <div class="package-details-rules d-flex tw-gap-12 position-relative z-1 mb-4">
                                    <div>
                                        <span
                                            class="tw-w-25 tw-h-24 lh-1 d-inline-flex align-items-center justify-content-center bg-main-600 tw-rounded-lg text-white tw-text-6 fw-bold">
                                            <?php echo sprintf('%02d', $itn['day_number']); ?>
                                        </span>
                                    </div>
                                    <div>
                                        <h6 class="tw-text-505 tw-mb-4"><?php echo lang($itn['title_tr'], $itn['title_en']); ?>
                                        </h6>
                                        <p class="tw-mb-4"><?php echo lang($itn['content_tr'], $itn['content_en']); ?></p>

                                        <?php
                                        $items = $pdo->query("SELECT * FROM tour_itinerary_items WHERE itinerary_id = " . $itn['id'])->fetchAll();
                                        if (count($items) > 0):
                                            ?>
                                            <div>
                                                <ul class="d-flex flex-column">
                                                    <?php foreach ($items as $itm): ?>
                                                        <li
                                                            class="float-start tw-text-base fw-medium tw-mb-3 d-inline-flex align-items-center tw-gap-3">
                                                            <span><img src="assets/images/icon/package-details-engelberg-check.svg"
                                                                    alt="check"></span>
                                                            <?php echo lang($itm['text_tr'], $itm['text_en']); ?>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($tour['map_iframe'])): ?>
                        <div class="tw-mb-18" data-aos-duration="1000" data-aos-delay="300">
                            <h2 class="tw-text-10 tw-mb-8"><?php echo lang('Tur Haritası :', 'Tour Maps :'); ?></h2>
                            <div class="package-details-maps">
                                <?php echo $tour['map_iframe']; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php
                    $faqs = $pdo->query("SELECT * FROM tour_faqs WHERE tour_id = " . $tour['id'])->fetchAll();
                    if (count($faqs) > 0):
                        ?>
                        <div class="package-details-faq tw-mb-26" data-aos-duration="1000" data-aos-delay="300">
                            <h2 class="tw-text-10 tw-mb-8">
                                <?php echo lang('Sıkça Sorulan Sorular:', 'Frequently ask Question:'); ?>
                            </h2>
                            <div class="faq-wrapper">
                                <div class="accordion" id="general_faqaccordion">
                                    <?php foreach ($faqs as $i => $faq): ?>
                                        <div class="accordion-item faq-accordion-item">
                                            <h2 class="accordion-header" id="faq_header_<?php echo $i; ?>">
                                                <button
                                                    class="accordion-button faq-accordion-button <?php echo $i !== 0 ? 'collapsed' : ''; ?>"
                                                    type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#faq_collapse_<?php echo $i; ?>"
                                                    aria-expanded="<?php echo $i === 0 ? 'true' : 'false'; ?>"
                                                    aria-controls="faq_collapse_<?php echo $i; ?>">
                                                    <?php echo lang($faq['question_tr'], $faq['question_en']); ?>
                                                </button>
                                            </h2>
                                            <div id="faq_collapse_<?php echo $i; ?>"
                                                class="accordion-collapse collapse <?php echo $i === 0 ? 'show' : ''; ?>"
                                                aria-labelledby="faq_header_<?php echo $i; ?>"
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
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="package-details-sidebar row-gap-6 d-flex flex-column">
                    <div class="package-details-sidebar-book tw-p-8 tw-rounded-xl bg-white" data-aos="fade-up"
                        data-aos-duration="1000" data-aos-delay="200">

                        <div class="form-steps-indicator tw-mb-8 d-flex align-items-center tw-gap-4">
                            <div class="step-item active" id="step1Indicator">
                                <div class="step-number">1</div>
                                <div class="step-label"><?php echo lang('İletişim Bilgileri', 'Contact Info'); ?></div>
                            </div>
                            <div class="step-line"></div>
                            <div class="step-item" id="step2Indicator">
                                <div class="step-number">2</div>
                                <div class="step-label"><?php echo lang('Talep Detayları', 'Request Details'); ?></div>
                            </div>
                        </div>

                        <div id="formResult" class="tw-mb-4 d-none"></div>

                        <form id="tourContactForm" action="send_message.php" method="POST">
                            <input type="hidden" name="subject" value="Tour Inquiry: <?php echo $tour['title_tr']; ?>">

                            <!-- Step 1 -->
                            <div id="formStep1">
                                <div class="tw-mb-5">
                                    <label
                                        class="form-label fw-bold tw-mb-2"><?php echo lang('Adınız *', 'Name *'); ?></label>
                                    <input type="text" name="full_name"
                                        class="form-control bg-transparent tw-h-14 border-neutral-200 focus-border-main-600 tw-rounded-xl"
                                        placeholder="<?php echo lang('Adınız', 'Name'); ?>" required>
                                </div>
                                <div class="tw-mb-5">
                                    <label
                                        class="form-label fw-bold tw-mb-2"><?php echo lang('Firma Adı', 'Company Name'); ?></label>
                                    <input type="text" name="company_name"
                                        class="form-control bg-transparent tw-h-14 border-neutral-200 focus-border-main-600 tw-rounded-xl"
                                        placeholder="<?php echo lang('Firma Adı', 'Company Name'); ?>">
                                </div>
                                <div class="tw-mb-5">
                                    <label
                                        class="form-label fw-bold tw-mb-2"><?php echo lang('Telefon *', 'Phone *'); ?></label>
                                    <div class="phone-input-wrapper">
                                        <input type="tel" id="tour_phone" name="phone"
                                            class="form-control bg-transparent tw-h-14 border-neutral-200 focus-border-main-600 tw-rounded-xl w-100"
                                            placeholder="<?php echo lang('Telefon', 'Phone'); ?>" required>
                                    </div>
                                </div>
                                <div class="tw-mb-8">
                                    <label
                                        class="form-label fw-bold tw-mb-2"><?php echo lang('E-Posta *', 'E-Mail *'); ?></label>
                                    <input type="email" name="email"
                                        class="form-control bg-transparent tw-h-14 border-neutral-200 focus-border-main-600 tw-rounded-xl"
                                        placeholder="<?php echo lang('E-posta', 'E-Mail'); ?>" required>
                                </div>
                                <button type="button" id="nextStepBtn"
                                    class="btn w-100 text-uppercase fw-bold text-main-600 bg-main-two-600 tw-py-5 tw-rounded-4xl d-flex justify-content-center align-items-center">
                                    <?php echo lang('İleri', 'Next'); ?>
                                </button>
                            </div>

                            <!-- Step 2 -->
                            <div id="formStep2" class="d-none">
                                <div class="tw-mb-8">
                                    <label
                                        class="form-label fw-bold tw-mb-2"><?php echo lang('Mesajınız', 'Your Message'); ?></label>
                                    <textarea name="message"
                                        class="form-control bg-transparent border-neutral-200 focus-border-main-600 tw-rounded-xl"
                                        rows="6"
                                        placeholder="<?php echo lang('Nasıl yardımcı olabiliriz?', 'How can we help?'); ?>"></textarea>
                                </div>
                                <div class="d-flex tw-gap-3">
                                    <button type="button" id="prevStepBtn"
                                        class="btn w-50 text-uppercase fw-bold border-neutral-200 tw-py-5 tw-rounded-4xl d-flex justify-content-center align-items-center">
                                        <?php echo lang('Geri', 'Back'); ?>
                                    </button>
                                    <button type="submit"
                                        class="btn w-50 text-uppercase fw-bold text-main-600 bg-main-two-600 tw-py-5 tw-rounded-4xl d-flex justify-content-center align-items-center tw-gap-2">
                                        <?php echo lang('Gönder', 'Send'); ?> <i class="ph ph-arrow-up-right"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
    .form-steps-indicator {
        position: relative;
    }

    .step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        z-index: 1;
        text-align: center;
    }

    .step-number {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 2px solid #D1D5DB;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin-bottom: 8px;
        color: #9CA3AF;
        transition: all 0.3s;
    }

    .step-label {
        font-size: 12px;
        font-weight: 600;
        color: #9CA3AF;
        transition: all 0.3s;
    }

    .step-item.active .step-number {
        border-color: #A855F7;
        /* Using a vibrant color for active step if main is too dark */
        color: #A855F7;
    }

    .step-item.active .step-label {
        color: #A855F7;
    }

    /* Adjusted based on theme main color if possible */
    .step-item.active .step-number {
        border-color: var(--main-600);
        color: var(--main-600);
    }

    .step-item.active .step-label {
        color: var(--main-600);
    }

    .step-line {
        position: absolute;
        top: 16px;
        left: 25%;
        right: 25%;
        height: 1px;
        background: #D1D5DB;
        z-index: 0;
    }

    .phone-input-wrapper .iti {
        width: 100%;
    }

    .iti__flag-container {
        border-radius: 8px 0 0 8px;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: var(--main-600) !important;
    }
</style>

<!-- Styles & Scripts for intl-tel-input -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.8/build/css/intlTelInput.css">
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.8/build/js/intlTelInput.min.js"></script>

<?php include 'footer.php'; ?>
<script>
    $(document).ready(function () {
        // Initialize Phone Input
        const phoneInput = document.querySelector("#tour_phone");
        const iti = window.intlTelInput(phoneInput, {
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.8/build/js/utils.js",
            initialCountry: "tr",
            separateDialCode: true,
            preferredCountries: ["tr", "us", "gb", "de"]
        });

        // Step Navigation
        $('#nextStepBtn').on('click', function () {
            const form = document.querySelector('#tourContactForm');
            if(form.full_name.checkValidity() && form.email.checkValidity() && form.phone.checkValidity()) {
                $('#formStep1').addClass('d-none');
                $('#formStep2').removeClass('d-none');
                $('#step1Indicator').removeClass('active');
                $('#step2Indicator').addClass('active');
            } else {
                form.reportValidity();
            }
        });

        $('#prevStepBtn').on('click', function () {
            $('#formStep2').addClass('d-none');
            $('#formStep1').removeClass('d-none');
            $('#step2Indicator').removeClass('active');
            $('#step1Indicator').addClass('active');
        });

        $('#tourContactForm').on('submit', function (e) {
            e.preventDefault();
            var form = $(this);
            var resultDiv = $('#formResult');
            var submitBtn = form.find('button[type="submit"]');

            // Get full phone number with dial code
            const fullPhoneNumber = iti.getNumber();

            submitBtn.prop('disabled', true).html('<i class="ph ph-spinner-gap tw-animate-spin"></i> <?php echo lang("Gönderiliyor...", "Sending..."); ?>');

            let formData = form.serializeArray();
            // Update phone field in serialized data
            formData.forEach(field => {
                if(field.name === 'phone') field.value = fullPhoneNumber;
            });

            $.ajax({
                url: 'send_message.php',
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if(response.status === 'success') {
                        resultDiv.removeClass('d-none alert-danger').addClass('alert alert-success').html(response.message);
                        form[0].reset();
                        // Reset steps
                        $('#formStep2').addClass('d-none');
                        $('#formStep1').removeClass('d-none');
                        $('#step2Indicator').removeClass('active');
                        $('#step1Indicator').addClass('active');
                    } else {
                        resultDiv.removeClass('d-none alert-success').addClass('alert alert-danger').html(response.message);
                    }
                },
                error: function () {
                    resultDiv.removeClass('d-none alert-success').addClass('alert alert-danger').html('<?php echo lang("Bir hata oluştu, lütfen tekrar deneyin.", "An error occurred, please try again."); ?>');
                },
                complete: function () {
                    submitBtn.prop('disabled', false).html('<?php echo lang("Gönder", "Send"); ?> <i class="ph ph-arrow-up-right"></i>');
                    $('html, body').animate({
                        scrollTop: resultDiv.offset().top - 100
                    }, 500);
                }
            });
        });
    });
</script>