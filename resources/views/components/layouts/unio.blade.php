<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">

    <!-- Page Title-->
    <title>{{ $title ?? config('app.name') }}e</title>

    <!-- Meta Tags-->
    <meta name="description"
          content="Unio - colorful and stylish coming soon & landing page template to kick-start your project">
    <meta name="keywords"
          content="mix_design, coming soon, under construction, template, landing page, portfolio, one page, responsive, html5, css3, creative, clean, agency, personal page">
    <meta name="author" content="mix_design">

    <!-- Viewport Meta-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Template Favicon & Icons Start -->
    <link rel="icon" href="{{ asset('frontend/unio/img/favicon/favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('frontend/unio/img/favicon/icon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('frontend/unio/img/favicon/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('frontend/unio/img/favicon/manifest.webmanifest') }}">
    <!-- Template Favicon & Icons End -->

    <!-- Facebook Metadata Start -->
    <meta property="og:image:height" content="1200">
    <meta property="og:image:width" content="1200">
    <meta property="og:title" content="Unio - Coming Soon & Landing Page Template">
    <meta property="og:description"
          content="Unio - colorful and stylish coming soon & landing page template to kick-start your project">
    <meta property="og:url" content="http://mixdesign.club/themeforest/unio/">
    <meta property="og:image" content="http://mixdesign.club/themeforest/unio/favicon/og-image.jpg">
    <!-- Facebook Metadata End -->

    <!-- Template Styles Start -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/unio/css/loaders/loader.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/unio/css/plugins.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/unio/css/main.css') }}">
    <!-- Template Styles End -->

    <!-- Custom Browser Color Start -->
    <meta name="theme-color" content="#fc466b">
    <meta name="msapplication-navbutton-color" content="#fc466b">
    <meta name="apple-mobile-web-app-status-bar-style" content="#fc466b">
    <!-- Custom Browser Color End -->

</head>

<body>

<!-- Loader Start -->
<div class="loader">
    <div class="loader__container">
        <div class="loader-content">
            <div class="loader-logo fadeIn">
                <!-- Your Logo Here -->
                <img src="{{ asset('frontend/running/Logo_8.png') }}" alt="Unio - Coming Soon & Landing Page Template">
            </div>
        </div>
    </div>
    <div class="loader__darkscreen"></div>
</div>
<!-- Loader End -->

<!-- Header Start -->
<header class="header">

    <!-- Logo Start -->
    <div class="header__logo">
        <!-- Your Logo Here -->
        <img src="{{ asset('frontend/unio/img/unio-logo.svg') }}" alt="Unio - Coming Soon & Landing
        Page Template">
    </div>
    <!-- Logo End -->

    <!-- Menu Trigger Start -->
    <div class="menu-button">
        <a href="#0" id="menu-trigger" class="menu-trigger"><span></span></a>
    </div>
    <!-- Menu Trigger End -->

    <!-- Desktop Socials Start -->
    <div class="socials socials-desktop">
        <ul>
            <li>
                <a href="https://www.facebook.com/" target="_blank">Fb</a>
            </li>
            <li>
                <a href="https://www.instagram.com/" target="_blank">In</a>
            </li>
            <li>
                <a href="https://twitter.com/" target="_blank">Tw</a>
            </li>
        </ul>
    </div>
    <!-- Desktop Socials End -->

</header>
<!-- Header End -->

<!-- Navigation Start -->
<nav id="menu" class="menu">
    <div class="container-fluid p-0 fullheight">
        <div class="row g-0 fullheight">

            <!-- Menu Navigation Block Start -->
            <div class="col-12 col-xl-6 menu__navigation">
                <div class="navigation-container">
                    <!-- Navigation links -->
                    <ul class="navigation">
                        <li>
                            <a href="#" id="home-trigger" class="navigation__item">
                                <span class="subtitle">Welcome</span>
                                <span class="link">Home</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="about-trigger" class="navigation__item">
                                <span class="subtitle">Something new</span>
                                <span class="link">About us</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="portfolio-trigger" class="navigation__item">
                                <span class="subtitle">Our creative</span>
                                <span class="link">Portfolio</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="contact-trigger" class="navigation__item">
                                <span class="subtitle">Nice to meet you</span>
                                <span class="link">Contact</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Menu Navigation Block End -->

            <!-- Menu Media Block Start -->
            <div class="col-12 col-xl-6 menu__media">
                <div class="container-fluid p-0 fullheight-mobile">
                    <div class="row g-0 fullheight-mobile">
                        <!-- Menu Images -->
                        <div class="col-2 fullheight media-object">
                            <div class="menu-image menu-image-1"></div>
                        </div>
                        <div class="col-2 fullheight media-object">
                            <div class="menu-image menu-image-2"></div>
                        </div>
                        <div class="col-2 fullheight media-object">
                            <div class="menu-image menu-image-3"></div>
                        </div>
                        <div class="col-2 fullheight media-object">
                            <div class="menu-image menu-image-4"></div>
                        </div>
                        <div class="col-2 fullheight media-object">
                            <div class="menu-image menu-image-5"></div>
                        </div>
                        <div class="col-2 fullheight media-object">
                            <div class="menu-image menu-image-6"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Menu Media Block End -->

        </div>
    </div>
</nav>
<!-- Navigation End -->

<!-- Main Section Start -->
<section id="main" class="main">

    <!-- Main Section Content Start -->
    {{ $slot }}
    <!-- Main Section Content End -->

</section>
<!-- Main Section End -->

<!-- About Section Start -->
<section id="about" class="inner about">
    <div class="container-fluid p-0 fullheight">
        <div class="row g-0 fullheight">

            <!-- About Section Content Start -->
            <div class="col-12 col-xl-6 inner__content">
                <div class="blocks-container">

                    <!-- Section Content Block Title Start -->
                    <div class="content-block">
                        <span class="subtitle">About Us</span>
                        <h2>Just awesome<br>template</h2>
                        <p class="section-text">I wonder if I've been changed in the night? Let me think. Was I the same
                            when I got up this morning?
                            I almost think I can remember feeling a little different. But if I'm not the same, the next
                            question is 'Who in the world am I?' Ah, that's the great puzzle!</p>
                    </div>
                    <!-- Section Content Block Title End -->

                    <!-- Section Content Block Skillbars Start -->
                    <div class="content-block">
                        <!-- Skillbar single item -->
                        <div class="show-skillbar">
                            <div class="skillbar" data-percent="96">
                                <span class="skillbar-title">Design</span>
                                <p class="skillbar-bar"></p>
                                <span class="skill-bar-percent"></span>
                            </div>
                        </div>
                        <!-- Skillbar single item -->
                        <div class="show-skillbar">
                            <div class="skillbar" data-percent="84">
                                <span class="skillbar-title">Branding</span>
                                <p class="skillbar-bar"></p>
                                <span class="skill-bar-percent"></span>
                            </div>
                        </div>
                        <!-- Skillbar single item -->
                        <div class="show-skillbar">
                            <div class="skillbar" data-percent="90">
                                <span class="skillbar-title">Marketing</span>
                                <p class="skillbar-bar"></p>
                                <span class="skill-bar-percent"></span>
                            </div>
                        </div>
                    </div>
                    <!-- Section Content Block Skillbars End -->

                </div>
            </div>
            <!-- About Section Content End -->

            <!-- About Section Media Start -->
            <div class="col-12 col-xl-6 inner__media">
                <div class="media-container">

                    <!-- About Image Start -->
                    <div class="image about-image"></div>
                    <!-- About Image End -->

                </div>
            </div>
            <!-- About Section Media End -->

        </div>
    </div>
</section>
<!-- About Section End -->

<!-- Portfolio Section Start -->
<section id="portfolio" class="inner portfolio">
    <div class="container-fluid p-0 fullheight">
        <div class="row g-0 fullheight">

            <!-- Portfolio Section Content Start -->
            <div class="col-12 col-xl-6 inner__content">
                <div class="blocks-container">

                    <!-- Section Content Block Title Start -->
                    <div class="content-block">
                        <span class="subtitle">Portfolio</span>
                        <h2>Featured projects</h2>
                        <p class="section-text">Be what you would seem to be – or, if you’d like it put more simply –
                            never imagine yourself not to be otherwise than what it might
                            appear to others that what you were or might have been was not otherwise than what you had
                            been would have appeared to them to be otherwise.</p>
                    </div>
                    <!-- Section Content Block Title End -->

                    <!-- Section Content Block Features Start -->
                    <div class="content-block grid-block">
                        <div class="container-fluid px-4">
                            <div class="row gx-5">
                                <!-- Feature single item -->
                                <div class="col-12 col-sm-6 features__item">
                                    <img src="https://dummyimage.com/54x54/464646/8d8d8d" alt="Unio Template Feature">
                                    <h4>Eye catching<br>design</h4>
                                </div>
                                <!-- Feature single item -->
                                <div class="col-12 col-sm-6 features__item">
                                    <img src="https://dummyimage.com/54x54/464646/8d8d8d" alt="Unio Template Feature">
                                    <h4>Trendy colors<br>and fonts</h4>
                                </div>
                                <!-- Feature single item -->
                                <div class="col-12 col-sm-6 features__item">
                                    <img src="https://dummyimage.com/54x54/464646/8d8d8d" alt="Unio Template Feature">
                                    <h4>Custom<br>Google Map</h4>
                                </div>
                                <!-- Feature single item -->
                                <div class="col-12 col-sm-6 features__item">
                                    <img src="https://dummyimage.com/54x54/464646/8d8d8d" alt="Unio Template Feature">
                                    <h4>Ready-to-use<br>contact form</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Section Content Block Features End -->

                </div>
            </div>
            <!-- Portfolio Section Content End -->

            <!-- Portfolio Section Media Start -->
            <div class="col-12 col-xl-6 inner__media">
                <div class="media-container">

                    <!-- Works Gallery Start -->
                    <div class="container-fluid p-0 works__gallery">
                        <div class="row g-0 my-gallery" itemscope itemtype="http://schema.org/ImageGallery">
                            <!-- gallery single item -->
                            <figure class="col-12 col-sm-6 my-gallery__item" itemprop="associatedMedia" itemscope
                                    itemtype="http://schema.org/ImageObject">
                                <a href="https://dummyimage.com/900x1200/383838/8d8d8d"
                                   data-image="https://dummyimage.com/900x1200/383838/8d8d8d" class="my-gallery__link"
                                   itemprop="contentUrl" data-size="900x1200">
                                    <img src="https://dummyimage.com/900x1200/383838/8d8d8d" class="my-gallery__image"
                                         itemprop="thumbnail" alt="Image description"/>
                                </a>
                                <figcaption class="my-gallery__description" itemprop="caption description">
                                    <h3>Mobile app
                                        <small>Creative</small>
                                    </h3>
                                    <p>Mauris porttitor lobortis ligula, quis molestie lorem scelerisque eu. Morbi
                                        aliquam enim odio, a mollis ipsum tristique eu.</p>
                                </figcaption>
                            </figure>
                            <!-- gallery single item -->
                            <figure class="col-12 col-sm-6 my-gallery__item" itemprop="associatedMedia" itemscope
                                    itemtype="http://schema.org/ImageObject">
                                <a href="https://dummyimage.com/900x1200/383838/8d8d8d"
                                   data-image="https://dummyimage.com/900x1200/383838/8d8d8d" class="my-gallery__link"
                                   itemprop="contentUrl" data-size="900x1200">
                                    <img src="https://dummyimage.com/900x1200/383838/8d8d8d" class="my-gallery__image"
                                         itemprop="thumbnail" alt="Image description"/>
                                </a>
                                <figcaption class="my-gallery__description" itemprop="caption description">
                                    <h3>Urban landscape
                                        <small>Photography, illustration</small>
                                    </h3>
                                    <p>Mauris porttitor lobortis ligula, quis molestie lorem scelerisque eu.</p>
                                </figcaption>
                            </figure>
                            <!-- gallery single item -->
                            <figure class="col-12 col-sm-6 my-gallery__item" itemprop="associatedMedia" itemscope
                                    itemtype="http://schema.org/ImageObject">
                                <a href="https://dummyimage.com/900x1200/383838/8d8d8d"
                                   data-image="https://dummyimage.com/900x1200/383838/8d8d8d" class="my-gallery__link"
                                   itemprop="contentUrl" data-size="900x1200">
                                    <img src="https://dummyimage.com/900x1200/383838/8d8d8d" class="my-gallery__image"
                                         itemprop="thumbnail" alt="Image description"/>
                                </a>
                                <figcaption class="my-gallery__description" itemprop="caption description">
                                    <h3>Just stylish brand
                                        <small>Fashion</small>
                                    </h3>
                                    <p>Mauris porttitor lobortis ligula, quis molestie lorem scelerisque eu. Morbi
                                        aliquam enim odio, a mollis ipsum tristique eu.</p>
                                </figcaption>
                            </figure>
                            <!-- gallery single item -->
                            <figure class="col-12 col-sm-6 my-gallery__item" itemprop="associatedMedia" itemscope
                                    itemtype="http://schema.org/ImageObject">
                                <a href="https://dummyimage.com/900x1200/383838/8d8d8d"
                                   data-image="https://dummyimage.com/900x1200/383838/8d8d8d" class="my-gallery__link"
                                   itemprop="contentUrl" data-size="900x1200">
                                    <img src="https://dummyimage.com/900x1200/383838/8d8d8d" class="my-gallery__image"
                                         itemprop="thumbnail" alt="Image description"/>
                                </a>
                                <figcaption class="my-gallery__description" itemprop="caption description">
                                    <h3>Architecture website
                                        <small>Web Design</small>
                                    </h3>
                                    <p>Mauris porttitor lobortis ligula, quis molestie lorem scelerisque eu. Morbi
                                        aliquam enim odio, a mollis ipsum tristique eu.</p>
                                </figcaption>
                            </figure>
                        </div>
                    </div>
                    <!-- Works Gallery End -->

                </div>
            </div>
            <!-- Portfolio Section Media End -->

        </div>
    </div>
</section>
<!-- Portfolio Section End -->

<!-- Contact Section Start -->
<section id="contact" class="inner contact">
    <div class="container-fluid p-0 fullheight">
        <div class="row g-0 fullheight">

            <!-- Contact Section Content Start -->
            <div class="col-12 col-xl-6 inner__content">
                <div class="blocks-container">

                    <!-- Section Content Block Title Start -->
                    <div class="content-block">
                        <span class="subtitle">Contact</span>
                        <h2>Welcome to our new office</h2>
                        <p class="section-text">Our website is under construction but we are ready to go! You can call
                            us or leave a request here.
                            We are always glad to see you in our office from <span>9:00</span> to <span>18:00</span>.
                        </p>
                    </div>
                    <!-- Section Content Block Title End -->

                    <!-- Section Content Block Contact Data Start -->
                    <div class="content-block grid-block contact__data">
                        <div class="container-fluid px-4">
                            <div class="row gx-5">
                                <!-- Contact data single item -->
                                <div class="col-12 col-sm-6 contact-data__item">
                                    <h5>Location</h5>
                                    <p>
                                        11 West 53 Street
                                        <br>New York, NY
                                        <br>10019
                                    </p>
                                </div>
                                <!-- Contact data single item -->
                                <div class="col-12 col-sm-6 contact-data__item">
                                    <h5>Follow us</h5>
                                    <ul>
                                        <li>
                                            <a href="https://www.facebook.com/" target="_blank">Facebook</a>
                                        </li>
                                        <li>
                                            <a href="https://www.instagram.com/" target="_blank">Instagram</a>
                                        </li>
                                        <li>
                                            <a href="https://twitter.com/" target="_blank">Twitter</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row gx-5">
                                <!-- Contact data single item -->
                                <div class="col-12 col-sm-6 contact-data__item">
                                    <h5>Phone</h5>
                                    <p>
                                        <a href="tel:+12127089400">+1 212-708-9400</a>
                                    </p>
                                </div>
                                <!-- Contact data single item -->
                                <div class="col-12 col-sm-6 contact-data__item">
                                    <h5>Email</h5>
                                    <p>
                                        <a href="mailto:example@example.com?subject=Message%20from%20your%20site">example@example.com</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Section Content Block - Contact Data - End -->

                    <!-- Section Content Block Contact Buttons Start -->
                    <div class="content-block contact-buttons">
                        <a href="#" id="writealine-trigger" class="btn btn-l btn-gradient">
                            <span class="btn-border"></span>
                            <span class="btn-hover"></span>
                            <span class="btn-caption">Say Hello!</span>
                        </a>
                    </div>
                    <!-- Section Content Block Contact Buttons End -->

                </div>

                <!-- Write a Line Popup Start -->
                <div id="writealine" class="writealine popup">

                    <!-- Popup Controls Start -->
                    <div class="popup__controls">
                        <div class="section-button">
                            <a href="#0" id="writealine-close" class="section-close"></a>
                        </div>
                    </div>
                    <!-- Popup Controls End -->

                    <!-- Popup Content Start -->
                    <div class="popup__content">

                        <!-- Popup Title Start -->
                        <div class="content-block popup-title">
                            <span class="popup-title__subtitle">Write a line</span>
                            <p class="popup-title__title">Just say Hello!</p>
                        </div>
                        <!-- Popup Title End -->

                        <!-- Popup Content Block Start -->
                        <div class="content-block">
                            <div class="form-container">

                                <!-- Write a Line Form Reply Group Start -->
                                <div class="reply-group">
                                    <i class="fa-solid fa-circle-check reply-group__icon"></i>
                                    <p class="reply-group__title">Done!</p>
                                    <span class="reply-group__text">Thanks for your message. We will get back as soon as possible.</span>
                                </div>
                                <!-- Write a Line Form Reply Group End -->

                                <!-- Write a Line Form Start -->
                                <form class="form writealine-form form-light" id="writealine-form">
                                    <!-- Hidden Required Fields -->
                                    <input type="hidden" name="project_name"
                                           value="Unio - Coming Soon & Landing Page Template">
                                    <input type="hidden" name="admin_email" value="support@mixdesign.club">
                                    <input type="hidden" name="form_subject" value="Write a Line Form Message">
                                    <!-- END Hidden Required Fields-->
                                    <input class="input-1" type="text" name="Name" placeholder="Your Name*" required>
                                    <input class="input-2" type="email" name="E-mail" placeholder="Email Adress*"
                                           required>
                                    <textarea class="" name="Message" placeholder="A Few Words*" required></textarea>
                                    <span class="inputs-description">*Required fields</span>
                                    <button class="btn btn-l btn-gradient">
                                        <span class="btn-border"></span>
                                        <span class="btn-hover"></span>
                                        <span class="btn-caption">Send</span>
                                    </button>
                                </form>
                                <!-- Write a Line Form End  -->

                            </div>
                        </div>
                        <!-- Popup Content Block End -->

                    </div>
                    <!-- Popup Content End -->

                </div>
                <!-- Write a Line Popup End -->

            </div>
            <!-- Contact Section Content End -->

            <!-- Contact Section Media Start -->
            <div class="col-12 col-xl-6 inner__media">
                <div class="media-container">

                    <!-- Google Map Start -->
                    <div class="map">
                        <div id="google-map">
                            <div id="google-container"></div>
                            <div id="zoom-in">
                                <span class="btn-border"></span>
                                <span class="btn-hover"></span>
                                <span class="fa-solid fa-plus btn-icon"></span>
                            </div>
                            <div id="zoom-out">
                                <span class="btn-border"></span>
                                <span class="btn-hover"></span>
                                <span class="fa-solid fa-minus btn-icon"></span>
                            </div>
                        </div>
                    </div>
                    <!-- Google Map End -->

                </div>
            </div>
            <!-- Contact Section Media End -->

        </div>
    </div>
</section>
<!-- Contact Section End -->

<!-- Root element of PhotoSwipe. Must have class pswp. -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

    <!-- Background of PhotoSwipe.
    It's a separate element, as animating opacity is faster than rgba(). -->
    <div class="pswp__bg"></div>

    <!-- Slides wrapper with overflow:hidden. -->
    <div class="pswp__scroll-wrap">

        <!-- Container that holds slides. PhotoSwipe keeps only 3 slides in DOM to save memory. -->
        <!-- don't modify these 3 pswp__item elements, data is added later on. -->
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>

        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
        <div class="pswp__ui pswp__ui--hidden">

            <div class="pswp__top-bar">

                <!--  Controls are self-explanatory. Order can be changed. -->

                <div class="pswp__counter"></div>

                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                <button class="pswp__button pswp__button--share" title="Share"></button>

                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                <!-- element will get class pswp__preloader-active when preloader is running -->
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                        <div class="pswp__preloader__cut">
                            <div class="pswp__preloader__donut"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div>
            </div>

            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>

            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>

            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>

        </div>

    </div>

</div>

<!-- Load Scripts Start -->
<script src="{{ asset('frontend/unio/js/libs.min.js') }}"></script>
<script src="{{ asset('frontend/unio/js/gallery-init.js') }}"></script>
<script src="{{ asset('frontend/unio/js/custom.js') }}"></script>
<script src="{{ asset('frontend/unio/js/maps/map.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDNMDtmEsvSevM4ztfsbhLfLNZhKHCvWXk"></script>
<!-- Load Scripts End -->

</body>

</html>
