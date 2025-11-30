<?php
// index.php
include "config/config.php";
include "includes/header.php"; // header otomatis cek session

// ambil info user
$user = $_SESSION['user'];
$isAdmin = ($user['role'] === 'admin');
?>
<link rel="stylesheet" href="/indomaret/assets/css/style.css">

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Indomaret Homepage</title>
  <!-- Tailwind CSS via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Hide horizontal scrollbar for drag slider */
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
  </style>
</head>
<body class="bg-gray-100 font-sans text-black">

<!-- ===== BANNER SLIDER ===== -->
<section class="banner relative overflow-hidden max-w-4xl mx-auto mt-8 rounded shadow-lg h-72">
  <div class="banner-slide flex w-full" style="transition: transform 0.5s ease-in-out;">
    <img src="/indomaret/assets/img/promo2.jpeg" alt="Promo 1" class="w-full flex-shrink-0 object-cover h-full rounded">
    <img src="/indomaret/assets/img/promo1.jpeg" alt="Promo 2" class="w-full flex-shrink-0 object-cover h-full rounded">
    <img src="https://www.klikindomaret.com/assets-klikidmcore/_next/image?url=https%3A%2F%2Fcdn-klik.klikindomaret.com%2Fhome%2Fbanner%2F22e7fdea-eae8-45c5-ba0e-0a77c5f69072.png&w=1080&q=75" alt="Promo 3" class="w-full flex-shrink-0 object-cover h-full rounded">
  </div>
</section>

<!-- ===== PROMO TERBARU ===== -->
<section class="promo py-8 px-4 max-w-6xl mx-auto">
  <h2 class="text-2xl font-bold mb-4">Promo Lainnya</h2>
  <div class="promo-cards grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
    <div class="promo-card bg-white rounded shadow overflow-hidden">
      <img src="https://www.klikindomaret.com/assets-klikidmcore/_next/image?url=https%3A%2F%2Fcdn-klik.klikindomaret.com%2Fhome%2Fbanner%2F5575f587-b217-4d17-b5ce-742de98a430f.png&w=1080&q=75" alt="">
    </div>
    <div class="promo-card bg-white rounded shadow overflow-hidden">
      <img src="https://www.klikindomaret.com/assets-klikidmcore/_next/image?url=https%3A%2F%2Fcdn-klik.klikindomaret.com%2Fhome%2Fbanner%2Ff565c682-953a-4adc-91e6-bcc0336bd1cb.png&w=1080&q=75" alt="">
    </div>
    <div class="promo-card bg-white rounded shadow overflow-hidden">
      <img src="https://www.klikindomaret.com/assets-klikidmcore/_next/image?url=https%3A%2F%2Fcdn-klik.klikindomaret.com%2Fhome%2Fbanner%2F9bcba85b-a276-43b9-a294-0a65863d7e59.png&w=1080&q=75" alt="">
    </div>
    <div class="promo-card bg-white rounded shadow overflow-hidden">
      <img src="https://www.klikindomaret.com/assets-klikidmcore/_next/image?url=https%3A%2F%2Fcdn-klik.klikindomaret.com%2Fhome%2Fbanner%2F12060ab1-6dff-490f-91bf-edc133a71942.png&w=1080&q=75" alt="">
    </div>
  </div>
</section>

<!-- ===== KATEGORI PRODUK ===== -->
<section class="kategori py-8 px-4 max-w-6xl mx-auto">
  <h2 class="text-2xl font-bold mb-4">Kategori Produk</h2>
  <div class="kategori-list grid grid-cols-2 sm:grid-cols-4 gap-4">
    <div class="kategori-item bg-white rounded shadow overflow-hidden text-center">
      <img src="https://www.klikindomaret.com/assets-klikidmcore/_next/image?url=https%3A%2F%2Fcdn-klik.klikindomaret.com%2Fklik-catalog%2Fproduct%2F10004401_thumb.jpg&w=1920&q=75" class="mx-auto">
      <p class="p-2">Minuman</p>
    </div>
    <div class="kategori-item bg-white rounded shadow overflow-hidden text-center">
      <img src="https://cdn-klik.klikindomaret.com/klik-catalog/product/20087713_1.jpg" class="mx-auto">
      <p class="p-2">Makanan</p>
    </div>
    <div class="kategori-item bg-white rounded shadow overflow-hidden text-center">
      <img src="https://cdn-klik.klikindomaret.com/klik-catalog/product/10001094_1.jpg" class="mx-auto">
      <p class="p-2">Snack</p>
    </div>
    <div class="kategori-item bg-white rounded shadow overflow-hidden text-center">
      <img src="https://cdn-klik.klikindomaret.com/klik-catalog/product/20137260_1.jpg" class="mx-auto">
      <p class="p-2">Perawatan</p>
    </div>
  </div>
</section>

<?php include "includes/footer.php"; ?>

<!-- JS Slider otomatis scroll-x-only -->
<script>
const slider = document.querySelector('.banner-slide');
const slides = document.querySelectorAll('.banner-slide img');
let index = 0;
const totalSlides = slides.length;
const duration = 4000; // 4 detik

function nextSlide() {
    index++;
    if(index >= totalSlides) index = 0;
    slider.style.transform = `translateX(-${index * 100}%)`;
}

// Set interval auto-slide
setInterval(nextSlide, duration);
</script>

</body>
</html>