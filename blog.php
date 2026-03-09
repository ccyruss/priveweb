<?php include 'header.php'; ?>

<!-- Breadcrumb -->
<?php $banner = !empty($settings['banner_blog']) ? 'uploads/' . $settings['banner_blog'] : 'assets/images/banner/breadcrumb-bg.jpg'; ?>
<section class="breadcrumb-area background-img" data-background-image="<?php echo $banner; ?>">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div>
                    <h2 class="breadcrumb-title text-center tw-mb-6"> <?php echo lang('Blog', 'Blog'); ?>
                    </h2>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pt-140 pb-140">
    <div class="container">
        <div class="row justify-content-center tw-mb-7">
            <?php
            $q = clean($_GET['q'] ?? '');
            $params = [];
            $sql = "SELECT * FROM blogs WHERE status = 1";
            if (!empty($q)) {
                $sql .= " AND (title_tr LIKE ? OR title_en LIKE ? OR content_tr LIKE ? OR content_en LIKE ?)";
                $params = ["%$q%", "%$q%", "%$q%", "%$q%"];
            }
            $sql .= " ORDER BY id DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $blogs = $stmt->fetchAll();

            if (empty($blogs)) {
                echo '<div class="col-12 text-center"><h4>' . lang('Sonuç bulunamadı.', 'No results found.') . '</h4></div>';
            }

            foreach ($blogs as $blog):
                $slug = lang($blog['slug_tr'], $blog['slug_en']);
                $title = lang($blog['title_tr'], $blog['title_en']);
                $date = date('d.m.Y', strtotime($blog['created_at']));
                ?>
                <div class="col-xl-4 col-md-6">
                    <div class="blog-three-wrap blog-ip-wrap tw-mb-7" data-aos="fade-up" data-aos-duration="1000"
                        data-aos-delay="200">
                        <div class="blog-three-thumb tw-rounded-xl overflow-hidden tw-mb-6">
                            <a href="blog/<?php echo $slug; ?>">
                                <img class="w-100" src="uploads/blog/<?php echo $blog['image']; ?>" alt="thumb"
                                    style="height: 250px; object-fit: cover;">
                            </a>
                        </div>
                        <div class="blog-ip-content tw-p-8">
                            <div class="blog-three-meta tw-mb-6">
                                <ul class="d-flex tw-gap-4">
                                    <li class="d-inline-flex align-items-center tw-gap-2">
                                        <span class="tw-text-xl text-main-600 d-inline-block lh-1"><i
                                                class="ph ph-calendar"></i></span>
                                        <?php echo $date; ?>
                                    </li>
                                    <li class="d-inline-flex align-items-center tw-gap-2">
                                        <span class="tw-text-xl text-main-600 d-inline-block lh-1"><i
                                                class="ph ph-user-circle"></i></span>
                                        <?php echo $settings['title']; ?>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="blog-three-title tw-text-2xl tw-mb-8">
                                    <a class="hover-text-secondary" href="blog/<?php echo $slug; ?>">
                                        <?php echo $title; ?>
                                    </a>
                                </h4>
                                <div class="blog-three-button common-hover-yellow">
                                    <a class="primary-btn bg-main-600 text-white tw-py-4 tw-px-8 fs-15 text-capitalize fw-bold font-heading tw-gap-2 d-inline-flex align-items-center tw-rounded-4xl"
                                        href="blog/<?php echo $slug; ?>">
                                        <?php echo lang('Devamı', 'Continue reading'); ?> <i
                                            class="ph ph-arrow-up-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>