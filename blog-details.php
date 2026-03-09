<?php include 'header.php';
$slug = clean($_GET['s'] ?? '');
$stmt = $pdo->prepare("SELECT * FROM blogs WHERE (slug_tr = ? OR slug_en = ?) AND status = 1");
$stmt->execute([$slug, $slug]);
$blog = $stmt->fetch();

if (!$blog)
    redirect('blog');

// Recent blogs for sidebar
$recent_blogs = $pdo->query("SELECT * FROM blogs WHERE status = 1 AND id != " . $blog['id'] . " ORDER BY created_at DESC LIMIT 3")->fetchAll();
?>

<!-- Breadcrumb -->
<section class="breadcrumb-area background-img" data-background-image="uploads/blog/<?php echo $blog['image']; ?>">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div>
                    <h2 class="breadcrumb-title text-center tw-mb-6 char-animation">
                        <?php echo lang($blog['title_tr'], $blog['title_en']); ?>
                    </h2>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="blog-details-area pt-140 pb-140">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-xl-8">
                <div class="tw-mb-7">
                    <div class="tw-mb-10">
                        <img class="tw-rounded-xl w-100" src="uploads/blog/<?php echo $blog['image']; ?>" alt="thumb">
                    </div>
                    <div class="tw-mb-4 d-flex align-items-center tw-gap-205 flex-wrap">
                        <div class="d-flex align-items-center tw-gap-2">
                            <span class="text-main-600 tw-text-lg">
                                <i class="ph ph-user"></i>
                            </span>
                            <span class="text-neutral-600 tw-text-sm"><?php echo $settings['title']; ?></span>
                        </div>
                        <span class="tw-w-205 border border-main-600"></span>
                        <div class="d-flex align-items-center tw-gap-2">
                            <span class="text-main-600 tw-text-lg">
                                <i class="ph ph-calendar"></i>
                            </span>
                            <span
                                class="text-neutral-600 tw-text-sm"><?php echo date('d.m.Y', strtotime($blog['created_at'])); ?></span>
                        </div>
                        <span class="tw-w-205 border border-main-600"></span>
                        <div class="d-flex align-items-center tw-gap-2">
                            <span class="text-main-600 tw-text-lg">
                                <i class="ph ph-clock"></i>
                            </span>
                            <span class="text-neutral-600 tw-text-sm">3 min Read</span>
                        </div>
                    </div>
                    <div class="tw-mb-10">
                        <h3 class="tw-mb-6"><?php echo lang($blog['title_tr'], $blog['title_en']); ?></h3>
                        <div class="blog-text fw-medium">
                            <?php echo lang($blog['content_tr'], $blog['content_en']); ?>
                        </div>
                    </div>

                    <?php
                    $gallery_stmt = $pdo->prepare("SELECT * FROM blog_gallery WHERE blog_id = ?");
                    $gallery_stmt->execute([$blog['id']]);
                    $gallery_images = $gallery_stmt->fetchAll();
                    if (count($gallery_images) > 0):
                        ?>
                        <div class="row gy-4 tw-mt-10">
                            <?php foreach ($gallery_images as $img): ?>
                                <div class="col-sm-6">
                                    <div class="gallery-item">
                                        <img src="uploads/blog/<?php echo $img['image']; ?>" alt="gallery"
                                            class="tw-rounded-lg w-100 h-100 object-fit-cover"
                                            style="height: 300px !important;">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-xl-4 col-lg-6 col-md-8">
                <div class="sidebar-sticky">
                    <!-- Author Widget -->
                    <div class="bg-white tw-px-8 text-center tw-py-17 tw-mb-7 tw-rounded-xl" data-aos="fade-up">
                        <div class="tw-w-95-px tw-h-95-px rounded-circle d-inline-flex overflow-hidden">
                            <img src="assets/images/logo/logo.png" alt="logo"
                                class="w-100 h-100 object-fit-contain p-2">
                        </div>
                        <h6 class="tw-text-xl tw-mb-1 tw-mt-4"><?php echo $settings['title']; ?></h6>
                        <span
                            class="text-neutral-1000 tw-text-sm"><?php echo lang('Lüks Turizm Acentesi', 'Luxury Tourism Agency'); ?></span>
                        <p class="tw-mt-5 text-neutral-1000">
                            <?php echo lang('Dünyanın en güzel noktalarını lüks ve konforla keşfedin.', 'Explore the world\'s most beautiful destinations with luxury and comfort.'); ?>
                        </p>
                        <ul class="d-flex align-items-center tw-gap-3 justify-content-center tw-mt-6">
                            <li><a href="<?php echo $settings['facebook']; ?>"
                                    class="tw-w-11 tw-h-11 border border-neutral-200 text-main-600 tw-text-xl d-flex justify-content-center align-items-center bg-white hover-bg-main-600 hover-text-white tw-duration-200"><i
                                        class="ph ph-facebook-logo"></i></a></li>
                            <li><a href="<?php echo $settings['twitter']; ?>"
                                    class="tw-w-11 tw-h-11 border border-neutral-200 text-main-600 tw-text-xl d-flex justify-content-center align-items-center bg-white hover-bg-main-600 hover-text-white tw-duration-200"><i
                                        class="ph ph-x-logo"></i></a></li>
                            <li><a href="<?php echo $settings['instagram']; ?>"
                                    class="tw-w-11 tw-h-11 border border-neutral-200 text-main-600 tw-text-xl d-flex justify-content-center align-items-center bg-white hover-bg-main-600 hover-text-white tw-duration-200"><i
                                        class="ph ph-instagram-logo"></i></a></li>
                        </ul>
                    </div>

                    <!-- Search Widget -->
                    <div class="search bg-white tw-pt-9 tw-pb-7 tw-px-11 tw-mb-8 tw-rounded-xl" data-aos="fade-up">
                        <h6 class="tw-text-505 text-capitalize tw-mb-3 border-start border-3 border-main-600 tw-ps-2">
                            <?php echo lang('Arama', 'Search'); ?>
                        </h6>
                        <form action="blog" class="position-relative">
                            <input type="text" name="q"
                                class="tw-ps-4 tw-pe-12 tw-py-4 bg-neutral-100 tw-rounded-xl focus-outline-0 w-100 border border-white focus-border-main-600 tw-duration-300"
                                placeholder="<?php echo lang('Ara...', 'Search...'); ?>">
                            <button type="submit"
                                class="position-absolute top-50 tw--translate-y-50 tw-end-0 text-main-600 tw-text-xl d-flex tw-me-5">
                                <i class="ph-bold ph-magnifying-glass"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Recent News Widget -->
                    <?php if (count($recent_blogs) > 0): ?>
                        <div class="recent bg-white tw-pt-9 tw-pb-7 tw-px-8 tw-mb-8 tw-rounded-xl" data-aos="fade-up">
                            <h6 class="tw-text-505 text-capitalize tw-mb-5 border-start border-3 border-main-600 tw-ps-2">
                                <?php echo lang('Son Yazılar', 'Recent News'); ?>
                            </h6>
                            <?php foreach ($recent_blogs as $rb): ?>
                                <div class="d-flex align-items-center tw-gap-4 tw-mb-7">
                                    <div class="flex-shrink-0" style="width: 80px; height: 80px;">
                                        <a href="blog/<?php echo lang($rb['slug_tr'], $rb['slug_en']); ?>"
                                            class="tw-rounded-md overflow-hidden d-block h-100">
                                            <img src="uploads/blog/<?php echo $rb['image']; ?>" alt="thumb"
                                                class="w-100 h-100 object-fit-cover hover-scale-2 tw-duration-500">
                                        </a>
                                    </div>
                                    <div>
                                        <div class="tw-mb-1 text-main-600 tw-text-xs">
                                            <i class="ph-fill ph-star"></i>
                                            <i class="ph-fill ph-star"></i>
                                            <i class="ph-fill ph-star"></i>
                                            <i class="ph-fill ph-star"></i>
                                            <i class="ph-fill ph-star"></i>
                                        </div>
                                        <h6 class="tw-mb-1 tw-text-sm"><a
                                                href="blog/<?php echo lang($rb['slug_tr'], $rb['slug_en']); ?>"><?php echo lang($rb['title_tr'], $rb['title_en']); ?></a>
                                        </h6>
                                        <p class="mb-0 tw-text-xs fw-medium text-neutral-600">
                                            <?php echo date('d.m.Y', strtotime($rb['created_at'])); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Popular Tags -->
                    <div class="sidebar-tag bg-white tw-rounded-xl tw-pt-8 tw-pb-8 tw-px-10" data-aos="fade-up">
                        <h6 class="tw-text-505 text-capitalize tw-mb-5 border-start border-3 border-main-600 tw-ps-2">
                            <?php echo lang('Etiketler', 'Popular Tags'); ?>
                        </h6>
                        <div class="d-flex align-items-center flex-wrap tw-gap-3">
                            <a href="blog"
                                class="tw-px-4 tw-py-2 bg-white border border-neutral-100 hover-bg-main-600 tw-rounded-lg hover-text-white text-main-600 tw-text-xs fw-medium">Travel</a>
                            <a href="blog"
                                class="tw-px-4 tw-py-2 bg-white border border-neutral-100 hover-bg-main-600 tw-rounded-lg hover-text-white text-main-600 tw-text-xs fw-medium">Adventure</a>
                            <a href="blog"
                                class="tw-px-4 tw-py-2 bg-white border border-neutral-100 hover-bg-main-600 tw-rounded-lg hover-text-white text-main-600 tw-text-xs fw-medium">Luxury</a>
                            <a href="blog"
                                class="tw-px-4 tw-py-2 bg-white border border-neutral-100 hover-bg-main-600 tw-rounded-lg hover-text-white text-main-600 tw-text-xs fw-medium">Vacation</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>