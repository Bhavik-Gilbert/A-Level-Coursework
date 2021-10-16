<DOCTYPE HTML>
<html>
<?php
session_start();
#displays header at the top of the page
include 'Enitity/Header.php';
?>
<head>
<link rel="stylesheet" type = "text/css" href="CSS/Style.css">
<title>About Page</title>
</head>

<body>
<?php
#displays navbar at the top of the page
include 'Enitity/menu.php';
#displays 2 images at the top of the page center
?>
<br>

<div align="center">
<div class = "picture">
<img src='images/about1.jpeg' alt='Photo1'>
</div>
<div class = "picture">
<img src='images/about2.jpg' alt='Photo2'>
</div>
</div>

<br>

<p></p>
<div class="basic-container" align="center">
<h1>About Me</h1>

<p>My name’s Dan Burman and I am a portrait and event photographer. I’ve shot professionally for the last thirteen years, but I've been passionate about photography since I was a kid. 
   As I child I wanted to be a nature photographer and after inheriting £300 when I was 10 years old, I went straight to the local camera shop and bought the biggest, most professional camera I could afford. 
   I spent quite a while teaching myself how to use the camera and I ended up taking lots of blurry photos of ducks in the park. When I hit my teenage years I was told wildlife photography wasn’t “cool”, 
   so my precious SLR camera went under the bed to be replaced by a Sony Playstation. Once I had finished with my “cool” phase a few years later, my camera resurfaced as I began my art foundation course. 
   I was working in the darkroom a lot, developing my own prints, often in very experimental ways and merging it with print, paint and drawing.</p>
<p>I absolutely loved my Art foundation course and my passion with photography was born. I took a gap year and the camera came with me. I did a 9 month round the world trip, taking plenty of photos along the way. 
   The camera got very bashed about, dropped onto concrete and filled with dust and sand, but it survived. On my return to the UK I began my Fine Art degree at St Martins School of Art in London, 
   where I specialised in photography. I had a keen interest in travel photography and my work explored notions of how images are created across boundaries. 
   I also began to explore documentary photography and merge my images with sound recordings. After my degree I began working for magazines, which was a great experience as I love editorial photography. 
   I enjoyed all the fascinating people I would meet and places I visited. Today I shoot for a variety of clients, mostly in London. Some of my previous clients and publications include: 
    Channel 5, Siemens, The Financial Times, Premier Inn, The Times, American Express, Expedia, Legal and General, Air BnB, The Evening Standard, Dulux, Estates Gazette, Accenture, The Independent, Babybel, 
    BBC News, The Telegraph, Bulmers Cider, The Mail on Sunday, Vice Magazine, The British Orthodontic Society, Runners World Magazine, Forrester, The Irish Times.</p>

</div>
<h4 align="center">Please get in touch if you'd like to discuss a photography job.<h4>

<br><br><br>
</body>

<?php
#displays footer at the bottom of the page
include 'Enitity/Footer.php';
?>
</html>

