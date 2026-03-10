var $ = jQuery.noConflict();
jQuery(document).ready(function ($) {

  $("input[type='tel']").inputmask({
    "mask": "+38 (999) 999-99-99"
  });

  document.addEventListener('wpcf7mailsent', function (event) {
    var status = event.detail.apiResponse.status;
    if (status === 'mail_sent') {
      $('.dialog-close-button').trigger('click');
      $('.success a').trigger('click');
    }
  });

  $("a[href='#top']").on("click", function () {
    $("html, body").animate({
      scrollTop: 0
    }, "slow");
    return false;
  });

  window.addEventListener('scroll', function () {
    var element = document.querySelector('.scroll-top');
    if (window.scrollY > 150) {
      element.classList.add('active');
    } else {
      element.classList.remove('active');
    }
  });


});	