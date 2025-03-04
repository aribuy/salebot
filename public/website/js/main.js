(function (window, document, $, undefined) {
  "use strict";

  var saleBotJs = {
    i: function (e) {
      saleBotJs.d();
      saleBotJs.methods();
    },

    d: function (e) {
      (this._window = $(window)),
        (this._document = $(document)),
        (this._body = $("body")),
        (this._html = $("html"));
    },

    methods: function (e) {
      saleBotJs.backToTopInit();
      saleBotJs.headerSticky();
      saleBotJs.counterUpActivation();
      saleBotJs.slickSliderActivation();
      saleBotJs.salActive();
      saleBotJs.onePageNav();
    },

    backToTopInit: function () {
      var scrollTop = $(".dreamd-back-top");
      $(window).scroll(function () {
        var topPos = $(this).scrollTop();
        if (topPos > 150) {
          $(scrollTop).css("opacity", "1");
        } else {
          $(scrollTop).css("opacity", "0");
        }
      });
      $(scrollTop).on("click", function () {
        $("html, body").animate(
          {
            scrollTop: 0,
            easingType: "linear",
          },
          10
        );
        return false;
      });
    },

    headerSticky: function () {
      $(window).scroll(function () {
          if ($(this).scrollTop() > 250) {
              $('.header-sticky').addClass('sticky')
          } else {
              $('.header-sticky').removeClass('sticky')
          }
      })
  },

    counterUpActivation: function () {
      $(".counter").counterUp({
        delay: 10,
        time: 1000,
      });
    },

    // =========================
    slickSliderActivation: function () {
      $(".brand-slick-activition")
        .not(".slick-initialized")
        .slick({
          autoplay: true,
          infinite: true,
          slidesToShow: 7,
          slidesToScroll: 1,
          dots: false,
          arrows: false,
          autoplaySpeed: 0,
          cssEase: "linear",
          speed: 8000,
          draggable: false,
          responsive: [
            {
              breakpoint: 769,
              settings: {
                slidesToShow: 5,
                slidesToScroll: 1,
              },
            },
            {
              breakpoint: 581,
              settings: {
                slidesToShow: 3,
              },
            },
          ],
        });
    },

    salActive: function () {
        sal({
            threshold: 0.01,
            once: true,
        });
    },
    
    onePageNav: function () {
      $('.onepagenav').onePageNav({
          currentClass: 'current',
          changeHash: false,
          scrollSpeed: 500,
          scrollThreshold: 0.2,
          filter: '',
          easing: 'swing',
      });
  },

  };
  saleBotJs.i();
})(window, document, jQuery);
