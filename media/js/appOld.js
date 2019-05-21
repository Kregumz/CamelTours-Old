$(function(){
  var nodeSwiperContainer = $('.swiper-container');
  var lastTap = new Date().getTime();
  var suppressHover = false;
  var isMoving = false;
  var barOpacity = 0;
  var resize;
  var resizeFix;
  setSlideSize();
  var nodeSwiper = nodeSwiperContainer.swiper({
    slidesPerView: 'auto',
    centeredSlides :true,
    watchActiveIndex :true,
    pagination: '.pagination',
    paginationClickable: true,
    resizeReInit: true,
    keyboardControl: true,
    onImagesReady: function(){ setSlideSize() },
    onTouchStart: function(){ isMoving = false; },
    onTouchMove: function(){ isMoving = true; },
    onTouchEnd: function(){ checkIfTap() }
  });
  function checkIfTap(){
    if (!isMoving){
      var currentTap = new Date().getTime();
      if (currentTap - lastTap > 800){
        lastTap = currentTap;
        toggleBarDisplay();
      }
    }
  }
  function toggleBarDisplay(opacity){
    var bars = $('.caption, .audiobar');
    if (typeof opacity !== 'undefined'){
      if (suppressHover == false){
        barOpacity = opacity;
        bars.stop().animate({'opacity': barOpacity}, 'slow');
      }
    }
    else if (!bars.is(':animated')){
      barOpacity ^= 1;
      suppressHover = true;
      bars.animate({'opacity': barOpacity}, 'slow');
      setTimeout(function(){ suppressHover = false; }, 599);
    }
  }
  function addListeners(){
    $('img, .caption, .audiobar').hover(
      function(){ toggleBarDisplay(1); },
      function(){ toggleBarDisplay(0); }
    );
  }
  function checkImages(){
    var resize = false;
    var images = $('.swiper-slide img');
    images.each(function(index){
      var img = $(this);
      var testImage = new Image();
      testImage.src = img.attr('src');
      if (testImage.height == 0){
        img.hide().parents('.inner').parents('.swiper-slide').remove();
        resize = true;
      }
      if (resize)
        nodeSwiper.reInit();
    });
  }
  function setSlideSize(){
    var windowWidth = $(window).width();
    var windowHeight = $(window).height();
    var maxSlideWidth = windowWidth - 20;
    var maxSlideHeight = windowHeight - 70;
    var introContent = $('.swiper-intro .intro-content');
    introContent.css({ position: 'absolute',
     left: (maxSlideWidth - introContent.outerWidth())/2,
     top: (maxSlideHeight - 50 - introContent.outerHeight())/2 });
    $('.swiper-slide').css('width', windowWidth).css('height', windowHeight);
    $('.swiper-intro').css('max-height', maxSlideHeight);
    $('.swiper-slide img').css('max-height', maxSlideHeight);
    $('.swiper-slide .inner .caption').css('width', maxSlideWidth);
    setTimeout(centerImages, 300);
  }
  function centerImages(){
    $('.swiper-slide img').each(function(){
      var img = $(this);
      img.css('top', Math.max(0, (($(window).height() - img.outerHeight()) / 2) + 
                                   $(window).scrollTop() - 35) + 'px');
      img.css('left', Math.max(0, (($(window).width() - img.outerWidth()) / 2) + 
                                    $(window).scrollLeft() - 10) + 'px');
    });
  }
  $(window).load(function(){
    addListeners();
    checkImages();
    setSlideSize();
    var notStarted = true;
    var nodeAudio = $('#node-audio');
    var nodeControl = $('#node-control');
    if (nodeAudio.length) {
      nodeAudio.bind('playing', function() {
        nodeControl.text('Pause Audio');
        notStarted = false;
      });
      nodeAudio.bind('pause', function() {
        nodeControl.text('Resume Audio');
      });
      nodeAudio.bind('ended', function() {
        nodeControl.text('Replay Audio');
      });
      nodeControl.click(function() {
        if (notStarted) {
          nodeAudio.get(0).play();
          nodeSwiper.swipeNext();
        }
        else {
          if (nodeAudio.get(0).paused)
            nodeAudio.get(0).play();
          else
            nodeAudio.get(0).pause();
        }
        return false;
      });
    }
    else if (nodeControl.length) {
      nodeControl.click(function() {
        if (nodeSwiper.previousIndex)
          nodeSwiper.swipeTo(nodeSwiper.previousIndex);
        else
          nodeSwiper.swipeNext();
        return false;
      });
    }
    nodeSwiperContainer.css('visibility', 'visible');
    $('#loader').css('display', 'none');
  });
  $(window).resize(function(){
    setSlideSize();
    nodeSwiper.resizeFix(true);
  });
});