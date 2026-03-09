<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
require_once 'inc/db.php';
require_once 'inc/functions.php';

session_start();
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'tr';
}

$settings = $pdo->query("SELECT * FROM settings WHERE id = 1")->fetch();
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="<?php echo lang($settings['description_tr'], $settings['description_en']); ?>">
    <meta name="keywords" content="<?php echo $settings['keywords']; ?>">
    <meta name="robots" content="INDEX,FOLLOW">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/Prive-Web/">
    <!-- Title -->
    <title><?php echo $settings['title']; ?></title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/logo/favicon.png">
    <!-- Aos -->
    <link rel="stylesheet" href="assets/css/swiper-bundle.css">
    <!-- Aos -->
    <link rel="stylesheet" href="assets/css/slick.css">
    <!-- Aos -->
    <link rel="stylesheet" href="assets/css/aos.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Main css -->
    <link rel="stylesheet" href="assets/css/main.css">
    <style>
        /* Requested Design Changes */
        :root {
            --main-color: #CDAD57;
            --main-two-h: 44;
            --main-two-s: 57%;
            --main-two-l: 57%;
        }

        .section-subtitle {
            color: var(--main-color) !important;
        }

        /* Override Buttons for #CDAD57 */
        .primary-btn.bg-main-two-600 {
            background-color: #CDAD57 !important;
            color: #113A75 !important;
        }

        .primary-btn.bg-main-two-600:hover {
            color: #ffffff !important;
        }

        .primary-btn.bg-main-two-600::before {
            background-color: #113A75 !important;
        }

        .primary-btn.bg-main-600 {
            background-color: #113A75 !important;
            color: #CDAD57 !important;
        }

        .primary-btn.bg-main-600:hover {
            color: #113A75 !important;
            background-color: #CDAD57 !important;
        }

        .primary-btn.bg-main-600::before {
            background-color: #CDAD57 !important;
        }

        .tour-image-wrapper {
            position: relative;
            overflow: hidden;
        }

        .tour-image-wrapper img {
            transition: opacity 0.5s ease-in-out;
        }

        .tour-image-wrapper .hover-img {
            opacity: 0;
            pointer-events: none;
        }

        .tour-image-wrapper:hover .main-img {
            opacity: 0;
        }

        .tour-image-wrapper:hover .hover-img {
            opacity: 1;
        }

        .about-subtitle {
            width: 100%;
        }

        @media (min-width: 768px) {
            .about-subtitle {
                width: 7.5rem;
            }
        }

        .header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 999;
            background: transparent !important;
            transition: background-color 0.3s ease-in-out;
        }

        .header.fixed-header {
            position: fixed !important;
            background-color: #ffffff !important;
            box-shadow: 0 5px 16px rgba(0, 0, 0, 0.1);
        }

        /* Adjust slider height to be full viewport */
        .banner-two-area {
            height: 100vh;
            padding-top: 0 !important;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .banner-two-slider,
        .banner-two-active,
        .swiper-wrapper,
        .swiper-slide {
            height: 100%;
            width: 100%;
        }

        /* Banner/Breadcrumb Overlay */
        .breadcrumb-area {
            position: relative;
            z-index: 1;
        }

        .breadcrumb-area::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            /* Black overlay */
            z-index: -1;
        }

        .breadcrumb-title,
        .breadcrumb-list li,
        .breadcrumb-list li a {
            color: #ffffff !important;
        }

        .header:not(.fixed-header) .nav-menu__link {
            color: #ffffff !important;
        }

        .header:not(.fixed-header) .logo img {
            filter: brightness(0) invert(1);
            /* Make logo white if needed, or user might have a white logo */
        }

        /* Blog Details Styling */
        .blog-text blockquote {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            position: relative;
            margin: 40px 0;
            border-left: 4px solid #CDAD57;
            font-size: 24px;
            font-weight: 700;
            color: #CDAD57;
        }

        .blog-text blockquote::after {
            content: "\f121";
            /* phosphor quote icon if available or just use a quote char */
            position: absolute;
            right: 40px;
            bottom: 40px;
            opacity: 0.1;
            font-size: 60px;
        }

        .blog-text p {
            margin-bottom: 20px;
            line-height: 1.8;
            color: #444;
        }

        .sidebar-sticky {
            position: sticky;
            top: 100px;
        }

        /* Hide vertical lines in footer */
        .footer-wrapper::before,
        .footer-wrapper::after {
            display: none !important;
        }

        /* Increase banner and breadcrumb heights by 50% */
        .banner-area {
            padding-block-start: 270px !important;
            padding-block-end: 292.5px !important;
        }

        .banner-two-area {
            height: 100vh !important;
            min-height: 600px !important;
            padding: 0 !important;
            display: flex !important;
            align-items: center !important;
        }

        .banner-two-bg {
            height: 100% !important;
        }

        .banner-three-area {
            padding: 330px 0 330px !important;
        }

        .breadcrumb-area {
            padding: 390px 0 318px !important;
        }

        /* Homepage Header Fix */
        .header {
            position: absolute !important;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 100;
        }
    </style>
</head>

<body class="bg-neutral-50">

    <!--========= Start Prealoader ==============-->
    <div class="loading-screen" id="loading-screen">
        <span class="bar top-bar"></span>
        <span class="bar down-bar"></span>
        <div class="animation-preloader">
            <div class="line_shape">
                <svg width="1920" height="287" viewBox="0 0 1920 287" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path id="line_path"
                        d="M0 286C235.807 161.804 715.277 -31.6361 948 104C1192.5 246.5 1698.62 102.064 1920 1"
                        stroke="#CEC7BE" stroke-dasharray="4 4" />
                    <g id="paper-plane">
                        <path
                            d="M8.52862 0.643979C8.65472 0.654731 12.5944 5.1037 15.7327 8.66873C16.0616 8.64319 16.3357 8.62234 16.3357 8.62234L16.6797 8.10704L19.1766 7.91312L19.3247 9.82093L16.9109 10.0082C17.5051 10.6843 18.0375 11.2905 18.4682 11.7814C21.1362 11.6535 26.2869 11.419 27.1597 11.4798C28.3465 11.5623 30.0418 12.128 30.0368 12.5126C30.0318 12.8972 28.4581 13.7513 27.5404 14.0673C26.8454 14.3064 21.1236 14.8358 18.3473 15.0812C17.9672 15.7098 17.5181 16.4498 17.0277 17.2557L19.8851 17.0334L20.0333 18.9412L17.5364 19.1351L17.1173 18.6791C17.1173 18.6791 16.5828 18.7209 16.1125 18.7568C13.4463 23.1189 10.1742 28.3838 9.92358 28.3613C9.49623 28.3236 8.49302 27.2688 8.49302 27.2688L9.96254 22.7816C9.52126 22.7861 9.19671 22.7262 9.18859 22.6259C9.17946 22.5123 9.58103 22.3871 10.1098 22.3303L10.8472 20.079C10.5083 20.0669 10.2756 20.0104 10.2686 19.9261C10.2611 19.828 10.5617 19.7202 10.9855 19.6563L12.4419 15.2098L6.2911 15.3333L3.00246 20.403L1.50247 20.2363L2.61746 15.6496L4.02107 15.0837C4.02107 15.0837 2.47224 15.1697 2.47782 14.7758C2.4834 14.3819 3.85476 14.0784 3.85476 14.0784L2.61875 13.8202L0.562775 9.68354L2.55228 8.81392L6.31432 13.4946L12.3097 12.4928L10.3991 8.9086C9.84767 8.93961 9.41072 8.87701 9.4014 8.76046C9.39341 8.6582 9.71898 8.54567 10.1719 8.48324L9.06011 6.39696C9.04924 6.39738 9.03867 6.39894 9.02779 6.39936C8.42442 6.44647 7.92762 6.38376 7.91735 6.25891C7.90894 6.14578 8.30449 6.02093 8.82844 5.96421L6.75475 2.07323C6.75475 2.07323 8.31696 0.625839 8.5282 0.6447L8.52862 0.643979Z"
                            fill="#113A75" />
                    </g>
                </svg>
            </div>
            <div class="txt-loading">
                <span data-text-preloader="P" class="letters-loading">P</span>
                <span data-text-preloader="R" class="letters-loading">R</span>
                <span data-text-preloader="I" class="letters-loading">I</span>
                <span data-text-preloader="V" class="letters-loading">V</span>
                <span data-text-preloader="E" class="letters-loading">E</span>
            </div>
        </div>
    </div>
    <!--========= End Prealoader ==============-->

    <!-- Search Popup Start -->
    <div class="search_popup">
        <div class="container">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="search_wrapper">
                        <div class="search_top d-flex justify-content-between align-items-center">
                            <div class="search_logo">
                                <a href="./">
                                    <img src="assets/images/logo/logo.png" alt="Logo">
                                </a>
                            </div>
                            <div class="search_close">
                                <button type="button" class="search_close_btn">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17 1L1 17" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M1 1L17 17" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="search_form">
                            <form action="tours.php">
                                <div class="search_input">
                                    <input name="q" class="search-input-field" type="text"
                                        placeholder="<?php echo lang('Arama yapın...', 'Search here...'); ?>">
                                    <span class="search-focus-border"></span>
                                    <button type="submit">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9.55 18.1C14.272 18.1 18.1 14.272 18.1 9.55C18.1 4.82797 14.272 1 9.55 1C4.82797 1 1 4.82797 1 9.55C1 14.272 4.82797 18.1 9.55 18.1Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M19.0002 19.0002L17.2002 17.2002" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="search-popup-overlay"></div>
    <!-- Search Popup End-->

    <div class="mouseCursor cursor-outer d-none"></div>
    <div class="mouseCursor cursor-inner">
        <span class="inner-text-1 tw-text-lg fw-bold text-main-600">
            <span>
                <svg width="48" height="47" viewBox="0 0 48 47" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M33.6454 16.1088L15.7477 32.4423L14.3477 30.9082L32.2453 14.5746L33.6454 16.1088Z"
                        fill="#141616" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M31.4786 15.2755C27.8709 18.5679 27.8182 24.431 30.9057 27.8141L31.6057 28.5811L33.1398 27.1811L32.4398 26.414C30.0957 23.8454 30.1506 19.2992 32.8787 16.8096L33.6453 16.1099L32.2453 14.5758L31.4786 15.2755Z"
                        fill="#141616" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M32.8783 16.8088C29.2706 20.1012 23.4271 19.6189 20.3397 16.2358L19.6396 15.4688L21.1738 14.0687L21.8738 14.8358C24.218 17.4045 28.7502 17.7643 31.4783 15.2747L32.2449 14.575L33.645 16.1091L32.8783 16.8088Z"
                        fill="#141616" />
                </svg>
            </span>
            <br>
            About us
        </span>
        <span class="inner-text-2">
            <span>
                <svg width="63" height="63" viewBox="0 0 63 63" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M0.132374 56.2074L6.68723 62.776C6.98521 63.0747 8.773 63.0747 9.07084 62.776L47.2086 25.4537C47.8044 24.8564 48.4004 24.5578 49.2942 24.5578C49.5922 24.5578 50.1881 24.5578 50.486 24.8564C51.6778 25.155 52.2736 26.3494 52.2736 27.5437V46.3542V46.6529H61.8082C62.404 46.6529 62.7022 46.6529 63 46.6529V0.970426C63 0.970426 63 0.970427 62.4042 0.373196C61.8083 -0.224035 62.1063 0.0747274 61.5102 0.0747274H16.8176C16.8176 0.373196 16.8176 0.671811 16.8176 1.56766V10.8234C16.8176 11.4205 16.8176 12.0177 16.8176 12.0177H35.8865C37.0783 12.0177 38.2701 12.6148 38.5679 13.8091C39.1638 15.0034 38.8659 16.1978 37.9721 17.0936L0.728355 54.1174C0.430367 54.416 0.132374 54.7146 0.132374 55.3117C-0.16547 55.9088 0.132374 55.9088 0.132374 56.2074Z"
                        fill="currentColor" />
                </svg>
            </span>
        </span>
    </div>

    <!--==================== Overlay Start ====================-->
    <div class="overlay"></div>
    <!--==================== Overlay End ====================-->

    <!--==================== Sidebar Overlay End ====================-->
    <div class="side-overlay"></div>
    <!--==================== Sidebar Overlay End ====================-->

    <!-- Custom Toast Message start -->
    <div id="toast-container"></div>
    <!-- Custom Toast Message End -->

    <div class="progress-wrap cursor-big">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>

    <!-- Custom Cursor Start -->
    <div class="cursor"></div>
    <span class="dot"></span>
    <!-- Custom Cursor End -->

    <!-- ==================== Mobile Menu Start Here ==================== -->
    <div class="mobile-menu d-lg-none d-block scroll-sm position-fixed bg-white tw-w-300-px tw-h-screen overflow-y-auto tw-p-6 tw-z-999 tw--translate-x-full tw-pb-68 "
        style="z-index: 1100 !important;">

        <button type="button"
            class="close-button position-absolute tw-end-0 top-0 tw-me-2 tw-mt-2 tw-w-11 tw-h-11 rounded-circle d-flex justify-content-center align-items-center text-neutral-900 bg-neutral-200 hover-bg-neutral-900 hover-text-white">
            <i class="ph ph-x"></i>
        </button>

        <div class="mobile-menu__inner">
            <a href="./" class="mobile-menu__logo">
                <img src="assets/images/logo/logo.png" alt="Logo">
            </a>
            <div class="mobile-menu__menu">
                <ul
                    class="nav-menu d-lg-flex align-items-center nav-menu--mobile d-block tw-mt-8 bg-white tw-px-12 tw-rounded-4xl">
                    <li class="nav-menu__item">
                        <a href="./"
                            class="nav-menu__link text-main-600 tw-py-3 fw-medium w-100 font-dmsans"><?php echo lang('Ana Sayfa', 'Home'); ?></a>
                    </li>
                    <li class="nav-menu__item has-submenu position-relative">
                        <a href="javascript:void(0)"
                            class="nav-menu__link tw-pe-5 text-main-600 tw-py-3 fw-medium w-100 font-dmsans fw-medium"><?php echo lang('Kurumsal', 'Corporate'); ?></a>
                        <ul
                            class="nav-submenu scroll-sm position-absolute start-0 top-100 tw-w-max bg-white overflow-hidden tw-p-2 tw-mt-1 tw-duration-200 tw-z-99">
                            <li class="nav-menu__item">
                                <a href="about-us"
                                    class="nav-submenu__link text-main-600 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded font-dmsans fw-medium"><?php echo lang('Hakkımızda', 'About Us'); ?></a>
                            </li>
                            <li class="nav-menu__item">
                                <a href="corporate/mission-vision"
                                    class="nav-submenu__link text-main-600 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded font-dmsans fw-medium"><?php echo lang('Misyon & Vizyon', 'Mission & Vision'); ?></a>
                            </li>
                            <li class="nav-menu__item">
                                <a href="corporate/sustainability"
                                    class="nav-submenu__link text-main-600 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded font-dmsans fw-medium"><?php echo lang('Sürdürülebilirlik', 'Sustainability'); ?></a>
                            </li>
                            <li class="nav-menu__item text-main-600">
                                <a href="blog"
                                    class="nav-submenu__link text-main-600 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded font-dmsans fw-medium"><?php echo lang('Blog', 'Blog'); ?></a>
                            </li>
                            <li class="nav-menu__item text-main-600">
                                <a href="gallery"
                                    class="nav-submenu__link text-main-600 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded font-dmsans fw-medium"><?php echo lang('Galeri', 'Gallery'); ?></a>
                            </li>
                            <li class="nav-menu__item text-main-600">
                                <a href="contact"
                                    class="nav-submenu__link text-main-600 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded font-dmsans fw-medium"><?php echo lang('Biz Ulaşın', 'Contact Us'); ?></a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-menu__item">
                        <a href="tours"
                            class="nav-menu__link text-main-600 tw-py-3 fw-medium w-100 font-dmsans"><?php echo lang('Özel Turlar', 'Private Tours'); ?></a>
                    </li>
                    <li class="nav-menu__item">
                        <a href="blog"
                            class="nav-menu__link text-main-600 tw-py-3 fw-medium w-100 font-dmsans"><?php echo lang('Blog', 'Blog'); ?></a>
                    </li>
                    <li class="nav-menu__item">
                        <a href="gallery"
                            class="nav-menu__link text-main-600 tw-py-3 fw-medium w-100 font-dmsans"><?php echo lang('Galeri', 'Gallery'); ?></a>
                    </li>
                    <li class="nav-menu__item">
                        <a href="contact"
                            class="nav-menu__link text-main-600 tw-py-3 fw-medium w-100 font-dmsans"><?php echo lang('Bize Ulaşın', 'Contact Us'); ?></a>
                    </li>
                    <li class="nav-menu__item">
                        <a href="change_lang.php?l=<?php echo ($_SESSION['lang'] == 'tr') ? 'en' : 'tr'; ?>"
                            class="nav-menu__link text-main-600 tw-py-3 fw-bold"><?php echo ($_SESSION['lang'] == 'tr') ? 'EN' : 'TR'; ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- ==================== Mobile Menu End Here ==================== -->

    <!-- ==================== Header Start Here ==================== -->
    <header class="header header-two transition-all">
        <div class="container-fluid">
            <nav class="d-flex align-items-center justify-content-between">
                <div class="logo">
                    <a href="./" class="link">
                        <img src="assets/images/logo/logo.png" alt="Logo" class="max-w-200-px">
                    </a>
                </div>

                <div class="header-menu header-two-menu d-lg-block d-none">
                    <ul class="nav-menu d-lg-flex align-items-center tw-gap-6">
                        <li class="nav-menu__item">
                            <a href="./"
                                class="nav-menu__link text-main-600 tw-py-3 fw-medium w-100 font-dmsans"><?php echo lang('Ana Sayfa', 'Home'); ?></a>
                        </li>
                        <li class="nav-menu__item has-submenu position-relative">
                            <a href="javascript:void(0)"
                                class="nav-menu__link tw-pe-5 text-main-600 tw-py-3 fw-medium w-100 font-dmsans fw-medium"><?php echo lang('Kurumsal', 'Corporate'); ?></a>
                            <ul
                                class="nav-submenu scroll-sm position-absolute start-0 top-100 tw-w-max bg-white tw-rounded-md overflow-hidden tw-p-2 tw-mt-1 tw-duration-200 tw-z-99">
                                <li class="nav-menu__item">
                                    <a href="about-us"
                                        class="nav-submenu__link text-main-600 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded font-dmsans fw-medium"><?php echo lang('Hakkımızda', 'About Us'); ?></a>
                                </li>
                                <li class="nav-menu__item">
                                    <a href="corporate/mission-vision"
                                        class="nav-submenu__link text-main-600 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded font-dmsans fw-medium"><?php echo lang('Misyon & Vizyon', 'Mission & Vision'); ?></a>
                                </li>
                                <li class="nav-menu__item">
                                    <a href="corporate/sustainability"
                                        class="nav-submenu__link text-main-600 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded font-dmsans fw-medium"><?php echo lang('Sürdürülebilirlik', 'Sustainability'); ?></a>
                                </li>
                                <li class="nav-menu__item">
                                    <a href="blog"
                                        class="nav-submenu__link text-main-600 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded font-dmsans fw-medium"><?php echo lang('Blog', 'Blog'); ?></a>
                                </li>
                                <li class="nav-menu__item">
                                    <a href="gallery"
                                        class="nav-submenu__link text-main-600 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded font-dmsans fw-medium"><?php echo lang('Galeri', 'Gallery'); ?></a>
                                </li>
                                <li class="nav-menu__item">
                                    <a href="contact"
                                        class="nav-submenu__link text-main-600 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded font-dmsans fw-medium"><?php echo lang('Biz Ulaşın', 'Contact Us'); ?></a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-menu__item">
                            <a href="tours"
                                class="nav-menu__link text-main-600 tw-py-3 fw-medium w-100 font-dmsans"><?php echo lang('Özel Turlar', 'Private Tours'); ?></a>
                        </li>
                        <li class="nav-menu__item">
                            <a href="blog"
                                class="nav-menu__link text-main-600 tw-py-3 fw-medium w-100 font-dmsans"><?php echo lang('Blog', 'Blog'); ?></a>
                        </li>
                        <li class="nav-menu__item">
                            <a href="gallery"
                                class="nav-menu__link text-main-600 tw-py-3 fw-medium w-100 font-dmsans"><?php echo lang('Galeri', 'Gallery'); ?></a>
                        </li>
                        <li class="nav-menu__item">
                            <a href="contact"
                                class="nav-menu__link text-main-600 tw-py-3 fw-medium w-100 font-dmsans"><?php echo lang('Bize Ulaşın', 'Contact Us'); ?></a>
                        </li>
                        <li class="nav-menu__item">
                            <a href="change_lang.php?l=<?php echo ($_SESSION['lang'] == 'tr') ? 'en' : 'tr'; ?>"
                                class="nav-menu__link text-main-600 tw-py-3 fw-bold"><?php echo ($_SESSION['lang'] == 'tr') ? 'EN' : 'TR'; ?></a>
                        </li>
                    </ul>
                </div>

                <div class="header-right">
                    <div class="d-flex align-items-center tw-gap-29">
                        <div class="header-btn-wrap d-flex align-items-center tw-gap-5">
                            <a href="<?php echo $settings['instagram']; ?>" target="_blank" class="open-search">
                                <span><img src="assets/images/icon/instagram.svg" alt="instagram"></span>
                            </a>
                        </div>
                    </div>
                </div>
                <button type="button" class="toggle-mobileMenu leading-none d-lg-none ms-3 text-neutral-800 tw-text-9">
                    <i class="ph ph-list"></i>
                </button>
            </nav>
        </div>
    </header>
    <!-- ==================== Header End Here ==================== -->
    <div id="scrollSmoother-container">