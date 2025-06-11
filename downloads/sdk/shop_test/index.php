<?php
require_once 'conf.php';
global $BASE_URL;
global $PAYTECH_URL;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta property="og:site_name" content="PayTech">
    <meta property="og:title" content="PayTech">
    <meta property="og:description"
          content="PayTech | Boutique Démo">
    <meta property="og:image" content="<?= $BASE_URL ?>/assets/img/paytech-green.png">
    <meta property="og:url" content="<?= $BASE_URL ?>/">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="fr_FR">
    <meta name="generator"
          content="PayTech | Boutique Démo"/>

    <title>PayTech | Boutique Démo</title>

    <link rel="shortcut icon" type="image/png" href="assets/img/paytech-green.png"/>
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300,300i,400,400i,600,600i,700,700i&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="assets/css/animate.css"/>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="assets/css/chosen.min.css"/>
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="assets/css/jquery.scrollbar.css"/>
    <link rel="stylesheet" type="text/css" href="assets/css/lightbox.min.css"/>
    <link rel="stylesheet" type="text/css" href="assets/css/magnific-popup.css"/>
    <link rel="stylesheet" type="text/css" href="assets/css/slick.min.css"/>
    <link rel="stylesheet" type="text/css" href="assets/fonts/flaticon.css"/>
    <link rel="stylesheet" type="text/css" href="assets/css/megamenu.css"/>
    <link rel="stylesheet" type="text/css" href="assets/css/dreaming-attribute.css"/>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css"/>
    <script src="<?= $PAYTECH_URL ?>/cdn/paytech.min.js"></script>
    <link rel="stylesheet" href="<?= $PAYTECH_URL ?>/cdn/paytech.min.css">

</head>
<body>
<header id="header" class="header style-01 header-dark header-transparent header-sticky">
    <div class="header-wrap-stick">
        <div class="header-position">
            <div class="header-middle">
                <div class="furgan-menu-wapper"></div>
                <div class="header-middle-inner">
                    <div class="header-search-menu">
                        <ul class="wpml-menu">
                            <!--<li class="menu-item furgan-dropdown block-language">
                                <a href="javascript:void(0);" data-furgan="furgan-dropdown">
                                    <img src="assets/images/en.png"
                                         alt="en">
                                    English
                                </a>
                                <span class="toggle-submenu"></span>
                                <ul class="sub-menu">
                                    <li class="menu-item">
                                        <a href="#">
                                            <img src="assets/images/it.png"
                                                 alt="it">
                                            Italiano
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="menu-item">
                                <div class="wcml-dropdown product wcml_currency_switcher">
                                    <ul>
                                        <li class="wcml-cs-active-currency">
                                            <a class="wcml-cs-item-toggle">USD</a>
                                            <ul class="wcml-cs-submenu">
                                                <li>
                                                    <a>EUR</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </li>-->
                        </ul>
                    </div>
                    <div class="header-logo-nav">
                        <div class="header-logo">
                            <a href="index.html">
                                <img style="width: 300px" alt="Furgan" src="assets/img/logo-transparent.png" class="logo">
                            </a>
                        </div>
                        <div class="box-header-nav menu-center">
                            <ul id="menu-primary-menu"
                                class="clone-main-menu furgan-clone-mobile-menu furgan-nav main-menu">
                               <!-- <li id="menu-item-230"
                                    class="menu-item menu-item-type-post_type menu-item-object-megamenu menu-item-230 parent parent-megamenu item-megamenu menu-item-has-children">
                                    <a class="furgan-menu-item-title" title="Home" href="index.html">Home</a>
                                    <span class="toggle-submenu"></span>
                                    <div class="submenu megamenu megamenu-home">
                                        <div class="demo-item">
                                            <div class="row">
                                                <div class="col-md-6 col-lg-4 col-sm-6">
                                                    <div class="dreaming_single_image dreaming_content_element az_align_left shadow-img">
                                                        <figure class="dreaming_wrapper az_figure">
                                                            <a href="index.html" target="_self"
                                                               class="az_single_image-wrapper az_box_border_grey effect normal-effect dark-bg">
                                                                <img src="assets/images/demo001.jpg"
                                                                     class="az_single_image-img attachment-full" alt="img">
                                                            </a>
                                                        </figure>
                                                    </div>
                                                    <h5 class="az_custom_heading">
                                                        <a href="index.html">Home 01</a>
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-lg-4 col-sm-6">
                                                    <div class="dreaming_single_image dreaming_content_element az_align_left shadow-img">
                                                        <figure class="dreaming_wrapper az_figure">
                                                            <a href="home-02.html" target="_self"
                                                               class="az_single_image-wrapper az_box_border_grey effect normal-effect dark-bg ">
                                                                <img src="assets/images/demo002.jpg"
                                                                     class="az_single_image-img attachment-full" alt="img"></a>
                                                        </figure>
                                                    </div>
                                                    <h5 class="az_custom_heading">
                                                        <a href="home-02.html">Home 02</a>
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-lg-4 col-sm-6">
                                                    <div class="dreaming_single_image dreaming_content_element az_align_left shadow-img">
                                                        <figure class="dreaming_wrapper az_figure">
                                                            <a href="home-03.html" target="_self"
                                                               class="az_single_image-wrapper az_box_border_grey effect normal-effect dark-bg">
                                                                <img src="assets/images/demo003.jpg"
                                                                     class="az_single_image-img attachment-full" alt="img">
                                                            </a>
                                                        </figure>
                                                    </div>
                                                    <h5 class="az_custom_heading">
                                                        <a href="home-03.html">Home 03</a>
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-lg-4 col-sm-6">
                                                    <div class="dreaming_single_image dreaming_content_element az_align_left shadow-img">
                                                        <figure class="dreaming_wrapper az_figure">
                                                            <a href="home-04.html" target="_self"
                                                               class="az_single_image-wrapper az_box_border_grey effect normal-effect dark-bg ">
                                                                <img src="assets/images/demo004.jpg"
                                                                     class="az_single_image-img attachment-full" alt="img">
                                                            </a>
                                                        </figure>
                                                    </div>
                                                    <h5 class="az_custom_heading">
                                                        <a href="home-04.html">Home 04</a></h5>
                                                </div>
                                                <div class="col-md-6 col-lg-4 col-sm-6">
                                                    <div class="dreaming_single_image dreaming_content_element az_align_left shadow-img">
                                                        <figure class="dreaming_wrapper az_figure">
                                                            <a href="home-05.html" target="_self"
                                                               class="az_single_image-wrapper az_box_border_grey effect normal-effect dark-bg">
                                                                <img src="assets/images/demo005.jpg"
                                                                     class="az_single_image-img attachment-full" alt="img">
                                                            </a>
                                                        </figure>
                                                    </div>
                                                    <h5 class="az_custom_heading">
                                                        <a href="home-05.html">Home 05</a>
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 col-lg-4 col-sm-6">
                                                    <div class="dreaming_single_image dreaming_content_element az_align_left shadow-img">
                                                        <figure class="dreaming_wrapper az_figure">
                                                            <a href="home-06.html" target="_self"
                                                               class="az_single_image-wrapper az_box_border_grey effect normal-effect dark-bg ">
                                                                <img src="assets/images/demo006.jpg"
                                                                     class="az_single_image-img attachment-full" alt="img">
                                                            </a>
                                                        </figure>
                                                    </div>
                                                    <h5 class="az_custom_heading">
                                                        <a href="home-06.html">Home 06</a>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li id="menu-item-228"
                                    class="menu-item menu-item-type-post_type menu-item-object-megamenu menu-item-228 parent parent-megamenu item-megamenu menu-item-has-children">
                                    <a class="furgan-menu-item-title" title="Shop"
                                       href="shop.html">Shop</a>
                                    <span class="toggle-submenu"></span>
                                    <div class="submenu megamenu megamenu-shop">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="furgan-listitem style-01">
                                                    <div class="listitem-inner">
                                                        <h4 class="title">Shop Layouts </h4>
                                                        <ul class="listitem-list">
                                                            <li>
                                                                <a href="shop.html" target="_self">Shop Grid </a>
                                                            </li>
                                                            <li>
                                                                <a href="shop-list.html" target="_self">
                                                                    <span class="image">
                                                                        <img src="assets/images/label-new.jpg"
                                                                             class="attachment-full size-full" alt="img">
                                                                    </span>
                                                                    Shop List
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="shop.html" target="_self">No Sidebar </a>
                                                            </li>
                                                            <li>
                                                                <a href="shop-leftsidebar.html" target="_self">Left
                                                                    Sidebar </a>
                                                            </li>
                                                            <li>
                                                                <a href="shop-rightsidebar.html" target="_self">Right
                                                                    Sidebar </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="furgan-listitem style-01">
                                                    <div class="listitem-inner">
                                                        <h4 class="title">Product Layouts </h4>
                                                        <ul class="listitem-list">
                                                            <li>
                                                                <a href="single-product.html" target="_self">Vertical
                                                                    Thumbnails </a>
                                                            </li>
                                                            <li>
                                                                <a href="single-product-policy.html"
                                                                   target="_self">
                                                                    <span class="image">
                                                                        <img src="assets/images/label-new.jpg"
                                                                             class="attachment-full size-full" alt="img">
                                                                    </span>
                                                                    Extra Sidebar
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="single-product-rightsidebar.html"
                                                                   target="_self">
                                                                    Right Sidebar </a>
                                                            </li>
                                                            <li>
                                                                <a href="single-product-leftsidebar.html"
                                                                   target="_self">
                                                                    Left Sidebar </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="furgan-listitem style-01">
                                                    <div class="listitem-inner">
                                                        <h4 class="title">
                                                            Product Extends </h4>
                                                        <ul class="listitem-list">
                                                            <li>
                                                                <a href="single-product-bundle.html"
                                                                   target="_self">
                                                                            <span class="image">
                                                                                <img src="assets/images/label-new.jpg"
                                                                                     class="attachment-full size-full"
                                                                                     alt="img">
                                                                            </span>
                                                                    Product Bundle
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="single-product-360deg.html"
                                                                   target="_self">
                                                                    <span class="image">
                                                                        <img src="assets/images/label-hot.jpg"
                                                                             class="attachment-full size-full" alt="img">
                                                                    </span>
                                                                    Product 360 Deg </a>
                                                            </li>
                                                            <li>
                                                                <a href="single-product-video.html"
                                                                   target="_self">
                                                                    Video </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="furgan-listitem style-01">
                                                    <div class="listitem-inner">
                                                        <h4 class="title">
                                                            Other Pages </h4>
                                                        <ul class="listitem-list">
                                                            <li>
                                                                <a href="cart.html">Cart </a>
                                                            </li>
                                                            <li>
                                                                <a href="wishlist.html" target="_self">Wishlist </a>
                                                            </li>
                                                            <li>
                                                                <a href="checkout.html" target="_self">Checkout </a>
                                                            </li>
                                                            <li>
                                                                <a href="order-tracking.html" target="_self">Order
                                                                    Tracking </a>
                                                            </li>
                                                            <li>
                                                                <a href="my-account.html" target="_self">My Account </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="furgan-listitem style-01">
                                                    <div class="listitem-inner">
                                                        <h4 class="title">
                                                            Product Types </h4>
                                                        <ul class="listitem-list">
                                                            <li>
                                                                <a href="single-product-simple.html"
                                                                   target="_self">
                                                                    Simple </a>
                                                            </li>
                                                            <li>
                                                                <a href="single-product.html"
                                                                   target="_self">
                                                                            <span class="image"><img
                                                                                    src="assets/images/label-hot.jpg"
                                                                                    class="attachment-full size-full"
                                                                                    alt="img"></span>
                                                                    Variable </a>
                                                            </li>
                                                            <li>
                                                                <a href="single-product-external.html"
                                                                   target="_self">
                                                                    External / Affiliate </a>
                                                            </li>
                                                            <li>
                                                                <a href="single-product-group.html"
                                                                   target="_self">
                                                                    Grouped </a>
                                                            </li>
                                                            <li>
                                                                <a href="single-product-outofstock.html"
                                                                   target="_self">
                                                                    Out Of Stock </a>
                                                            </li>
                                                            <li>
                                                                <a href="single-product-onsale.html"
                                                                   target="_self">
                                                                    On Sale </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li id="menu-item-229"
                                    class="menu-item menu-item-type-post_type menu-item-object-megamenu menu-item-229 parent parent-megamenu item-megamenu menu-item-has-children">
                                    <a class="furgan-menu-item-title" title="Elements" href="#">Elements</a>
                                    <span class="toggle-submenu"></span>
                                    <div class="submenu megamenu megamenu-elements">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="furgan-listitem style-01">
                                                    <div class="listitem-inner">
                                                        <h4 class="title">Element 1 </h4>
                                                        <ul class="listitem-list">
                                                            <li>
                                                                <a href="banner.html"
                                                                   target="_self">Banner </a>
                                                            </li>
                                                            <li>
                                                                <a href="blog-element.html"
                                                                   target="_self">Blog Element </a>
                                                            </li>
                                                            <li>
                                                                <a href="categories-element.html"
                                                                   target="_self">
                                                                    Categories Element </a>
                                                            </li>
                                                            <li>
                                                                <a href="product-element.html"
                                                                   target="_self">
                                                                    Product Element </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="furgan-listitem style-01">
                                                    <div class="listitem-inner">
                                                        <h4 class="title">
                                                            Element 2 </h4>
                                                        <ul class="listitem-list">
                                                            <li>
                                                                <a href="client.html"
                                                                   target="_self">
                                                                    Client </a>
                                                            </li>
                                                            <li>
                                                                <a href="product-layout.html"
                                                                   target="_self">
                                                                    Product Layout </a>
                                                            </li>
                                                            <li>
                                                                <a href="google-map.html"
                                                                   target="_self">
                                                                    Google map </a>
                                                            </li>
                                                            <li>
                                                                <a href="iconbox.html"
                                                                   target="_self">
                                                                    Icon Box </a>
                                                            </li>
                                                            <li>
                                                                <a href="team.html"
                                                                   target="_self">
                                                                    Team </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="furgan-listitem style-01">
                                                    <div class="listitem-inner">
                                                        <h4 class="title">
                                                            Element 3 </h4>
                                                        <ul class="listitem-list">
                                                            <li>
                                                                <a href="instagram-feed.html"
                                                                   target="_self">
                                                                    Instagram Feed </a>
                                                            </li>
                                                            <li>
                                                                <a href="newsletter.html"
                                                                   target="_self">
                                                                    Newsletter </a>
                                                            </li>
                                                            <li>
                                                                <a href="testimonials.html"
                                                                   target="_self">
                                                                    Testimonials </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li id="menu-item-996"
                                    class="menu-item menu-item-type-post_type menu-item-object-megamenu menu-item-996 parent parent-megamenu item-megamenu menu-item-has-children">
                                    <a class="furgan-menu-item-title" title="Blog"
                                       href="blog.html">Blog</a>
                                    <span class="toggle-submenu"></span>
                                    <div class="submenu megamenu megamenu-blog">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="furgan-listitem style-01">
                                                    <div class="listitem-inner">
                                                        <h4 class="title">
                                                            Blog Layout </h4>
                                                        <ul class="listitem-list">
                                                            <li>
                                                                <a href="blog.html" target="_self">No Sidebar </a>
                                                            </li>
                                                            <li>
                                                                <a href="blog-leftsidebar.html" target="_self">Left
                                                                    Sidebar </a>
                                                            </li>
                                                            <li>
                                                                <a href="blog-rightsidebar.html" target="_self">Right
                                                                    Sidebar </a>
                                                            </li>
                                                            <li>
                                                                <a href="blog.html" target="_self">Blog Standard </a>
                                                            </li>
                                                            <li>
                                                                <a href="blog-grid.html" target="_self">Blog Grid </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="furgan-listitem style-01">
                                                    <div class="listitem-inner">
                                                        <h4 class="title">
                                                            Post Layout </h4>
                                                        <ul class="listitem-list">
                                                            <li>
                                                                <a href="single-post.html" target="_self">No
                                                                    Sidebar </a>
                                                            </li>
                                                            <li>
                                                                <a href="single-post-leftsidebar.html" target="_self">Left
                                                                    Sidebar </a>
                                                            </li>
                                                            <li>
                                                                <a href="single-post-rightsidebar.html" target="_self">Right
                                                                    Sidebar </a>
                                                            </li>
                                                            <li>
                                                                <a href="single-post-instagram.html" target="_self">
                                                                    <span class="image">
                                                                        <img src="assets/images/label-hot.jpg"
                                                                             class="attachment-full size-full" alt="img">
                                                                    </span>
                                                                    Instagram In Post
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="single-post-product.html"
                                                                   target="_self">
                                                                    <span class="image">
                                                                        <img src="assets/images/label-new.jpg"
                                                                             class="attachment-full size-full" alt="img">
                                                                    </span>
                                                                    Product In Post
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="furgan-listitem style-01">
                                                    <div class="listitem-inner">
                                                        <h4 class="title">
                                                            Post Format </h4>
                                                        <ul class="listitem-list">
                                                            <li>
                                                                <a href="single-post.html" target="_self">Standard </a>
                                                            </li>
                                                            <li>
                                                                <a href="single-post-gallery.html" target="_self">Gallery </a>
                                                            </li>
                                                            <li>
                                                                <a href="single-post-video.html"
                                                                   target="_self">
                                                                    <span class="image">
                                                                        <img src="assets/images/label-hot.jpg"
                                                                             class="attachment-full size-full" alt="img">
                                                                    </span>
                                                                    Video
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li id="menu-item-237"
                                    class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-237 parent">
                                    <a class="furgan-menu-item-title" title="Pages" href="#">Pages</a>
                                    <span class="toggle-submenu"></span>
                                    <ul role="menu" class="submenu">
                                        <li id="menu-item-987"
                                            class="menu-item menu-item-type-custom menu-item-object-custom menu-item-987">
                                            <a class="furgan-menu-item-title" title="About"
                                               href="about.html">About</a></li>
                                        <li id="menu-item-988"
                                            class="menu-item menu-item-type-custom menu-item-object-custom menu-item-988">
                                            <a class="furgan-menu-item-title" title="Contact"
                                               href="contact.html">Contact</a></li>
                                        <li id="menu-item-990"
                                            class="menu-item menu-item-type-custom menu-item-object-custom menu-item-990">
                                            <a class="furgan-menu-item-title" title="Page 404"
                                               href="404.html">Page 404</a></li>
                                    </ul>
                                </li>-->
                            </ul>
                        </div>
                    </div>
                    <div class="header-control">
                        <div class="header-control-inner">
                            <div class="meta-dreaming">
                                <!--<div class="header-search furgan-dropdown">
                                    <div class="header-search-inner" data-furgan="furgan-dropdown">
                                        <a href="javascript:void(0);" class="link-dropdown block-link">
                                            <span class="flaticon-magnifying-glass-1"></span>
                                        </a>
                                    </div>
                                    <div class="block-search">
                                        <form role="search" method="get"
                                              class="form-search block-search-form furgan-live-search-form">
                                            <div class="form-content search-box results-search">
                                                <div class="inner">
                                                    <input autocomplete="off" class="searchfield txt-livesearch input"
                                                           name="s" value="" placeholder="Search here..." type="text">
                                                </div>
                                            </div>
                                            <div class="category">
                                                <select title="product_cat" name="product_cat"
                                                        class="category-search-option">
                                                    <option value="0">All Categories</option>
                                                    <option class="level-0" value="light">Light</option>
                                                    <option class="level-0" value="chair">Chair</option>
                                                    <option class="level-0" value="table">Table</option>
                                                    <option class="level-0" value="bed">Bed</option>
                                                    <option class="level-0" value="new-arrivals">New arrivals</option>
                                                    <option class="level-0" value="lamp">Lamp</option>
                                                    <option class="level-0" value="specials">Specials</option>
                                                    <option class="level-0" value="sofas">Sofas</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn-submit">
                                                <span class="flaticon-magnifying-glass-1"></span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="furgan-dropdown-close">x</div>
                                <div class="menu-item block-user block-dreaming furgan-dropdown">
                                    <a class="block-link" href="#">
                                        <span class="flaticon-profile"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="menu-item furgan-MyAccount-navigation-link furgan-MyAccount-navigation-link--dashboard is-active">
                                            <a href="#">Dashboard</a>
                                        </li>
                                        <li class="menu-item furgan-MyAccount-navigation-link furgan-MyAccount-navigation-link--orders">
                                            <a href="#">Orders</a>
                                        </li>
                                        <li class="menu-item furgan-MyAccount-navigation-link furgan-MyAccount-navigation-link--downloads">
                                            <a href="#">Downloads</a>
                                        </li>
                                        <li class="menu-item furgan-MyAccount-navigation-link furgan-MyAccount-navigation-link--edit-adchair">
                                            <a href="#">Address</a>
                                        </li>
                                        <li class="menu-item furgan-MyAccount-navigation-link furgan-MyAccount-navigation-link--edit-account">
                                            <a href="#">Account details</a>
                                        </li>
                                        <li class="menu-item furgan-MyAccount-navigation-link furgan-MyAccount-navigation-link--customer-logout">
                                            <a href="#">Logout</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="block-minicart block-dreaming furgan-mini-cart furgan-dropdown">
                                    <div class="shopcart-dropdown block-cart-link" data-furgan="furgan-dropdown">
                                        <a class="block-link link-dropdown" href="#">
                                            <span class="flaticon-cart"></span>
                                            <span class="count">3</span>
                                        </a>
                                    </div>
                                    <div class="widget furgan widget_shopping_cart">
                                        <div class="widget_shopping_cart_content">
                                            <ul class="furgan-mini-cart cart_list product_list_widget">
                                                <li class="furgan-mini-cart-item mini_cart_item">
                                                    <a href="javascript:void(0);" class="remove remove_from_cart_button">×</a>
                                                    <a href="#">
                                                        <img src="assets/images/apro134-1-600x778.jpg"
                                                             class="attachment-furgan_thumbnail size-furgan_thumbnail"
                                                             alt="img" width="600" height="778">T-shirt with skirt – Pink&nbsp;
                                                    </a>
                                                    <span class="quantity">1 × <span
                                                            class="furgan-Price-amount amount"><span
                                                                class="furgan-Price-currencySymbol">$</span>150.00</span></span>
                                                </li>
                                                <li class="furgan-mini-cart-item mini_cart_item">
                                                    <a href="javascript:void(0);" class="remove remove_from_cart_button">×</a>
                                                    <a href="#">
                                                        <img src="assets/images/apro1113-600x778.jpg"
                                                             class="attachment-furgan_thumbnail size-furgan_thumbnail"
                                                             alt="img" width="600" height="778">Alarm Clock&nbsp;
                                                    </a>
                                                    <span class="quantity">1 × <span
                                                            class="furgan-Price-amount amount"><span
                                                                class="furgan-Price-currencySymbol">$</span>129.00</span></span>
                                                </li>
                                                <li class="furgan-mini-cart-item mini_cart_item">
                                                    <a href="javascript:void(0);" class="remove remove_from_cart_button">×</a>
                                                    <a href="#">
                                                        <img src="assets/images/apro201-1-600x778.jpg"
                                                             class="attachment-furgan_thumbnail size-furgan_thumbnail"
                                                             alt="img" width="600" height="778">Chair AAC&nbsp;
                                                    </a>
                                                    <span class="quantity">1 × <span
                                                            class="furgan-Price-amount amount"><span
                                                                class="furgan-Price-currencySymbol">$</span>139.00</span></span>
                                                </li>
                                            </ul>
                                            <p class="furgan-mini-cart__total total"><strong>Subtotal:</strong>
                                                <span class="furgan-Price-amount amount"><span
                                                        class="furgan-Price-currencySymbol">$</span>418.00</span>
                                            </p>
                                            <p class="furgan-mini-cart__buttons buttons">
                                                <a href="cart.html" class="button furgan-forward">Viewcart</a>
                                                <a href="checkout.html" class="button checkout furgan-forward">Checkout</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-mobile">
        <div class="header-mobile-left">
            <div class="block-menu-bar">
                <a class="menu-bar menu-toggle" href="#">
                    <span></span>
                    <span></span>
                    <span></span>
                </a>
            </div>
            <div class="header-search furgan-dropdown">
                <div class="header-search-inner" data-furgan="furgan-dropdown">
                    <a href="javascript:void(0);" class="link-dropdown block-link">
                        <span class="flaticon-magnifying-glass-1"></span>
                    </a>
                </div>
                <div class="block-search">
                    <form role="search" method="get"
                          class="form-search block-search-form furgan-live-search-form">
                        <div class="form-content search-box results-search">
                            <div class="inner">
                                <input autocomplete="off" class="searchfield txt-livesearch input" name="s" value=""
                                       placeholder="Search here..." type="text">
                            </div>
                        </div>
                        <div class="category">
                            <select title="product_cat" name="product_cat" class="category-search-option">
                                <option value="0">All Categories</option>
                                <option class="level-0" value="light">Light</option>
                                <option class="level-0" value="chair">Chair</option>
                                <option class="level-0" value="table">Table</option>
                                <option class="level-0" value="bed">Bed</option>
                                <option class="level-0" value="new-arrivals">New arrivals</option>
                                <option class="level-0" value="lamp">Lamp</option>
                                <option class="level-0" value="specials">Specials</option>
                                <option class="level-0" value="sofas">Sofas</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-submit">
                            <span class="flaticon-magnifying-glass-1"></span>
                        </button>
                    </form><!-- block search -->
                </div>
            </div>
            <ul class="wpml-menu">
                <li class="menu-item furgan-dropdown block-language">
                    <a href="javascript:void(0);" data-furgan="furgan-dropdown">
                        <img src="assets/images/en.png"
                             alt="en">
                        English
                    </a>
                    <span class="toggle-submenu"></span>
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a href="#">
                                <img src="assets/images/it.png"
                                     alt="it">
                                Italiano
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item">
                    <div class="wcml-dropdown product wcml_currency_switcher">
                        <ul>
                            <!--<li class="wcml-cs-active-currency">
                                <a class="wcml-cs-item-toggle">USD</a>
                                <ul class="wcml-cs-submenu">
                                    <li>
                                        <a>EUR</a>
                                    </li>
                                </ul>
                            </li>-->
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        <div class="header-mobile-mid">
            <div class="header-logo">
                <a href="index.html"><img alt="Furgan" src="assets/images/logo.png" class="logo"></a>
            </div>
        </div>
        <div class="header-mobile-right">
            <div class="header-control-inner">
                <div class="meta-dreaming">
                    <div class="menu-item block-user block-dreaming furgan-dropdown">
                        <a class="block-link" href="#">
                            <span class="flaticon-profile"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="menu-item furgan-MyAccount-navigation-link furgan-MyAccount-navigation-link--dashboard is-active">
                                <a href="#">Dashboard</a>
                            </li>
                            <li class="menu-item furgan-MyAccount-navigation-link furgan-MyAccount-navigation-link--orders">
                                <a href="#">Orders</a>
                            </li>
                            <li class="menu-item furgan-MyAccount-navigation-link furgan-MyAccount-navigation-link--downloads">
                                <a href="#">Downloads</a>
                            </li>
                            <li class="menu-item furgan-MyAccount-navigation-link furgan-MyAccount-navigation-link--edit-adchair">
                                <a href="#">Address</a>
                            </li>
                            <li class="menu-item furgan-MyAccount-navigation-link furgan-MyAccount-navigation-link--edit-account">
                                <a href="#">Account details</a>
                            </li>
                            <li class="menu-item furgan-MyAccount-navigation-link furgan-MyAccount-navigation-link--customer-logout">
                                <a href="#">Logout</a>
                            </li>
                        </ul>
                    </div>
                    <div class="block-minicart block-dreaming furgan-mini-cart furgan-dropdown">
                        <div class="shopcart-dropdown block-cart-link" data-furgan="furgan-dropdown">
                            <a class="block-link link-dropdown" href="#">
                                <span class="flaticon-cart"></span>
                                <span class="count">3</span>
                            </a>
                        </div>
                        <div class="widget furgan widget_shopping_cart">
                            <div class="widget_shopping_cart_content">
                                <ul class="furgan-mini-cart cart_list product_list_widget">
                                    <li class="furgan-mini-cart-item mini_cart_item">
                                        <a href="javascript:void(0);" class="remove remove_from_cart_button">×</a>
                                        <a href="#">
                                            <img src="assets/images/apro134-1-600x778.jpg"
                                                 class="attachment-furgan_thumbnail size-furgan_thumbnail"
                                                 alt="img" width="600" height="778">T-shirt with skirt – Pink&nbsp;
                                        </a>
                                        <span class="quantity">1 × <span
                                                class="furgan-Price-amount amount"><span
                                                    class="furgan-Price-currencySymbol">$</span>150.00</span></span>
                                    </li>
                                    <li class="furgan-mini-cart-item mini_cart_item">
                                        <a href="javascript:void(0);" class="remove remove_from_cart_button">×</a>
                                        <a href="#">
                                            <img src="assets/images/apro1113-600x778.jpg"
                                                 class="attachment-furgan_thumbnail size-furgan_thumbnail"
                                                 alt="img" width="600" height="778">Alarm Clock&nbsp;
                                        </a>
                                        <span class="quantity">1 × <span
                                                class="furgan-Price-amount amount"><span
                                                    class="furgan-Price-currencySymbol">$</span>129.00</span></span>
                                    </li>
                                    <li class="furgan-mini-cart-item mini_cart_item">
                                        <a href="javascript:void(0);" class="remove remove_from_cart_button">×</a>
                                        <a href="#">
                                            <img src="assets/images/apro201-1-600x778.jpg"
                                                 class="attachment-furgan_thumbnail size-furgan_thumbnail"
                                                 alt="img" width="600" height="778">Chair AAC&nbsp;
                                        </a>
                                        <span class="quantity">1 × <span
                                                class="furgan-Price-amount amount"><span
                                                    class="furgan-Price-currencySymbol">$</span>139.00</span></span>
                                    </li>
                                </ul>
                                <p class="furgan-mini-cart__total total"><strong>Subtotal:</strong>
                                    <span class="furgan-Price-amount amount"><span
                                            class="furgan-Price-currencySymbol">$</span>418.00</span>
                                </p>
                                <p class="furgan-mini-cart__buttons buttons">
                                    <a href="cart.html" class="button furgan-forward">Viewcart</a>
                                    <a href="checkout.html" class="button checkout furgan-forward">Checkout</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="fullwidth-template">
    <div class="slide-home-01">
        <div class="response-product product-list-owl owl-slick equal-container better-height"
             data-slick="{&quot;arrows&quot;:false,&quot;slidesMargin&quot;:0,&quot;dots&quot;:true,&quot;infinite&quot;:false,&quot;speed&quot;:300,&quot;slidesToShow&quot;:1,&quot;rows&quot;:1}"
             data-responsive="[{&quot;breakpoint&quot;:480,&quot;settings&quot;:{&quot;slidesToShow&quot;:1,&quot;slidesMargin&quot;:&quot;0&quot;}},{&quot;breakpoint&quot;:768,&quot;settings&quot;:{&quot;slidesToShow&quot;:1,&quot;slidesMargin&quot;:&quot;0&quot;}},{&quot;breakpoint&quot;:992,&quot;settings&quot;:{&quot;slidesToShow&quot;:1,&quot;slidesMargin&quot;:&quot;0&quot;}},{&quot;breakpoint&quot;:1200,&quot;settings&quot;:{&quot;slidesToShow&quot;:1,&quot;slidesMargin&quot;:&quot;0&quot;}},{&quot;breakpoint&quot;:1500,&quot;settings&quot;:{&quot;slidesToShow&quot;:1,&quot;slidesMargin&quot;:&quot;0&quot;}}]">
            <div class="slide-wrap">
                <img src="assets/images/slide2222.jpg" alt="image">
                <div class="slide-info">
                    <div class="container">
                        <div class="slide-inner">
                            <h5>Nouveau arrivages</h5>
                            <h1>Chaise cuisine</h1>
                            <h2>70 000 CFA</h2>
                            <a onclick="buy(this)" href="#">Acheter</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide-wrap">
                <img src="assets/images/slide11new.jpg" alt="image">
                <div class="slide-info">
                    <div class="container">
                        <div class="slide-inner">
                            <h5>Meilleure ventes</h5>
                            <h1><span>Veilleuse</span> Blance</h1>
                            <h2>24 000 CFA</h2>
                            <a href="javascript:void(0);" onclick="buy(this)">Acheter</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide-wrap">
                <img src="assets/images/slide333.jpg" alt="image">
                <div class="slide-info">
                    <div class="container">
                        <div class="slide-inner">
                            <h5>Promo</h5>
                            <h1>Mega vente</h1>
                            <h2>70 000 CFA</h2>
                            <a href="javascript:void(0);" onclick="buy(this)">Acheter</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section-002">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-4">
                    <div class="furgan-iconbox style-01">
                        <div class="iconbox-inner">
                            <div class="icon">
                                <span class="flaticon-startup"></span>
                                <span class="flaticon-startup"></span>
                            </div>
                            <div class="content">
                                <h4 class="title">Expédition rapide.</h4>
                                <div class="desc">Avec des sites en 5 langues, nous livrons dans plus de 200 pays</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-4">
                    <div class="furgan-iconbox style-01">
                        <div class="iconbox-inner">
                            <div class="icon">
                                <span class="flaticon-padlock"></span>
                                <span class="flaticon-padlock"></span>
                            </div>
                            <div class="content">
                                <h4 class="title">Livraison sûre</h4>
                                <div class="desc">Payez avec des méthodes de paiement sécurisées populaires.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-4">
                    <div class="furgan-iconbox style-01">
                        <div class="iconbox-inner">
                            <div class="icon">
                                <span class="flaticon-recycle"></span>
                                <span class="flaticon-recycle"></span>
                            </div>
                            <div class="content">
                                <h4 class="title">Retour en 365 jours</h4>
                                <div class="desc">Assistance 24h / 24 pour une expérience d'achat.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section-003">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-6">
                    <div class="furgan-banner style-01 left-center">
                        <div class="banner-inner">
                            <figure class="banner-thumb">
                                <img src="assets/images/banner11.jpg"
                                     class="attachment-full size-full" alt="img"></figure>
                            <div class="banner-info ">
                                <div class="banner-content">
                                    <div class="title-wrap">
                                        <h6 class="title">
                                            <a  href="javascript:void(0);" onclick="buy(this)">Acheter</a>
                                        </h6>
                                    </div>
                                    <div class="button-wrap">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div class="furgan-banner style-01 right-top">
                        <div class="banner-inner">
                            <figure class="banner-thumb">
                                <img src="assets/images/banner12.jpg"
                                     class="attachment-full size-full" alt="img"></figure>
                            <div class="banner-info ">
                                <div class="banner-content">
                                    <div class="title-wrap">
                                        <h6 class="title">
                                            <a  href="javascript:void(0);" onclick="buy(this)">Acheter</a>
                                        </h6>
                                    </div>
                                    <div class="button-wrap">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="furgan-banner style-01 left-bottom">
                        <div class="banner-inner">
                            <figure class="banner-thumb">
                                <img src="assets/images/banner13.jpg"
                                     class="attachment-full size-full" alt="img"></figure>
                            <div class="banner-info ">
                                <div class="banner-content">
                                    <div class="title-wrap">
                                        <h6 class="title">
                                            <a  href="javascript:void(0);" onclick="buy(this)">Acheter</a>
                                        </h6>
                                    </div>
                                    <div class="button-wrap">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section-001">
        <div class="container">
            <div class="furgan-heading style-01">
                <div class="heading-inner">
                    <h3 class="title">Populaire </h3>
                    <div class="subtitle">Fabriqués avec soin pour vos petits, nos produits sont parfaits pour toutes les occasions. Vérifiez-le.
                    </div>
                </div>
            </div>
            <div class="furgan-products style-02">
                <div class="response-product product-list-owl owl-slick equal-container better-height"
                     data-slick="{&quot;arrows&quot;:false,&quot;slidesMargin&quot;:30,&quot;dots&quot;:true,&quot;infinite&quot;:false,&quot;speed&quot;:300,&quot;slidesToShow&quot;:4,&quot;rows&quot;:2}"
                     data-responsive="[{&quot;breakpoint&quot;:480,&quot;settings&quot;:{&quot;slidesToShow&quot;:2,&quot;slidesMargin&quot;:&quot;10&quot;}},{&quot;breakpoint&quot;:768,&quot;settings&quot;:{&quot;slidesToShow&quot;:2,&quot;slidesMargin&quot;:&quot;10&quot;}},{&quot;breakpoint&quot;:992,&quot;settings&quot;:{&quot;slidesToShow&quot;:3,&quot;slidesMargin&quot;:&quot;20&quot;}},{&quot;breakpoint&quot;:1200,&quot;settings&quot;:{&quot;slidesToShow&quot;:3,&quot;slidesMargin&quot;:&quot;20&quot;}},{&quot;breakpoint&quot;:1500,&quot;settings&quot;:{&quot;slidesToShow&quot;:4,&quot;slidesMargin&quot;:&quot;30&quot;}}]">
                    <div class="product-item featured_products style-02 rows-space-30 post-34 product type-product status-publish has-post-thumbnail product_cat-light product_cat-new-arrivals product_tag-light product_tag-hat product_tag-sock first instock sale featured shipping-taxable product-type-grouped">
                        <div class="product-inner tooltip-top">
                            <div class="product-thumb">
                                <a class="thumb-link"
                                   href="javascript:void(0);" tabindex="0">
                                    <img class="img-responsive"
                                         src="assets/images/apro61-1-270x350.jpg"
                                         alt="Sweeper Funnel" width="270" height="350">
                                </a>
                                <div class="flash">
                                    <span class="onnew"><span class="text">New</span></span></div>
                                <a href="javascript:void(0);" class="button yith-wcqv-button" onclick="buy(this)">Acheter</a></div>
                            <div class="product-info">
                                <div class="rating-wapper nostar">
                                    <div class="star-rating"><span style="width:0%">Rated <strong
                                                class="rating">0</strong> out of 5</span></div>
                                    <span class="review">(0)</span></div>
                                <h3 class="product-name product_title">
                                    <a href="#"
                                       tabindex="0">Sweeper Funnel</a>
                                </h3>
                                <span class="price"><span class="furgan-Price-amount amount"><span
                                            class="furgan-Price-currencySymbol">$</span>79.00</span> – <span
                                        class="furgan-Price-amount amount"><span
                                            class="furgan-Price-currencySymbol">$</span>139.00</span></span>
                            </div>
                            <div class="group-button clearfix">
                                <div class="yith-wcwl-add-to-wishlist">
                                    <div class="yith-wcwl-add-button show">
                                        <a href="javascript:void(0);" class="add_to_wishlist">Add to Wishlist</a>
                                    </div>
                                </div>
                                <div class="add-to-cart">
                                    <a href="javascript:void(0);" class="button product_type_grouped">
                                        View products</a></div>
                                <div class="furgan product compare-button">
                                    <a href="javascript:void(0);" class="compare button">Compare</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="product-item featured_products style-02 rows-space-30 post-32 product type-product status-publish has-post-thumbnail product_cat-light product_cat-chair product_cat-sofas product_tag-hat product_tag-sock  instock sale featured shipping-taxable purchasable product-type-simple">
                        <div class="product-inner tooltip-top">
                            <div class="product-thumb">
                                <a class="thumb-link"
                                   href="#"
                                   tabindex="0">
                                    <img class="img-responsive"
                                         src="assets/images/apro71-1-270x350.jpg"
                                         alt="Moss Sofa" width="270" height="350">
                                </a>
                                <div class="flash">
                                    <span class="onsale"><span class="number">-18%</span></span>
                                    <span class="onnew"><span class="text">New</span></span></div>
                                 <a href="javascript:void(0);" class="button yith-wcqv-button" onclick="buy(this)">Acheter</a></div>
                            <div class="product-info">
                                <div class="rating-wapper nostar">
                                    <div class="star-rating"><span style="width:0%">Rated <strong
                                                class="rating">0</strong> out of 5</span></div>
                                    <span class="review">(0)</span></div>
                                <h3 class="product-name product_title">
                                    <a href="#"
                                       tabindex="0">Moss Sofa</a>
                                </h3>
                                <span class="price"><del><span
                                            class="furgan-Price-amount amount"><span
                                                class="furgan-Price-currencySymbol">$</span>109.00</span></del> <ins><span
                                            class="furgan-Price-amount amount"><span
                                                class="furgan-Price-currencySymbol">$</span>89.00</span></ins></span>
                            </div>
                            <div class="group-button clearfix">
                                <div class="yith-wcwl-add-to-wishlist">
                                    <div class="yith-wcwl-add-button show">
                                        <a href="javascript:void(0);" class="add_to_wishlist">Add to Wishlist</a>
                                    </div>
                                </div>
                                <div class="add-to-cart">
                                    <a href="javascript:void(0);" class="button product_type_grouped">Add to cart</a></div>
                                <div class="furgan product compare-button">
                                    <a href="javascript:void(0);" class="compare button">Compare</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="product-item featured_products style-02 rows-space-30 post-30 product type-product status-publish has-post-thumbnail product_cat-light product_cat-bed product_cat-specials product_tag-light product_tag-table product_tag-sock last instock featured downloadable shipping-taxable purchasable product-type-simple">
                        <div class="product-inner tooltip-top">
                            <div class="product-thumb">
                                <a class="thumb-link"
                                   href="#"
                                   tabindex="0">
                                    <img class="img-responsive"
                                         src="assets/images/apro101-1-270x350.jpg"
                                         alt="Tulip Chair" width="270" height="350">
                                </a>
                                <div class="flash">
                                    <span class="onnew"><span class="text">New</span></span></div>
                                <a href="javascript:void(0);" onclick="buy(this)" class="button yith-wcqv-button" data-product_id="30"
                                   tabindex="0">Acheter</a></div>
                            <div class="product-info">
                                <div class="rating-wapper nostar">
                                    <div class="star-rating"><span style="width:0%">Rated <strong
                                                class="rating">0</strong> out of 5</span></div>
                                    <span class="review">(0)</span></div>
                                <h3 class="product-name product_title">
                                    <a href="#"
                                       tabindex="0">Tulip Chair</a>
                                </h3>
                                <span class="price"><span class="furgan-Price-amount amount"><span
                                            class="furgan-Price-currencySymbol">$</span>60.00</span></span>
                            </div>
                            <div class="group-button clearfix">
                                <div class="yith-wcwl-add-to-wishlist">
                                    <div class="yith-wcwl-add-button show">
                                        <a href="javascript:void(0);" class="add_to_wishlist">Add to Wishlist</a>
                                    </div>
                                </div>
                                <div class="add-to-cart">
                                    <a href="javascript:void(0);" class="button product_type_grouped">
                                        Add to cart</a></div>
                                <div class="furgan product compare-button">
                                    <a href="javascript:void(0);" class="compare button">Compare</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="product-item featured_products style-02 rows-space-30 post-31 product type-product status-publish has-post-thumbnail product_cat-light product_cat-sofas product_tag-hat first instock sale featured shipping-taxable product-type-grouped">
                        <div class="product-inner tooltip-top">
                            <div class="product-thumb">
                                <a class="thumb-link"
                                   href="#"
                                   tabindex="0">
                                    <img class="img-responsive"
                                         src="assets/images/apro91-1-270x350.jpg"
                                         alt="DAX Armchair" width="270" height="350">
                                </a>
                                <div class="flash">
                                    <span class="onnew"><span class="text">New</span></span></div>
                                 <a href="javascript:void(0);" class="button yith-wcqv-button" onclick="buy(this)">Acheter</a></div>
                            <div class="product-info">
                                <div class="rating-wapper nostar">
                                    <div class="star-rating"><span style="width:0%">Rated <strong
                                                class="rating">0</strong> out of 5</span></div>
                                    <span class="review">(0)</span></div>
                                <h3 class="product-name product_title">
                                    <a href="#"
                                       tabindex="0">DAX Armchair</a>
                                </h3>
                                <span class="price"><span class="furgan-Price-amount amount"><span
                                            class="furgan-Price-currencySymbol">$</span>89.00</span> – <span
                                        class="furgan-Price-amount amount"><span
                                            class="furgan-Price-currencySymbol">$</span>139.00</span></span>
                            </div>
                            <div class="group-button clearfix">
                                <div class="yith-wcwl-add-to-wishlist">
                                    <div class="yith-wcwl-add-button show">
                                        <a href="javascript:void(0);" class="add_to_wishlist">Add to Wishlist</a>
                                    </div>
                                </div>
                                <div class="add-to-cart">
                                    <a href="javascript:void(0);" class="button product_type_grouped">
                                        View products</a></div>
                                <div class="furgan product compare-button">
                                    <a href="javascript:void(0);" class="compare button">Compare</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="product-item featured_products style-02 rows-space-30 post-29 product type-product status-publish has-post-thumbnail product_cat-new-arrivals product_cat-specials product_tag-light product_tag-sock  instock featured downloadable shipping-taxable purchasable product-type-simple">
                        <div class="product-inner tooltip-top">
                            <div class="product-thumb">
                                <a class="thumb-link"
                                   href="#"
                                   tabindex="0">
                                    <img class="img-responsive"
                                         src="assets/images/apro1113-270x350.jpg"
                                         alt="Alarm Clock" width="270" height="350">
                                </a>
                                <div class="flash">
                                    <span class="onnew"><span class="text">New</span></span></div>
                                 <a href="javascript:void(0);" class="button yith-wcqv-button" onclick="buy(this)">Acheter</a></div>
                            <div class="product-info">
                                <div class="rating-wapper nostar">
                                    <div class="star-rating"><span style="width:0%">Rated <strong
                                                class="rating">0</strong> out of 5</span></div>
                                    <span class="review">(0)</span></div>
                                <h3 class="product-name product_title">
                                    <a href="#"
                                       tabindex="0">Alarm Clock</a>
                                </h3>
                                <span class="price"><span class="furgan-Price-amount amount"><span
                                            class="furgan-Price-currencySymbol">$</span>129.00</span></span>
                            </div>
                            <div class="group-button clearfix">
                                <div class="yith-wcwl-add-to-wishlist">
                                    <div class="yith-wcwl-add-button show">
                                        <a href="javascript:void(0);" class="add_to_wishlist">Add to Wishlist</a>
                                    </div>
                                </div>
                                <div class="add-to-cart">
                                    <a href="javascript:void(0);" class="button product_type_grouped">
                                        Add to cart</a></div>
                                <div class="furgan product compare-button">
                                    <a href="javascript:void(0);" class="compare button">Compare</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="product-item featured_products style-02 rows-space-30 post-28 product type-product status-publish has-post-thumbnail product_cat-light product_cat-chair product_cat-sofas product_tag-light product_tag-sock last instock sale featured shipping-taxable purchasable product-type-simple">
                        <div class="product-inner tooltip-top">
                            <div class="product-thumb">
                                <a class="thumb-link"
                                   href="#"
                                   tabindex="0">
                                    <img class="img-responsive"
                                         src="assets/images/apro1211-2-270x350.jpg"
                                         alt="Prix Chair" width="270" height="350">
                                </a>
                                <div class="flash">
                                    <span class="onsale"><span class="number">-14%</span></span>
                                    <span class="onnew"><span class="text">New</span></span></div>
                                 <a href="javascript:void(0);" class="button yith-wcqv-button" onclick="buy(this)">Acheter</a></div>
                            <div class="product-info">
                                <div class="rating-wapper ">
                                    <div class="star-rating"><span style="width:100%">Rated <strong
                                                class="rating">5.00</strong> out of 5</span></div>
                                    <span class="review">(1)</span></div>
                                <h3 class="product-name product_title">
                                    <a href="#"
                                       tabindex="0">Prix Chair</a>
                                </h3>
                                <span class="price"><del><span
                                            class="furgan-Price-amount amount"><span
                                                class="furgan-Price-currencySymbol">$</span>138.00</span></del> <ins><span
                                            class="furgan-Price-amount amount"><span
                                                class="furgan-Price-currencySymbol">$</span>119.00</span></ins></span>
                            </div>
                            <div class="group-button clearfix">
                                <div class="yith-wcwl-add-to-wishlist">
                                    <div class="yith-wcwl-add-button show">
                                        <a href="javascript:void(0);" class="add_to_wishlist">Add to Wishlist</a>
                                    </div>
                                </div>
                                <div class="add-to-cart">
                                    <a href="javascript:void(0);" class="button product_type_grouped">
                                        Add to cart</a></div>
                                <div class="furgan product compare-button">
                                    <a href="javascript:void(0);" class="compare button">Compare</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="product-item featured_products style-02 rows-space-30 post-26 product type-product status-publish has-post-thumbnail product_cat-light product_cat-chair product_cat-sofas product_tag-light product_tag-hat first instock featured shipping-taxable product-type-external">
                        <div class="product-inner tooltip-top">
                            <div class="product-thumb">
                                <a class="thumb-link"
                                   href="#"
                                   tabindex="0">
                                    <img class="img-responsive"
                                         src="assets/images/apro141-1-270x350.jpg"
                                         alt="Chair AAC" width="270" height="350">
                                </a>
                                <div class="flash">
                                    <span class="onnew"><span class="text">New</span></span></div>
                                 <a href="javascript:void(0);" class="button yith-wcqv-button" onclick="buy(this)">Acheter</a></div>
                            <div class="product-info">
                                <div class="rating-wapper ">
                                    <div class="star-rating"><span style="width:100%">Rated <strong
                                                class="rating">5.00</strong> out of 5</span></div>
                                    <span class="review">(1)</span></div>
                                <h3 class="product-name product_title">
                                    <a href="#"
                                       tabindex="0">Chair AAC</a>
                                </h3>
                                <span class="price"><span class="furgan-Price-amount amount"><span
                                            class="furgan-Price-currencySymbol">$</span>207.00</span></span>
                            </div>
                            <div class="group-button clearfix">
                                <div class="yith-wcwl-add-to-wishlist">
                                    <div class="yith-wcwl-add-button show">
                                        <a href="javascript:void(0);" class="add_to_wishlist">Add to Wishlist</a>
                                    </div>
                                </div>
                                <div class="add-to-cart">
                                    <a href="javascript:void(0);" class="button product_type_grouped">
                                        Buy it on Amazon</a></div>
                                <div class="furgan product compare-button">
                                    <a href="javascript:void(0);" class="compare button">Compare</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="product-item featured_products style-02 rows-space-30 post-25 product type-product status-publish has-post-thumbnail product_cat-light product_cat-chair product_cat-specials product_tag-light product_tag-sock  instock sale featured shipping-taxable purchasable product-type-simple">
                        <div class="product-inner tooltip-top">
                            <div class="product-thumb">
                                <a class="thumb-link"
                                   href="#"
                                   tabindex="0">
                                    <img class="img-responsive"
                                         src="assets/images/apro151-1-270x350.jpg"
                                         alt="Office Chair" width="270" height="350">
                                </a>
                                <div class="flash">
                                    <span class="onsale"><span class="number">-11%</span></span>
                                    <span class="onnew"><span class="text">New</span></span></div>
                                <a href="javascript:void(0);" class="button yith-wcqv-button" data-product_id="25"
                                   tabindex="0" onclick="buy(this)">Acheter</a></div>
                            <div class="product-info">
                                <div class="rating-wapper nostar">
                                    <div class="star-rating"><span style="width:0%">Rated <strong
                                                class="rating">0</strong> out of 5</span></div>
                                    <span class="review">(0)</span></div>
                                <h3 class="product-name product_title">
                                    <a href="#"
                                       tabindex="0">Office Chair</a>
                                </h3>
                                <span class="price"><del><span
                                            class="furgan-Price-amount amount"><span
                                                class="furgan-Price-currencySymbol">$</span>89.00</span></del> <ins><span
                                            class="furgan-Price-amount amount"><span
                                                class="furgan-Price-currencySymbol">$</span>79.00</span></ins></span>
                            </div>
                            <div class="group-button clearfix">
                                <div class="yith-wcwl-add-to-wishlist">
                                    <div class="yith-wcwl-add-button show">
                                        <a href="javascript:void(0);" class="add_to_wishlist">Add to Wishlist</a>
                                    </div>
                                </div>
                                <div class="add-to-cart">
                                    <a href="javascript:void(0);" class="button product_type_grouped">
                                        Add to cart</a></div>
                                <div class="furgan product compare-button">
                                    <a href="javascript:void(0);" class="compare button">Compare</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="product-item featured_products style-02 rows-space-30 post-24 product type-product status-publish has-post-thumbnail product_cat-chair product_cat-table product_cat-new-arrivals product_tag-light product_tag-hat product_tag-sock last instock featured shipping-taxable purchasable product-type-variable has-default-attributes">
                        <div class="product-inner tooltip-top">
                            <div class="product-thumb">
                                <a class="thumb-link"
                                   href="javascript:void(0);" tabindex="-1">
                                    <img class="img-responsive"
                                         src="assets/images/apro161-1-270x350.jpg"
                                         alt="Moss Sofa" width="270" height="350">
                                </a>
                                <div class="flash">
                                    <span class="onnew"><span class="text">New</span></span></div>
                                <form class="variations_form cart">
                                    <table class="variations">
                                        <tbody>
                                        <tr>
                                            <td class="value">
                                                <select title="box_style" data-attributetype="box_style"
                                                        data-id="pa_color" class="attribute-select "
                                                        name="attribute_pa_color"
                                                        data-attribute_name="attribute_pa_color"
                                                        data-show_option_none="yes" tabindex="-1">
                                                    <option data-type="" data-pa_color="" value="">
                                                        Choose an option
                                                    </option>
                                                    <option data-width="30" data-height="30"
                                                            data-type="color" data-pa_color="#3155e2"
                                                            value="blue">Blue
                                                    </option>
                                                    <option data-width="30" data-height="30"
                                                            data-type="color" data-pa_color="#49aa51"
                                                            value="green">Green
                                                    </option>
                                                    <option data-width="30" data-height="30"
                                                            data-type="color" data-pa_color="#ff63cb"
                                                            value="pink">Pink
                                                    </option>
                                                </select>
                                                <div class="data-val attribute-pa_color"
                                                     data-attributetype="box_style"><a
                                                        class="change-value color" href="#"
                                                        style="background: #3155e2;"
                                                        data-value="blue"></a><a
                                                        class="change-value color" href="#"
                                                        style="background: #49aa51;"
                                                        data-value="green"></a><a
                                                        class="change-value color" href="#"
                                                        style="background: #ff63cb;"
                                                        data-value="pink"></a></div>
                                                <a class="reset_variations" href="#"
                                                   tabindex="-1">Clear</a></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </form>
                                 <a href="javascript:void(0);" class="button yith-wcqv-button" onclick="buy(this)">Acheter</a></div>
                            <div class="product-info">
                                <div class="rating-wapper nostar">
                                    <div class="star-rating"><span style="width:0%">Rated <strong
                                                class="rating">0</strong> out of 5</span></div>
                                    <span class="review">(0)</span></div>
                                <h3 class="product-name product_title">
                                    <a href="#"
                                       tabindex="-1">Moss Sofa</a>
                                </h3>
                                <span class="price"><span class="furgan-Price-amount amount"><span
                                            class="furgan-Price-currencySymbol">$</span>45.00</span> – <span
                                        class="furgan-Price-amount amount"><span
                                            class="furgan-Price-currencySymbol">$</span>54.00</span></span>
                            </div>
                            <div class="group-button clearfix">
                                <div class="yith-wcwl-add-to-wishlist">
                                    <div class="yith-wcwl-add-button show">
                                        <a href="javascript:void(0);" class="add_to_wishlist">Add to Wishlist</a>
                                    </div>
                                </div>
                                <div class="add-to-cart">
                                    <a href="javascript:void(0);" class="button product_type_grouped">
                                        Select options</a></div>
                                <div class="furgan product compare-button">
                                    <a href="javascript:void(0);" class="compare button">Compare</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="product-item featured_products style-02 rows-space-30 post-22 product type-product status-publish has-post-thumbnail product_cat-table product_cat-bed product_cat-lamp product_tag-table product_tag-hat product_tag-sock first instock featured downloadable shipping-taxable purchasable product-type-simple">
                        <div class="product-inner tooltip-top">
                            <div class="product-thumb">
                                <a class="thumb-link"
                                   href="#"
                                   tabindex="-1">
                                    <img class="img-responsive"
                                         src="assets/images/apro181-2-270x350.jpg"
                                         alt="Wall Lamp" width="270" height="350">
                                </a>
                                <div class="flash">
                                    <span class="onnew"><span class="text">New</span></span></div>
                                <a href="javascript:void(0);" class="button yith-wcqv-button" data-product_id="22"
                                   tabindex="-1" onclick="buy(this)">Acheter</a></div>
                            <div class="product-info">
                                <div class="rating-wapper nostar">
                                    <div class="star-rating"><span style="width:0%">Rated <strong
                                                class="rating">0</strong> out of 5</span></div>
                                    <span class="review">(0)</span></div>
                                <h3 class="product-name product_title">
                                    <a href="#"
                                       tabindex="-1">Wall Lamp</a>
                                </h3>
                                <span class="price"><span class="furgan-Price-amount amount"><span
                                            class="furgan-Price-currencySymbol">$</span>98.00</span></span>
                            </div>
                            <div class="group-button clearfix">
                                <div class="yith-wcwl-add-to-wishlist">
                                    <div class="yith-wcwl-add-button show">
                                        <a href="javascript:void(0);" class="add_to_wishlist">Add to cart</a>
                                    </div>
                                </div>
                                <div class="add-to-cart">
                                    <a href="javascript:void(0);" class="button product_type_grouped">
                                        Add to cart</a></div>
                                <div class="furgan product compare-button">
                                    <a href="javascript:void(0);" class="compare button">Compare</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<a href="javascript:void(0);" class="backtotop active">
    <i class="fa fa-angle-double-up"></i>
</a>

<script>
    function buy(btn) {
        //   $('.close-modal').click();
        loader('add');

        setTimeout(function () {
            var selector = $(btn);

            (new PayTech({
                item_id: 1,
            })).withOption({
                requestTokenUrl: '<?php echo BASE_URL ?>/paiement.php',
                method: 'POST',
                headers: {
                    "Accept": "text/html"
                },
                prensentationMode: PayTech.OPEN_IN_POPUP,
                didPopupClosed: function (is_completed, success_url, cancel_url) {
                    window.location.href = is_completed === true ? success_url : cancel_url;
                },
                willGetToken: function () {
                    console.log("Je me prepare a obtenir un token");
                    selector.prop('disabled', true);
                },
                didGetToken: function (token, redirectUrl) {
                    console.log("Mon token est : " + token + ' et url est ' + redirectUrl);
                    selector.prop('disabled', false);
                    loader('remove');
                },
                didReceiveError: function (error) {
                    alert('erreur inconnu', error.toString());
                    selector.prop('disabled', false);
                    loader('remove');
                },
                didReceiveNonSuccessResponse: function (jsonResponse) {
                    console.log('non success response ', jsonResponse);
                    alert(jsonResponse.errors);
                    selector.prop('disabled', false);
                    loader('remove');
                }
            }).send();
        }, 500)

    }
</script>

<script>
    function loader(removeAdd) {
        var html = `<div id="loader" style='z-index:99999999;width:100%;height:100%;position:fixed;top:0;left:0;background:rgba(0,0,0,0.2);'>
	<div style='width:50px;height:50px;margin: 25% auto;'><img src='<?php echo BASE_URL ?>/assets/img/loader.gif' style='width:100%;height:100%'></div>
	  </div>`;
        if (removeAdd === 'add') {
            $("html").append(html);
            //$('.loading-wrap').css('display', 'block');
        }
        else {
            $("#loader").remove();
            //$('.loading-wrap').css('display', 'none');
        }
    }


</script>
<script src="assets/js/jquery-1.12.4.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/chosen.min.js"></script>
<script src="assets/js/countdown.min.js"></script>
<script src="assets/js/jquery.scrollbar.min.js"></script>
<script src="assets/js/lightbox.min.js"></script>
<script src="assets/js/magnific-popup.min.js"></script>
<script src="assets/js/slick.js"></script>
<script src="assets/js/jquery.zoom.min.js"></script>
<script src="assets/js/threesixty.min.js"></script>
<script src="assets/js/jquery-ui.min.js"></script>
<script src="assets/js/mobilemenu.js"></script>
<script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyC3nDHy1dARR-Pa_2jjPCjvsOR4bcILYsM'></script>
<script src="assets/js/functions.js"></script>
</body>
</html>