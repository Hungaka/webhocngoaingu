$(document).ready(function () {
  $(".comment-slider__list").slick({
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 2000,
    prevArrow: ``,
    nextArrow: ``,
    dots: true,
  });
});
