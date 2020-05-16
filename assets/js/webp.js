(function($) {
  var hasWebP = (function() {
    // some small (2x1 px) test images for each feature
    var images = {
      basic:
        "data:image/webp;base64,UklGRjIAAABXRUJQVlA4ICYAAACyAgCdASoCAAEALmk0mk0iIiIiIgBoSygABc6zbAAA/v56QAAAAA==",
      lossless:
        "data:image/webp;base64,UklGRh4AAABXRUJQVlA4TBEAAAAvAQAAAAfQ//73v/+BiOh/AAA="
    };

    return function(feature) {
      var deferred = $.Deferred();

      $("<img>")
        .on("load", function() {
          // the images should have these dimensions
          if (this.width === 2 && this.height === 1) {
            deferred.resolve();
          } else {
            deferred.reject();
          }
        })
        .on("error", function() {
          deferred.reject();
        })
        .attr("src", images[feature || "basic"]);

      return deferred.promise();
    };
  })();

  var replaceImage = function(msg) {
    $('img').each(function() {
      var dataOriginal = $(this).attr('data-original');
      if(dataOriginal !== undefined){
        $(this).attr('src', dataOriginal);
      }
    });
  };

  var replaceBackgrounds = function(msg) {
    $('.item-has-bg').each(function() {
      var dataOriginal = $(this).attr('data-original');
      if(dataOriginal !== undefined){
        $(this).css('background-image',  "url(" + dataOriginal + ")");
      }
    });
  };

  $(document).ready(function() {

    hasWebP().then(
      function() {
      },
      function() {
        replaceImage();
        replaceBackgrounds();
      }
    );

  });

  
})(jQuery);
