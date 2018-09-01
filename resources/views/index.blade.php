@php
$config = [
    'appName' => config('app.name'),
    'locale' => $locale = app()->getLocale(),
    'locales' => config('app.locales'),
    'githubAuth' => config('services.github.client_id'),
];

$polyfills = [
    'Promise',
    'Object.assign',
    'Object.values',
    'Array.prototype.find',
    'Array.prototype.findIndex',
    'Array.prototype.includes',
    'String.prototype.includes',
    'String.prototype.startsWith',
    'String.prototype.endsWith',
];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="generator" content="Mobirise v4.7.2, mobirise.com">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <link rel="shortcut icon" href="assets/images/s3.amazonaws.com-upload.uxpin-files-867974-860361-codespace-logo-1525308909395-a043ca-450x120.png" type="image/x-icon">
  <meta name="description" content="">
  <title>{{ config('app.name') }}</title>


  <link rel="stylesheet" href="{{ mix('css/app.css') }}">

  <link rel="stylesheet" href="assets/web/assets/mobirise-icons-bold/mobirise-icons-bold.css">
  <link rel="stylesheet" href="assets/web/assets/mobirise-icons/mobirise-icons.css">
  <link rel="stylesheet" href="assets/tether/tether.min.css">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-grid.min.css">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-reboot.min.css">
  <link rel="stylesheet" href="assets/dropdown/css/style.css">
  <link rel="stylesheet" href="assets/socicon/css/styles.css">
  <link rel="stylesheet" href="assets/theme/css/style.css">
  <link rel="stylesheet" href="assets/gallery/style.css">
  <link rel="stylesheet" href="assets/mobirise/css/mbr-additional.css">

</head>
<body>
  <header role="banner">
    <section class="menu cid-qZDAwAEdSQ" once="menu" id="menu1-au">
      <nav class="navbar navbar-expand beta-menu navbar-dropdown align-items-center navbar-fixed-top navbar-toggleable-sm">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
          </div>
        </button>
        <div class="menu-logo">
          <div class="navbar-brand">
            <span class="navbar-logo">
              <a href="https://learncode.com.au">
                <img src="assets/images/s3.amazonaws.com-upload.uxpin-files-867974-860361-codespace-logo-1525308909395-a043ca-450x120.png" alt="Mobirise" title="" style="height: 3.8rem;">
              </a>
            </span>
          </div>
        </div>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav nav-dropdown" data-app-modern-menu="true">
            <li class="nav-item">
              <a class="nav-link link text-black display-4" href="index.html">
                <span class="mbri-home mbr-iconfont mbr-iconfont-btn"></span>
                Home
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link link text-black display-4" href="about.html">
                <span class="mbri-hot-cup mbr-iconfont mbr-iconfont-btn"></span>
                About
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link link text-black display-4" href="contact.html">
                <span class="mbrib-contact-form mbr-iconfont mbr-iconfont-btn"></span>
                Contact
              </a>
            </li>
          </ul>
          <div class="navbar-buttons mbr-section-btn">
            <a class="btn btn-sm btn-primary display-4" href="https://learncode.com.au/camps/">
              <span class="mbrib-rocket mbr-iconfont mbr-iconfont-btn"></span>
              Find a Camp
            </a>
          </div>
        </div>
      </nav>
    </section>
  </header>
  <main role="main">
    <div id="app"></div>
  </main>
  <footer role="contentinfo">
    <section class="cid-qZDAwCTL4j" id="footer1-az">
      <div class="container">
        <div class="media-container-row content text-white">
          <div class="col-12 col-md-3">
            <div class="media-wrap">
              <a href="https://learncode.com.au">
                <img src="assets/images/justlogobig-copy-170x136.png" alt="CodeSpace Education" title="CodeSpace Education">
              </a>
            </div>
          </div>
          <div class="col-12 col-md-3 mbr-fonts-style display-7">
            <h5 class="pb-3">Company Details</h5>
            <p class="mbr-text">CodeSpace Education Services Pty. Ltd.<br>ABN:&nbsp;34 615 494 267<br>Sydney, NSW, Australia </p>
          </div>
          <div class="col-12 col-md-3 mbr-fonts-style display-7">
            <h5 class="pb-3">Contact</h5>
            <p class="mbr-text">Email: info@learncode.com.au &nbsp; &nbsp;<br>Phone: (02) 8806 3750<br></p>
          </div>
          <div class="col-12 col-md-3 mbr-fonts-style display-7">
            <h5 class="pb-3">Links</h5>
            <p class="mbr-text">
              <a href="https://learncode.com.au/blog/careers/">Careers</a><br>
              <a href="https://learncode.com.au/blog/">Blog</a><br>
              <a href="https://learncode.com.au/blog/faq/">FAQ</a><br>
              <a href="https://learncode.com.au/blog/sitemap/">Sitemap</a><br>
              <a href="https://learncode.com.au/camps/workshops/">Workshops</a><br>
              <a href="https://learncode.com.au/camps/">Find a Camp</a><br>
              <a href="https://learncode.com.au/camps/locations/">Locations</a><br>
              <a href="https://staff.learncode.com.au/">Staff Login</a>
            </p>
          </div>
        </div>
        <div class="footer-lower">
          <div class="media-container-row">
            <div class="col-sm-12">
              <hr>
            </div>
          </div>
          <div class="media-container-row mbr-white">
            <div class="col-sm-6 copyright">
              <p class="mbr-text mbr-fonts-style display-7">
                © Copyright 2018 CodeSpace Education - All Rights Reserved
              </p>
            </div>
            <div class="col-md-6"></div>
          </div>
        </div>
      </div>
    </section>
  </footer>
  {{-- Global configuration object --}}
  <script>window.config = @json($config);</script>

  {{-- Polyfill JS features via polyfill.io --}}
  <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features={{ implode(',', $polyfills) }}"></script>

  {{-- Load the application scripts --}}
  @if (app()->isLocal())
    <script src="{{ mix('js/app.js') }}"></script>
  @else
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
  @endif

  <script src="assets/web/assets/jquery/jquery.min.js"></script>
  <script src="assets/popper/popper.min.js"></script>
  <script src="assets/tether/tether.min.js"></script>
  <script src="assets/bootstrap/js/bootstrap.min.js"></script>

  <script src="assets/parallax/jarallax.min.js"></script>
  <script src="assets/mbr-tabs/mbr-tabs.js"></script>
  <script src="assets/masonry/masonry.pkgd.min.js"></script>
  <script src="assets/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/smoothscroll/smooth-scroll.js"></script>
  <script src="assets/vimeoplayer/jquery.mb.vimeo_player.js"></script>
  <script src="assets/touchswipe/jquery.touch-swipe.min.js"></script>
  <script src="assets/mbr-switch-arrow/mbr-switch-arrow.js"></script>
  <script src="assets/dropdown/js/script.min.js"></script>
  <script src="assets/bootstrapcarouselswipe/bootstrap-carousel-swipe.js"></script>
  <script src="assets/theme/js/script.js"></script>
  <script src="assets/slidervideo/script.js"></script>
  <script src="assets/gallery/player.min.js"></script>
  <script src="assets/gallery/script.js"></script>
  <script src="assets/formoid/formoid.min.js"></script>
</body>
</html>
