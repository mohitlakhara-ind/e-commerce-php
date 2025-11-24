(function ($) {
  'use strict';

  // Preloader
  $(window).on('load', function () {
    $('#preloader').fadeOut('slow', function () {
      $(this).remove();
    });
  });



  // e-commerce touchspin
  $('input[name=\'product-quantity\']').TouchSpin();


  // Video Lightbox
  $(document).on('click', '[data-toggle="lightbox"]', function (event) {
    event.preventDefault();
    $(this).ekkoLightbox();
  });



  //Hero Slider
  $('.hero-slider').slick({
    // autoplay: true,
    infinite: true,
    arrows: true,
    prevArrow: '<button type=\'button\' class=\'heroSliderArrow prevArrow tf-ion-chevron-left\'></button>',
    nextArrow: '<button type=\'button\' class=\'heroSliderArrow nextArrow tf-ion-chevron-right\'></button>',
    dots: true,
    autoplaySpeed: 7000,
    pauseOnFocus: false,
    pauseOnHover: false
  });
  $('.hero-slider').slickAnimation();

  function handleProductNavigation(event) {
    var interactiveTarget = $(event.target).closest('a, button, input, textarea, select, label, form');
    if (interactiveTarget.length) {
      return;
    }

    var productCard = $(event.currentTarget);
    var url = productCard.data('productUrl');
    if (url) {
      window.location.href = url;
    }
  }

  function handleProductKeypress(event) {
    if (event.key !== 'Enter') {
      return;
    }
    handleProductNavigation(event);
  }

  $(document).on('click', '.product-item--clickable', handleProductNavigation);
  $(document).on('keypress', '.product-item--clickable', handleProductKeypress);

})(jQuery);
