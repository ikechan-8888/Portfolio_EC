<template>
  <div>
    <div class="pankuzu_list">
      <RouterLink class="pankuzu_link pankuzu_font" to="/topPage">
        トップページ
      </RouterLink>
      <p class="pankuzu_symbol">＞</p>
      <h5 class="pankuzu_font">店舗情報</h5>
    </div>
    <div class="shop">
      <div class="shop_photo">
        <img src="image/店舗写真.png" alt="店舗写真">
      </div>
      <div class="shop-container">
        <div class="shop_information">
          <h4 class="information_name">ショップ名</h4>
          <p class="information_contents">PORTFOLIO</p>
        </div>
        <div class="shop_information">
          <h4 class="information_name">住所</h4>
          <p class="information_contents">東京都千代田区丸の内1丁目9</p>
          <a class="information_link information_link_button button" href="https://goo.gl/maps/EndFfzHFF8LRcWH97" target="_blank">地図をみる</a>
        </div>
        <div class="shop_information">
          <h4 class="information_name">TEL</h4>
          <p class="information_contents">03-1234-5678</p>
        </div>
        <div class="shop_information">
          <h4 class="information_name">営業時間</h4>
          <p class="information_contents">OPEN 11:00 CLOSE 20:00</p>
        </div>
        <div class="shop_information">
          <h4 class="information_name">定休日</h4>
          <p class="information_contents">定休日：不定休</p>
        </div>
        <div class="shop_information">
          <h4 class="information_name">Instagram</h4>
          <a class="information_link information_link_button button" href="https://www.instagram.com" target="_blank">Instagramをみる</a>
        </div>
        <div class="shop_information">
          <h4 class="information_name">Twitter</h4>
          <a class="information_link information_link_button button" href="https://twitter.com" target="_blank">Twitterをみる</a>
        </div>
        <div class="shop_information">
          <h4 class="information_name">LINE公式アカウント</h4>
          <a class="information_link information_link_button button" href="https://line.me" target="_blank">LINE公式アカウントをみる</a>
        </div>
      </div>
    </div>
  </div>
</template>