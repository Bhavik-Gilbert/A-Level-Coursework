  <!-- Slideshow container -->
<div class="slideshow-container">

<!-- Full-width images with number and caption text -->
  <div class="mySlides fade">
    <img src="images/Portfolio/IMG_20210923_215013_466.jpg" style="width:100%" >
  </div>

  <div class="mySlides fade">
    <img src="images/Portfolio/IMG_20210923_215013_224.jpg" style="width:100%">
  </div>

  <div class="mySlides fade">
    <img src="images/Portfolio/IMG_20210418_194557.jpg" style="width:100%">
  </div>

  <div class="mySlides fade">
    <img src="images/Portfolio/20210812_011019.jpg" style="width:100%">
  </div>

  <div class="mySlides fade">
    <img src="images/Portfolio/20210830_144218.jpg" style="width:100%">
  </div>

  <div class="mySlides fade">
    <img src="images/Portfolio/20210831_163958.jpg" style="width:100%">
  </div>

  <div class="mySlides fade">
    <img src="images/Portfolio/IMG_20200226_175524.jpg" style="width:100%">
  </div>

  <div class="mySlides fade">
    <img src="images/Portfolio/20210916_195014.jpg" style="width:100%">
  </div>

  <!-- Next and previous buttons -->
  <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
  <a class="next" onclick="plusSlides(1)">&#10095;</a>
</div>
<br>

<!-- The dots/circles -->
<div style="text-align:center">
  <span class="dot" onclick="currentSlide(1)"></span>
  <span class="dot" onclick="currentSlide(2)"></span>
  <span class="dot" onclick="currentSlide(3)"></span>
  <span class="dot" onclick="currentSlide(4)"></span>
  <span class="dot" onclick="currentSlide(5)"></span>
  <span class="dot" onclick="currentSlide(6)"></span>
  <span class="dot" onclick="currentSlide(7)"></span>
  <span class="dot" onclick="currentSlide(8)"></span>
</div>

<script>
var slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";
}
</script>




<head>
  <title>Slideshow</title>
  <link rel="stylesheet" type = "text/css" href="CSS/slideshow.css">
</head>