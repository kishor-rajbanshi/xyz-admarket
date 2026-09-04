(function() {
  "use strict";

  /**
   * Easy selector helper function
   */
  const select = (el, all = false) => {
    el = el.trim()
    if (all) {
      return [...document.querySelectorAll(el)]
    } else {
      return document.querySelector(el)
    }
  }

  /**
   * Easy event listener function
   */
  const on = (type, el, listener, all = false) => {
    let selectEl = select(el, all)
    if (selectEl) {
      if (all) {
        selectEl.forEach(e => e.addEventListener(type, listener))
      } else {
        selectEl.addEventListener(type, listener)
      }
    }
  }

  /**
   * Easy on scroll event listener
   */
  const onscroll = (el, listener) => {
    el.addEventListener('scroll', listener)
  }

  /**
   * Toggle .header-scrolled class to .header-class when page is scrolled
   */
  let selectHeader = select('.header-class')
  if (selectHeader) {
    const headerScrolled = () => {
      if (window.scrollY > 100) {
        selectHeader.classList.add('header-scrolled')
      } else {
        selectHeader.classList.remove('header-scrolled')
      }
    }
    window.addEventListener('load', headerScrolled)
    onscroll(document, headerScrolled)
  }

  /**
   * Back to top button
   */
  let backtotop = select('.back-to-top')
  if (backtotop) {
    const toggleBacktotop = () => {
      if (window.scrollY > 100) {
        backtotop.classList.add('active')
      } else {
        backtotop.classList.remove('active')
      }
    }
    window.addEventListener('load', toggleBacktotop)
    onscroll(document, toggleBacktotop)
  }


  /**
   * Mobile nav toggle
   */
  on('click', '.mobile-nav-toggle', function(e) {
    select('#navbar').classList.toggle('navbar-mobile')
    this.classList.toggle('fa-bars')
    this.classList.toggle('fa-times')
  })

  /**
   * Mobile nav dropdowns activate
   */
  on('click', '.navbar .dropdown > a', function(e) {
    if (select('#navbar').classList.contains('navbar-mobile')) {
      e.preventDefault()
      this.nextElementSibling.classList.toggle('dropdown-active')
    }
  }, true)


/**
   * Testimonials slider
   */
if($('.slides-1').length > 0)
{
  new Swiper('.slides-1', {
    speed: 600,
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false
    },
    slidesPerView: 'auto',
    pagination: {
      el: '.swiper-pagination',
      type: 'bullets',
      clickable: true
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    }
  });
}


    $("#dashboard-side-menu-icon").click(function(){

        if($(".dashboard-side-menu-temp").length == 0)
        {
            $("#dashboard-side-menu").addClass("dashboard-side-menu-temp");
            $(".content-section").addClass("content-section-menu-hide");
        }
        else
        {
            $("#dashboard-side-menu").removeClass("dashboard-side-menu-temp");
            $(".content-section").removeClass("content-section-menu-hide");
        }

    });




    if($('.dashboard-side-menu .sidebar').length > 0)
    {
	    $('.dashboard-side-menu .sidebar').mCustomScrollbar({
	            axis:"y",
	    });
    }


/**
 * Animation on scroll
 */
 if(!(typeof AOS  == 'undefined'))
 {
     window.addEventListener('load', () => {
       AOS.init({
         duration: 1000,
         easing: 'ease-in-out',
         once: true,
         mirror: false
       })
     });
 }


function heightAdjustment(operation)
{
    var contentSectionHeight = $(".content-section").outerHeight();

		var headerSectionHeight  = 100;
		var footerSectionHeight  = $(".footer-class").outerHeight();

		var screenHeight         = $(window).height();
		var pageContentHeight    = screenHeight - headerSectionHeight - footerSectionHeight;

		$(".content-section").css("min-height", pageContentHeight+"px");
}

if($(".content-section").length > 0)
heightAdjustment();

$(window).resize(function()
{
      if($(".content-section").length > 0)
      heightAdjustment();
});

})();
