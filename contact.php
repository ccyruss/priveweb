<?php include 'header.php'; ?>

<!-- Breadcrumb -->
<?php $banner = !empty($settings['banner_contact']) ? 'uploads/' . $settings['banner_contact'] : 'assets/images/banner/breadcrumb-bg.jpg'; ?>
<section class="breadcrumb-area background-img" data-background-image="<?php echo $banner; ?>">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div>
                    <h2 class="breadcrumb-title text-center tw-mb-6">
                        <?php echo lang('Bize Ulaşın', 'Contact Us'); ?>
                    </h2>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="contact-area pt-140 pb-140">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="contact-info">
                    <h2 class="tw-text-10 tw-mb-6">
                        <?php echo lang('İletişime Geçin', 'Get in Touch'); ?>
                    </h2>
                    <p class="tw-mb-8">
                        <?php echo lang('Sorularınız için bizimle iletişime geçebilirsiniz.', 'You can contact us for any questions.'); ?>
                    </p>
                    <ul class="tw-gap-4 d-flex flex-column">
                        <li><strong>
                                <?php echo lang('Telefon', 'Phone'); ?>:
                            </strong>
                            <?php echo $settings['phone']; ?>
                        </li>
                        <li><strong>
                                <?php echo lang('E-posta', 'Email'); ?>:
                            </strong>
                            <?php echo $settings['email']; ?>
                        </li>
                        <li><strong>
                                <?php echo lang('Adres', 'Address'); ?>:
                            </strong>
                            <?php echo $settings['address']; ?>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
                <?php if (isset($_SESSION['alert'])): ?>
                    <div class="alert alert-<?php echo $_SESSION['alert']['type']; ?>">
                        <?php echo $_SESSION['alert']['msg'];
                        unset($_SESSION['alert']); ?>
                    </div>
                <?php endif; ?>
                <div id="contactPageResult" class="tw-mb-4 d-none"></div>
                <form id="contactPageForm" action="send_message.php" method="POST"
                    class="contact-form bg-white tw-p-8 tw-rounded-xl">
                    <div class="row row-gap-4">
                        <div class="col-md-6">
                            <input type="text" name="full_name" class="form-control"
                                placeholder="<?php echo lang('Adınız Soyadınız', 'Full Name'); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <input type="email" name="email" class="form-control"
                                placeholder="<?php echo lang('E-posta Adresiniz', 'Email Address'); ?>" required>
                        </div>
                        <div class="col-12">
                            <input type="text" name="subject" class="form-control"
                                placeholder="<?php echo lang('Konu', 'Subject'); ?>">
                        </div>
                        <div class="col-12">
                            <textarea name="message" class="form-control" rows="5"
                                placeholder="<?php echo lang('Mesajınız', 'Message'); ?>" required></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="primary-btn tw-py-4 tw-px-10 tw-rounded-4xl fw-bold">
                                <?php echo lang('Gönder', 'Send'); ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($settings['map_iframe'])): ?>
    <section class="contact-map-area">
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="col-12">
                    <div class="map-wrapper" style="width: 100%; line-height: 0;">
                        <style>
                            .map-wrapper iframe {
                                width: 100% !important;
                                height: 500px !important;
                                border: 0 !important;
                            }
                        </style>
                        <?php echo $settings['map_iframe']; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php include 'footer.php'; ?>
<script>
$(document).ready(function() {
    $('#contactPageForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var resultDiv = $('#contactPageResult');
        var submitBtn = form.find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<?php echo lang("Gönderiliyor...", "Sending..."); ?>');
        $.ajax({
            url: 'send_message.php',
            method: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    resultDiv.removeClass('d-none alert-danger').addClass('alert alert-success').html(response.message);
                    form[0].reset();
                } else {
                    resultDiv.removeClass('d-none alert-success').addClass('alert alert-danger').html(response.message);
                }
            },
            error: function() {
                resultDiv.removeClass('d-none alert-success').addClass('alert alert-danger').html('<?php echo lang("Bir hata oluştu, lütfen tekrar deneyin.", "An error occurred, please try again."); ?>');
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<?php echo lang("Gönder", "Send"); ?>');
                $('html, body').animate({
                    scrollTop: resultDiv.offset().top - 100
                }, 500);
            }
        });
    });
});
</script>