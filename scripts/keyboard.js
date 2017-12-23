(function ($) {

  $(document).ready(function() {
    
    $('.navbar-dropdown .navbar-item').focus(function() {
      $('#ding').toggleClass('is-warning');
      
      if( $(this).closest('.has-dropdown').find(':focus').length > 0) {
        $('#bleep').toggleClass('is-danger');
        $(this).closest('.has-dropdown').addClass('is-hovered');
      }
    });
    $('.navbar-dropdown .navbar-item:last-of-type').focusout(function() {
      $('#click').toggleClass('is-success');
      $(this).closest('.has-dropdown').removeClass('is-hovered');
    });

  });

})(jQuery);