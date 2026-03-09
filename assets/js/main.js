(function ($) {
  "use strict";
  
  // ==========================================
  //      Start Document Ready function
  // ==========================================
  $(document).ready(function () {
      
  // ============== Mobile Nav Menu Dropdown Js Start =======================
  function toggleSubMenu() {
    if ($(window).width() <= 991) {
      $('.has-submenu').off('click').on('click', function () {
        $(this).toggleClass('active').siblings('.has-submenu').removeClass('active').find('.nav-submenu').slideUp(300);
        $(this).find('.nav-submenu').stop(true, true).slideToggle(300);
      });
    } else {
      $('.has-submenu').off('click'); 
    }
  }

  toggleSubMenu();
  $(window).resize(toggleSubMenu);
  // ============== Mobile Nav Menu Dropdown Js End =======================
    
  // ===================== Scroll Back to Top Js Start ======================
  var progressPath = document.querySelector('.progress-wrap path');
  var pathLength = progressPath.getTotalLength();
  progressPath.style.transition = progressPath.style.WebkitTransition = 'none';
  progressPath.style.strokeDasharray = pathLength + ' ' + pathLength;
  progressPath.style.strokeDashoffset = pathLength;
  progressPath.getBoundingClientRect();
  progressPath.style.transition = progressPath.style.WebkitTransition = 'stroke-dashoffset 10ms linear';
  var updateProgress = function () {
    var scroll = $(window).scrollTop();
    var height = $(document).height() - $(window).height();
    var progress = pathLength - (scroll * pathLength / height);
    progressPath.style.strokeDashoffset = progress;
  }
  updateProgress();
  $(window).scroll(updateProgress);
  var offset = 50;
  var duration = 550;
  jQuery(window).on('scroll', function() {
    if (jQuery(this).scrollTop() > offset) {
      jQuery('.progress-wrap').addClass('active-progress');
    } else {
      jQuery('.progress-wrap').removeClass('active-progress');
    }
  });
  jQuery('.progress-wrap').on('click', function(event) {
    event.preventDefault();
    jQuery('html, body').animate({scrollTop: 0}, duration);
    return false;
  })
  // ===================== Scroll Back to Top Js End ======================

  
  // ========================== add active class to navbar menu current page Js Start =====================
    function dynamicActiveMenuClass(selector) {
      let FileName = window.location.pathname.split("/").reverse()[0];

      // If we are at the root path ("/" or no file name), keep the activePage class on the Home item
      if (FileName === "" || FileName === "index.html") {
        // Keep the activePage class on the Home link
        selector.find("li.nav-menu__item.has-submenu").eq(0).addClass("activePage");
      } else {
        // Remove activePage class from all items first
        selector.find("li").removeClass("activePage");

        // Add activePage class to the correct li based on the current URL
        selector.find("li").each(function () {
          let anchor = $(this).find("a");
          if ($(anchor).attr("href") == FileName) {
            $(this).addClass("activePage");
          }
        });

        // If any li has activePage element, add class to its parent li
        selector.children("li").each(function () {
          if ($(this).find(".activePage").length) {
            $(this).addClass("activePage");
          }
        });
      }
    }

    if ($('ul').length) {
      dynamicActiveMenuClass($('ul'));
    }
    // ========================== add active class to navbar menu current page Js End =====================




    // **************************** Mobile Menu js Start ****************************
    var mmm = gsap.matchMedia(); 
    var mtl = gsap.timeline({paused: true}); 

    const toggleMobileMenu = document.querySelector('.toggle-mobileMenu');
    const closeButton = document.querySelector('.close-button');
    const mobileSideOverlay = document.querySelector('.side-overlay');

    mmm.add("(max-width: 991px)", () => {
      
      mtl.to('.side-overlay', {
        opacity: 1,
        visibility: 'visible',
        duration: .3, 
      });
      
      mtl.to('.mobile-menu', {
        x: 0,
      });
      
      mtl.from('.nav-menu__item', {
        opacity: 0,
        duration: .3,
        y: -60,
        stagger: .12,
      });

      mtl.from('.close-button', {
        opacity: 0,
        scale: 0,
        duration: .2,
      });

      toggleMobileMenu.addEventListener('click', function () {
        mtl.play();
        document.body.style.overflow = 'hidden'
      });

      closeButton.addEventListener('click', function () {
        mtl.reverse();
        document.body.style.overflow = ''
      });

      mobileSideOverlay.addEventListener('click', function () {
        mtl.reverse();
        document.body.style.overflow = ''
      });

    });
    // **************************** Mobile Menu js End ****************************


      // =========================  Search Bar 9 Js Start ==============
      $(".open-search").on("click", function () {
        $(".search_popup").addClass("search-opened");
        $(".search-popup-overlay").addClass("search-popup-overlay-open");
      });
      $(".search_close_btn").on("click", function () {
        $(".search_popup").removeClass("search-opened");
        $(".search-popup-overlay").removeClass("search-popup-overlay-open");
      });
      $(".search-popup-overlay").on("click", function () {
        $(".search_popup").removeClass("search-opened");
        $(this).removeClass("search-popup-overlay-open");
      });
      // =========================  Search Bar 9 Js End ==============




  // ********************* Toast Notification Js start *********************
    function toastMessage(messageType, messageTitle, messageText, messageIcon) {
      let toastContainer = document.querySelector('#toast-container'); 

      let toast = document.createElement('div');
      toast.className = `toast-message ${messageType}`;

      toast.innerHTML = `
          <div class="toast-message__content">
              <span class="toast-message__icon">
                  <i class="${messageIcon}"></i>
              </span>
              <div class="flex-grow-1">
                  <div class="d-flex align-items-start justify-content-between mb-1">
                      <h6 class="toast-message__title">${messageTitle}</h6>
                      <button type="button" class="toast-message__close">
                          <i class="ph-bold ph-x"></i>
                      </button>
                  </div>
                  <span class="toast-message__text">${messageText}</span>
              </div>
          </div>
          <div class="progress__bar"></div>
      `;

      toastContainer.appendChild(toast);
      
      setTimeout(() => {
          toast.classList.add('active');
      }, 50);

      let totalDuration = 3500;
      let startTime = Date.now();
      let remainingTime = totalDuration;
      let toastTimeout = setTimeout(hideToast, remainingTime);

      function hideToast() {
          toast.classList.remove('active');
          setTimeout(() => {
              toast.remove();
          }, 500);
      }

      // Remove Toast
      let closeToast = toast.querySelector('.toast-message__close');
      closeToast.addEventListener('click', function () {
          toast.classList.remove('active');
          setTimeout(() => {
              toast.remove();
          }, 500);
      });
      // Remove Toast


      // Pause Timeout on Hover
      toast.addEventListener('mouseenter', function () {
          remainingTime -= Date.now() - startTime;
          clearTimeout(toastTimeout);
      });

      // Resume Timeout on Mouse Leave
      toast.addEventListener('mouseleave', function () {
          startTime = Date.now();
          toastTimeout = setTimeout(hideToast, remainingTime);
      });
  }
  // ********************* Toast Notification Js End *********************


  // ========================= Delete Item Js start ===================
  let deleteButtons = document.querySelectorAll('.delete-button');

  deleteButtons.forEach(deleteButton => {
    deleteButton.addEventListener('click', function () {
      this.closest('.delete-item').classList.add('d-none');
      toastMessage("danger", "Deleted", "You deleted successfully!", 'ph-bold ph-trash');
    });
  });
  // ========================= Delete Item Js End ===================

  // ========================= Form Submit Js Start ===================
  let formSubmit = document.querySelector('.form-submit');
  let fields = document.querySelectorAll('input');
  let textarea = document.querySelector('textarea');

  if(formSubmit && fields) {
    formSubmit.addEventListener('submit', function (e) {
      e.preventDefault();
      fields.forEach(field => {
        field.value = "";
      });
      if(textarea) {
        textarea.value = "";
      }
      toastMessage("success", "Success", "Form submitted successfully!", 'ph-fill ph-check-circle');
    });
  }
  // ========================= Form Submit Js End ===================


  // ================== Password Show Hide Js Start ==========
  $(".toggle-password").on('click', function() {
    $(this).toggleClass("active");
    var input = $($(this).attr("id"));
    if (input.attr("type") == "password") {
      input.attr("type", "text");
      $(this).removeClass('ph-bold ph-eye-closed');
      $(this).addClass('ph-bold ph-eye');
    } else {
      input.attr("type", "password");
        $(this).addClass('ph-bold ph-eye-closed');
    }
  });
  // ========================= Password Show Hide Js End ===========================

  

  $(document).ready(function() {
    $('select').niceSelect();
  });

  
  // ========================== Add Attribute For Bg Image Js Start ====================
    $(".background-img").css('background', function () {
      var bg = ('url("' + $(this).data("background-image") + '")');
      return bg;
    });
  // ========================== Add Attribute For Bg Image Js End =====================


  });
  // ==========================================
  //      End Document Ready function
  // ==========================================

  // ========================= Preloader Js Start =====================
    var percentage = 0;
      var LoadingCounter = setInterval(function () {
        if (percentage <= 100) {
          // $('#loading-screen ').css('opacity', (100 - percentage));
          $("#loading-screen .loading-counter").text(percentage + "%");
          $("#loading-screen .bar").css("width", (100 - percentage) / 2 + "%");
          $("#loading-screen .progress-line").css("transform", "scale(" + percentage / 100 + ")");
          percentage++;
        } else {
          $("#loading-screen").fadeOut(500);
          setTimeout(() => {
            $("#loading-screen").remove();
          }, 500);
          clearInterval(LoadingCounter);
        }
      }, 10);
    // ========================= Header Sticky Js Start ==============
    $(window).on('scroll', function() {
      if ($(window).scrollTop() >= 260) {
        $('.header').addClass('fixed-header');
      }
      else {
          $('.header').removeClass('fixed-header');
      }
    }); 
    // ========================= Header Sticky Js End===================






    // =========================  Home 1 feature Js Start ==============
      var slider = new Swiper('.feature-active', {
        slidesPerView: 4,
        spaceBetween: 30,
        loop: true,
        speed: 3000,
        autoplay: true,
        breakpoints: {
          '1400': {
            slidesPerView: 4,
          },
          '1200': {
            slidesPerView: 4,
          },
          '992': {
            slidesPerView: 2,
          },
          '768': {
            slidesPerView: 2,
          },
          '576': {
            slidesPerView: 1,
          },
          '0': {
            slidesPerView: 1,
          },
        },
      });
      // =========================  Home 1 feature Js End ==============


      // =========================  Home 2 project Js Start ==============
      var slider = new Swiper('.project-two-active', {
        slidesPerView: 4,
        spaceBetween: 30,
        loop: true,
        speed: 3000,
        autoplay:true,
        centeredSlides: true,
        breakpoints: {
          '1800': {
            slidesPerView: 3.6,
          },
          '1700': {
            slidesPerView: 3.4,
          },
          '1400': {
            slidesPerView: 2.8,
          },
          '1200': {
            slidesPerView: 2.4,
          },
          '992': {
            slidesPerView: 2,
          },
          '768': {
            slidesPerView: 1.7,
          },
          '576': {
            slidesPerView: 1.5,
          },
          '0': {
            slidesPerView: 1,
          },
        },
      });
      // =========================  Home 2 project Js End ==============



      
    // =========================  Home 2 service Js Start ==============
      var slider = new Swiper('.service-two-active', {
        slidesPerView: 3,
        spaceBetween: 30,
        loop: true,
        speed: 3000,
        autoplay: true,
        breakpoints: {
          '1400': {
            slidesPerView: 3,
          },
          '1200': {
            slidesPerView: 3,
          },
          '992': {
            slidesPerView: 2,
          },
          '768': {
            slidesPerView: 2,
          },
          '576': {
            slidesPerView: 1,
          },
          '0': {
            slidesPerView: 1,
          },
        },
      });
      // =========================  Home 2 feature Js End ==============



      // =========================  Home 2 gallery Js Start ==============
      var slider = new Swiper('.gallery-two-active', {
        slidesPerView: 4,
        spaceBetween: 30,
        loop: true,
        speed: 3000,
        autoplay:true,
        centeredSlides: true,
        breakpoints: {
          '1200': {
            slidesPerView: 4,
          },
          '992': {
            slidesPerView: 3,
          },
          '768': {
            slidesPerView: 2,
          },
          '576': {
            slidesPerView: 2,
          },
          '0': {
            slidesPerView: 1,
          },
        },
      });
      // =========================  Home 2 gallery Js End ==============



      // =========================  Home 2 testimonial Js Start ==============
      var slider = new Swiper('.testimonial-two-active', {
        slidesPerView: "auto",
        spaceBetween: 30,
        loop: true,
        speed: 3000,
        autoplay:true,
        breakpoints: {
          '1200': {
            slidesPerView: 2,
          },
          '992': {
            slidesPerView: 1,
          },
          '768': {
            slidesPerView: 1,
          },
          '576': {
            slidesPerView: 1,
          },
          '0': {
            slidesPerView: 1,
          },
        },
        // pagination
        pagination: {
          el: ".testimonial-two-dot",
          clickable: true,
        },
      });
      // =========================  Home 2 testimonial Js End ==============




      // =========================  Home 2 service Js Start ==============
      var slider = new Swiper('.service-three-active', {
        slidesPerView: 3,
        spaceBetween: 30,
        loop: true,
        speed: 3000,
        autoplay: true,
        breakpoints: {
          '1400': {
            slidesPerView: 4,
          },
          '1200': {
            slidesPerView: 4,
          },
          '992': {
            slidesPerView: 3,
          },
          '768': {
            slidesPerView: 2,
          },
          '576': {
            slidesPerView: 1,
          },
          '0': {
            slidesPerView: 1,
          },
        },
      });
      // =========================  Home 2 feature Js End ==============



		// Home 3 Marquee slider Js
    if ($(".testimonail-three-slider-one").length > 0) {
      var swiper = new Swiper(".testimonail-three-slider-one", {
        direction: "vertical", // Set vertical direction
        slidesPerView: "auto",
        spaceBetween: 0,
        loop: true,
        speed: 5000,
        allowTouchMove: false,
        autoplay: {
          delay: 1,
          disableOnInteraction: true,
        },
        breakpoints: {
          1800: {
            slidesPerView: 2,
          },
          1920: {
            slidesPerView: 2.2,
          },
        },
      });
    }

		// Home 3 Marquee 2 Js
    if ($(".testimonail-three-slider-two").length > 0) {
      var swiper = new Swiper(".testimonail-three-slider-two", {
        direction: "vertical", // vertical direction
        slidesPerView: "auto",
        spaceBetween: 0,
        loop: true,
        speed: 5000,
        allowTouchMove: false,
        autoplay: {
          delay: 1,
          disableOnInteraction: true,
          reverseDirection: true,  // <-- Add here as well
        },
        breakpoints: {
          1800: {
            slidesPerView: 2,
          },
          1920: {
            slidesPerView: 2.2,
          },
        },
      });
    }


    // =========================  Home 2 catagori Js Start ==============
      var slider = new Swiper('.catagori-three-active', {
        slidesPerView: 3,
        spaceBetween: 30,
        loop: true,
        speed: 3000,
        autoplay: true,
        breakpoints: {
          '1400': {
            slidesPerView: 5,
          },
          '1200': {
            slidesPerView: 4,
          },
          '992': {
            slidesPerView: 3,
          },
          '768': {
            slidesPerView: 2,
          },
          '576': {
            slidesPerView: 1,
          },
          '0': {
            slidesPerView: 1,
          },
        },
      });
      // =========================  Home 2 catagori Js End ==============






    // ================================ Floating Progress js start =================================
      const progressContainers = document.querySelectorAll('.progress-container');

      function setPercentage(progressContainer) {
          const percentage = progressContainer.getAttribute('data-percentage') + '%';
          
          const progressEl = progressContainer.querySelector('.progress');
          const percentageEl = progressContainer.querySelector('.percentage');
          
          progressEl.style.width = percentage;
          percentageEl.innerText = percentage;
          percentageEl.style.insetInlineStart = percentage;
      }

      // Intersection Observer to trigger progress animation when section is in view
      const observer = new IntersectionObserver(entries => {
          entries.forEach(entry => {
              if (entry.isIntersecting) {
                  // Element is in view, start the progress animation
                  const progressContainer = entry.target;
                  setPercentage(progressContainer);
                  progressContainer.querySelector('.progress').classList.remove('active');
                  progressContainer.querySelector('.percentage').classList.remove('active');
                  observer.unobserve(progressContainer); // Stop observing once animation is triggered
              }
          });
      }, {
          threshold: 0.5 // Adjust this value as needed (0.5 means half the section needs to be visible)
      });

      // Start observing all progress containers
      progressContainers.forEach(progressContainer => {
          observer.observe(progressContainer);
    });
    // ================================ Floating Progress js End =================================

    window.onload = function () {
      var $container = $(".masonry");
      $container.imagesLoaded(function () {
        $container.masonry({
          percentPosition: true
        });
      });
    };



    // Mouse active
    $(document).ready(function () {
        $(
        ".faq-ip-two-item"
        ).on("mouseenter", function () {
        $(this).addClass("active").siblings().removeClass("active");
        });
        $(
        ".faq-ip-two-item"
        ).on("mouseenter", function () {
        $(this).addClass("active");
        $(this)
            .parent()
            .siblings()
            .find(
            ".faq-ip-two-item"
            )
            .removeClass("active");
        });
    });


    // 21. Ecommerce Cart Js
    function tw_ecommerce() {
      $('.tw-cart-minus').on('click', function () {
        var $input = $(this).parent().find('input');
        var count = Number($input.val()) - 1;
        count = count < 1 ? 1 : count;
        $input.val(count);
        $input.change();
        return false;
      });
    
      $('.tw-cart-plus').on('click', function () {
        var $input = $(this).parent().find('input');
        $input.val(Number($input.val()) + 1);
        $input.change();
        return false;
      });


      $("#slider-range").slider({
          range: true,
          min: 0,
          max: 500,
          values: [75, 300],
          slide: function (event, ui) {
            $("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
          }
        });
        $("#amount").val("$" + $("#slider-range").slider("values", 0) +
          " - $" + $("#slider-range").slider("values", 1));
      
      }
      tw_ecommerce();




      // =========================  Building 10 Js Start ==============
      var slider = new Swiper('.package-details-active', {
        slidesPerView: "auto",
        spaceBetween: 30,
        loop: true,
        speed: 2500,
        autoplay: true,
        breakpoints: {
          '1200': {
            slidesPerView: 3,
          },
          '992': {
            slidesPerView: 3,
          },
          '768': {
            slidesPerView: 2,
          },
          '576': {
            slidesPerView: 1,
          },
          '0': {
            slidesPerView: 1,
          },
        },
		    // Navigation arrows
        navigation: {
          nextEl: '.slider-next',
          prevEl: '.slider-prev',
        },
      });
      // =========================  Building 10 Js End ==============




      // =========================  knob Js End ==============
      if (typeof ($.fn.knob) != 'undefined') {
        $('.knob').each(function () {
        var $this = $(this),
        knobVal = $this.attr('data-rel');

        $this.knob({
        'draw': function () {
          $(this.i).val(this.cv + '%')
        }
        });

        $this.appear(function () {
        $({
          value: 0
        }).animate({
          value: knobVal
        }, {
          duration: 2000,
          easing: 'swing',
          step: function () {
          $this.val(Math.ceil(this.value)).trigger('change');
          }
        });
        }, {
        accX: 0,
        accY: -150,
        });
      });
    }
    // =========================  Building Js End ==============
















    // Counter
    new PureCounter();
    new PureCounter({
        filesizing: true,
        selector: ".filesizecount",
        pulse: 2,
    });


    // Wow Js
    new WOW().init();


    // AOS Js
    AOS.init();


})(jQuery);
