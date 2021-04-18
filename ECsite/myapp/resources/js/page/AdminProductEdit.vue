<template>
  <div>
    <Loading class="loadingblock" v-show="loading" :key="loadingType.id" :item="loadingType" />
    <AdminProductEditCheck class="overlay" v-show="checkImage" :key="registerForm.id" :item="registerForm" @close="onclose" />
    <div class="container--registerform">
      <div class="form_top">
        <h1 class="form_name">登録商品編集(再読み込みすると変更内容がクリアされしまいます)</h1>
        <!-- <RouterLink class="button admin_backButton" :to="`/adminTopPage`">戻る</RouterLink> -->
      </div>
      <div class="panel form_panel">
        <form class="form" enctype="multipart/form-data" @submit.prevent="register">
<!-- 基本の商品情報 -->
<!-- 販売状況 -->
          <div class="form_block_admin">
            <div class="form_block_admin_block">
              <label class="form_label">販売状況</label>
            </div>
          </div>
          <div class="form_check_buttons">
            <button class="button form_buy_button" :class="{'select_form_start_butoon': registerForm.displaystatus === 1}" :disabled="registerForm.displaystatus === 1" @click="chengeDisplayStatus">販売中</button>
            <button class="button form_buy_button" :class="{'select_form_stop_butoon': registerForm.displaystatus === 0}" :disabled="registerForm.displaystatus === 0" @click="chengeDisplayStatus">停止中</button>
          </div>
<!-- 商品名 -->
          <div class="form_block_admin">
            <div class="form_block_admin_block">
              <label for="register-name" class="form_label">商品名</label><span class="form_require">必須</span>
            </div>
          </div>
          <input type="text" class="form__item" :class="{ 'input_notok': registererror.name}" :disabled="inputDisabled" id="register-name" v-model="registerForm.name">
<!-- 商品金額 -->
          <div class="form_block_admin">
            <div class="form_block_admin_block">
              <label for="register-price" class="form_label">金額(税込)</label><span class="form_require">必須(半角数字で入力してください)</span>
            </div>
          </div>
          <input type="text" class="form__item" :class="{ 'input_notok': registererror.price}" :disabled="inputDisabled" id="register-price" v-model="registerForm.price">
<!-- 商品カラー -->
          <div class="form_block_admin">
            <div class="form_block_admin_block">
              <h3 class="register-font">色</h3><span class="form_require">必須</span>
            </div>
            <div v-if="registerErrors">
              <ul v-if="registerErrors.color" class="errors">
                <li v-for="msg in registerErrors.color" :key="msg">{{msg}}</li>
              </ul>
            </div>
          </div>
          <div class="form_block_color">
            <div class="form_block_colors">
              <label for="register-name-black" class="form_label_color">黒</label>
              <input type="radio" class="form__item__color" :disabled="inputDisabled" id="register-name-black" name="register-color" value="0" v-model="registerForm.color">
            </div>
            <div class="form_block_colors">
              <label for="register-name-white" class="form_label_color">白</label>
              <input type="radio" class="form__item__color" :disabled="inputDisabled" id="register-name-white" name="register-color" value="1" v-model="registerForm.color">
            </div>
            <div class="form_block_colors">
              <label for="register-name-gray" class="form_label_color">グレー</label>
              <input type="radio" class="form__item__color" :disabled="inputDisabled" id="register-name-gray" name="register-color" value="2" v-model="registerForm.color">
            </div>
            <div class="form_block_colors">
              <label for="register-name-red" class="form_label_color">赤</label>
              <input type="radio" class="form__item__color" :disabled="inputDisabled" id="register-name-red" name="register-color" value="3" v-model="registerForm.color">
            </div>
            <div class="form_block_colors">
              <label for="register-name-blue" class="form_label_color">青</label>
              <input type="radio" class="form__item__color" :disabled="inputDisabled" id="register-name-blue" name="register-color" value="4" v-model="registerForm.color">
            </div>
            <div class="form_block_colors">
              <label for="register-name-green" class="form_label_color">緑</label>
              <input type="radio" class="form__item__color" :disabled="inputDisabled" id="register-name-green" name="register-color" value="5" v-model="registerForm.color">
            </div>
            <div class="form_block_colors">
              <label for="register-name-yellow" class="form_label_color">黄</label>
              <input type="radio" class="form__item__color" :disabled="inputDisabled" id="register-name-yellow" name="register-color" value="6" v-model="registerForm.color">
            </div>
          </div>
<!-- 商品説明 -->
          <div class="form_block_admin">
            <div class="form_block_admin_block">
              <label for="register-content" class="form_label">商品説明</label><span class="form_require">必須</span>
            </div>
          </div>
          <textarea type="text" class="form__item" :class="{ 'input_notok': registererror.content}"  :disabled="inputDisabled" id="register-content" rows="15" v-model="registerForm.content"></textarea>
<!-- 商品画像　-->
          <div class="form_block_admin">
            <div class="form_block_admin_block">
              <label for="register-img" class="form_label">商品画像</label><span class="form_require">変更する場合選択してください(jpeg,jpg,png形式)</span>
            </div>
            <div v-if="registerErrors">
              <ul v-if="registerErrors.img" class="errors">
                <li v-for="msg in registerErrors.img" :key="msg">{{msg}}</li>
              </ul>
            </div>
          </div>
          <input type="file" name="product_img" class="form__item__file" :disabled="inputDisabled" id="register-img" accept=".png, .jpg, .jpeg" @change="onImgset">
<!-- カテゴリー -->
          <div class="form_block_admin">
            <div class="form_block_admin_block">
              <h3 class="register-font">カテゴリー</h3><span class="form_require">必須</span>
            </div>
            <div v-if="registerErrors">
              <ul v-if="registerErrors.category" class="errors">
                <li v-for="msg in registerErrors.category" :key="msg">{{msg}}</li>
              </ul>
            </div>
          </div>
          <div class="form_block_category">
            <div class="form_block_categorys">
              <label for="register-name-tops" class="form_label_category">トップス</label>
              <input type="radio" class="form__item__category" :disabled="inputDisabled" id="register-name-tops" name="register-category" value="0" v-model="registerForm.category">
            </div>
            <div class="form_block_categorys">
              <label for="register-name-bottoms" class="form_label_category">ボトムス</label>
              <input type="radio" class="form__item__category" :disabled="inputDisabled" id="register-name-bottoms" name="register-category" value="1" v-model="registerForm.category">
            </div>
          </div>
<!-- トップ商品サイズ -->
          <div v-show="registerForm.category === '0'" class="form_block_tops">
            <div class="form_block_admin">
              <div class="form_block_admin_block">
                <label for="register-topscategory" class="form_label">トップスカテゴリー</label><span class="form_require">必須</span>
              </div>
              <div v-if="registerErrors">
                <ul v-if="registerErrors.topscategory" class="errors">
                  <li v-for="msg in registerErrors.topscategory" :key="msg">{{msg}}</li>
                </ul>
              </div>
            </div>
            <select name="topscategory" class="form__item" :disabled="inputDisabled" id="register-topscategory" v-model="registerForm.topscategory">
              <option value="0" selected="selected">Tシャツ</option>
              <option value="1">シャツ</option>
              <option value="2">ポロシャツ</option>
            </select>
            <div class="form_block_admin">
              <div class="form_block_admin_block">
                <h3 class="register-font">Sサイズ</h3><span class="form_require">必須(半角数字で入力してください)</span>
              </div>
            </div>
            <div class="form_block_ssize">
              <div class="form_block_size">
                <label for="register-ssizebody" class="form_label_size">身幅</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.ssizebody}" :disabled="inputDisabled" id="register-ssizebody" v-model="registerForm.ssizebody">
              </div>
              <div class="form_block_size">
                <label for="register-ssizeshoulder" class="form_label_size">肩幅</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.ssizeshoulder}" :disabled="inputDisabled" id="register-ssizeshoulder" v-model="registerForm.ssizeshoulder">
              </div>
              <div class="form_block_size">
                <label for="register-ssizelength" class="form_label_size">着丈</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.ssizelength}" :disabled="inputDisabled" id="register-ssizelength" v-model="registerForm.ssizelength">
              </div>
              <div class="form_block_size">
                <label for="register-ssizesleeve" class="form_label_size">そで丈</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.ssizesleeve}" :disabled="inputDisabled" id="register-ssizesleeve" v-model="registerForm.ssizesleeve">
              </div>
            </div>
            <div class="form_block_admin">
              <div class="form_block_admin_block">
                <h3 class="register-font">Mサイズ</h3><span class="form_require">必須(半角数字で入力してください)</span>
              </div>
            </div>
            <div class="form_block_msize">
              <div class="form_block_size">
                <label for="register-msizebody" class="form_label_size">身幅</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.msizebody}" :disabled="inputDisabled" id="register-msizebody" v-model="registerForm.msizebody">
              </div>
              <div class="form_block_size">
                <label for="register-msizeshoulder" class="form_label_size">肩幅</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.msizeshoulder}" :disabled="inputDisabled" id="register-msizeshoulder" v-model="registerForm.msizeshoulder">
              </div>
              <div class="form_block_size">
                <label for="register-msizelength" class="form_label_size">着丈</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.msizelength}" :disabled="inputDisabled" id="register-msizelength" v-model="registerForm.msizelength">
              </div>
              <div class="form_block_size">
                <label for="register-msizesleeve" class="form_label_size">そで丈</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.msizesleeve}" :disabled="inputDisabled" id="register-msizesleeve" v-model="registerForm.msizesleeve">
              </div>
            </div>
            <div class="form_block_admin">
              <div class="form_block_admin_block">
                <h3 class="register-font">Lサイズ</h3><span class="form_require">必須(半角数字で入力してください)</span>
              </div>
            </div>
            <div class="form_block_lsize">
              <div class="form_block_size">
                <label for="register-lsizebody" class="form_label_size">身幅</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.lsizebody}" :disabled="inputDisabled" id="register-lsizebody" v-model="registerForm.lsizebody">
              </div>
              <div class="form_block_size">
                <label for="register-lsizeshoulder" class="form_label_size">肩幅</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.lsizeshoulder}" :disabled="inputDisabled" id="register-lsizeshoulder" v-model="registerForm.lsizeshoulder">
              </div>
              <div class="form_block_size">
                <label for="register-lsizelength" class="form_label_size">着丈</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.lsizelength}" :disabled="inputDisabled" id="register-lsizelength" v-model="registerForm.lsizelength">
              </div>
              <div class="form_block_size">
                <label for="register-lsizesleeve" class="form_label_size">そで丈</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.lsizesleeve}" :disabled="inputDisabled" id="register-lsizesleeve" v-model="registerForm.lsizesleeve">
              </div>
            </div>
            <div class="form_block_admin">
              <div class="form_block_admin_block">
                <h3 class="register-font">XLサイズ</h3><span class="form_require">必須(半角数字で入力してください)</span>
              </div>
            </div>
            <div class="form_block_xlsize">
              <div class="form_block_size">
                <label for="register-xlsizebody" class="form_label_size">身幅</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.xlsizebody}" :disabled="inputDisabled" id="register-xlsizebody" v-model="registerForm.xlsizebody">
              </div>
              <div class="form_block_size">
                <label for="register-xlsizeshoulder" class="form_label_size">肩幅</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.xlsizeshoulder}" :disabled="inputDisabled" id="register-xlsizeshoulder" v-model="registerForm.xlsizeshoulder">
              </div>
              <div class="form_block_size">
                <label for="register-xlsizelength" class="form_label_size">着丈</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.xlsizelength}" :disabled="inputDisabled" id="register-xlsizelength" v-model="registerForm.xlsizelength">
              </div>
              <div class="form_block_size">
                <label for="register-xlsizesleeve" class="form_label_size">そで丈</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.xlsizesleeve}" :disabled="inputDisabled" id="register-xlsizesleeve" v-model="registerForm.xlsizesleeve">
              </div>
            </div>
          </div>
<!-- ボトム商品サイズ -->
          <div v-show="registerForm.category === '1'" class="form_block_bottoms">
            <div class="form_block_admin">
              <div class="form_block_admin_block">
                <label for="register-bottomscategory" class="form_label">ボトムスカテゴリー</label><span class="form_require">必須</span>
              </div>
              <div v-if="registerErrors">
                <ul v-if="registerErrors.bottomscategory" class="errors">
                  <li v-for="msg in registerErrors.bottomscategory" :key="msg">{{msg}}</li>
                </ul>
              </div>
            </div>
            <select name="bottomscategory" class="form__item" :disabled="inputDisabled" id="register-bottomscategory" v-model="registerForm.bottomscategory">
              <option value="0" selected="selected">デニム</option>
              <option value="1">チノパンツ</option>
              <option value="2">スラックス</option>
            </select>
            <div class="form_block_admin">
              <div class="form_block_admin_block">
                <h3 class="register-font">Sサイズ</h3><span class="form_require">必須(半角数字で入力してください)</span>
              </div>
            </div>
            <div class="form_block_ssize">
              <div class="form_block_size_b">
                <label for="register-ssizewaist" class="form_label">ウエスト</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.ssizewaist}" :disabled="inputDisabled" id="register-ssizewaist" v-model="registerForm.ssizewaist">
              </div>
              <div class="form_block_size_b">
                <label for="register-ssizehips" class="form_label">お尻</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.ssizehips}" :disabled="inputDisabled" id="register-ssizehips" v-model="registerForm.ssizehips">
              </div>
              <div class="form_block_size_b">
                <label for="register-ssizerise" class="form_label">股上</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.ssizerise}" :disabled="inputDisabled" id="register-ssizerise" v-model="registerForm.ssizerise">
              </div>
              <div class="form_block_size_b">
                <label for="register-ssizeinseam" class="form_label">股下</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.ssizeinseam}" :disabled="inputDisabled" id="register-ssizeinseam" v-model="registerForm.ssizeinseam">
              </div>
              <div class="form_block_size_b">
                <label for="register-ssizethigh" class="form_label">太股</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.ssizethigh}" :disabled="inputDisabled" id="register-ssizethigh" v-model="registerForm.ssizethigh">
              </div>
              <div class="form_block_size_b">
                <label for="register-ssizehem" class="form_label">すそ周り</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.ssizehem}" :disabled="inputDisabled" id="register-ssizehem" v-model="registerForm.ssizehem">
              </div>
            </div>
            <div class="form_block_admin">
              <div class="form_block_admin_block">
                <h3 class="register-font">Mサイズ</h3><span class="form_require">必須(半角数字で入力してください)</span>
              </div>
            </div>
            <div class="form_block_msize">
              <div class="form_block_size_b">
                <label for="register-msizewaist" class="form_label">ウエスト</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.msizewaist}" :disabled="inputDisabled" id="register-msizewaist" v-model="registerForm.msizewaist">
              </div>
              <div class="form_block_size_b">
                <label for="register-msizehips" class="form_label">お尻</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.msizehips}" :disabled="inputDisabled" id="register-msizehips" v-model="registerForm.msizehips">
              </div>
              <div class="form_block_size_b">
                <label for="register-msizerise" class="form_label">股上</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.msizerise}" :disabled="inputDisabled" id="register-msizerise" v-model="registerForm.msizerise">
              </div>
              <div class="form_block_size_b">
                <label for="register-msizeinseam" class="form_label">股下</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.msizeinseam}" :disabled="inputDisabled" id="register-msizeinseam" v-model="registerForm.msizeinseam">
              </div>
              <div class="form_block_size_b">
                <label for="register-msizethigh" class="form_label">太股</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.msizethigh}" :disabled="inputDisabled" id="register-msizethigh" v-model="registerForm.msizethigh">
              </div>
              <div class="form_block_size_b">
                <label for="register-msizehem" class="form_label">すそ周り</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.msizehem}" :disabled="inputDisabled" id="register-msizehem" v-model="registerForm.msizehem">
              </div>
            </div>
            <div class="form_block_admin">
              <div class="form_block_admin_block">
                <h3 class="register-font">Lサイズ</h3><span class="form_require">必須(半角数字で入力してください)</span>
              </div>
            </div>
            <div class="form_block_lsize">
              <div class="form_block_size_b">
                <label for="register-lsizewaist" class="form_label">ウエスト</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.lsizewaist}" :disabled="inputDisabled" id="register-lsizewaist" v-model="registerForm.lsizewaist">
              </div>
              <div class="form_block_size_b">
                <label for="register-lsizehips" class="form_label">お尻</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.lsizehips}" :disabled="inputDisabled" id="register-lsizehips" v-model="registerForm.lsizehips">
              </div>
              <div class="form_block_size_b">
                <label for="register-lsizerise" class="form_label">股上</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.lsizerise}" :disabled="inputDisabled" id="register-lsizerise" v-model="registerForm.lsizerise">
              </div>
              <div class="form_block_size_b">
                <label for="register-lsizeinseam" class="form_label">股下</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.lsizeinseam}" :disabled="inputDisabled" id="register-lsizeinseam" v-model="registerForm.lsizeinseam">
              </div>
              <div class="form_block_size_b">
                <label for="register-lsizethigh" class="form_label">太股</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.lsizethigh}" :disabled="inputDisabled" id="register-lsizethigh" v-model="registerForm.lsizethigh">
              </div>
              <div class="form_block_size_b">
                <label for="register-lsizehem" class="form_label">すそ周り</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.lsizehem}" :disabled="inputDisabled" id="register-lsizehem" v-model="registerForm.lsizehem">
              </div>
            </div>
            <div class="form_block_admin">
              <div class="form_block_admin_block">
                <h3 class="register-font">XLサイズ</h3><span class="form_require">必須(半角数字で入力してください)</span>
              </div>
            </div>
            <div class="form_block_xlsize">
              <div class="form_block_size_b">
                <label for="register-xlsizewaist" class="form_label">ウエスト</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.xlsizewaist}" :disabled="inputDisabled" id="register-xlsizewaist" v-model="registerForm.xlsizewaist">
              </div>
              <div class="form_block_size_b">
                <label for="register-xlsizehips" class="form_label">お尻</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.xlsizehips}" :disabled="inputDisabled" id="register-xlsizehips" v-model="registerForm.xlsizehips">
              </div>
              <div class="form_block_size_b">
                <label for="register-xlsizerise" class="form_label">股上</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.xlsizerise}" :disabled="inputDisabled" id="register-xlsizerise" v-model="registerForm.xlsizerise">
              </div>
              <div class="form_block_size_b">
                <label for="register-xlsizeinseam" class="form_label">股下</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.xlsizeinseam}" :disabled="inputDisabled" id="register-xlsizeinseam" v-model="registerForm.xlsizeinseam">
              </div>
              <div class="form_block_size_b">
                <label for="register-xlsizethigh" class="form_label">太股</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.xlsizethigh}" :disabled="inputDisabled" id="register-xlsizethigh" v-model="registerForm.xlsizethigh">
              </div>
              <div class="form_block_size_b">
                <label for="register-xlsizehem" class="form_label">すそ周り</label>
                <input type="text" class="form__item_size" :class="{ 'input_notok': registererror.xlsizehem}" :disabled="inputDisabled" id="register-xlsizehem" v-model="registerForm.xlsizehem">
              </div>
            </div>
          </div>
<!-- 商品の在庫数 -->
          <div class="form_block_admin">
            <div class="form_block_admin_block">
              <h3 class="register-font">在庫数</h3><span class="form_require">必須(半角数字で入力してください)</span>
            </div>
          </div>
          <div class="form_block_stock">
            <div class="form_block_sstock">
              <label for="register-ssizeitems" class="form_label">Sサイズ</label>
              <input type="text" class="form__item" :class="{ 'input_notok': registererror.ssizeitems}" :disabled="inputDisabled" id="register-ssizeitems" v-model="registerForm.ssizeitems">
            </div>
            <div class="form_block_mstock">
              <label for="register-msizeitems" class="form_label">Mサイズ</label>
              <input type="text" class="form__item" :class="{ 'input_notok': registererror.msizeitems}" :disabled="inputDisabled" id="register-msizeitems" v-model="registerForm.msizeitems">
            </div>
            <div class="form_block_lstock">
              <label for="register-lsizeitems" class="form_label">Lサイズ</label>
              <input type="text" class="form__item" :class="{ 'input_notok': registererror.lsizeitems}" :disabled="inputDisabled" id="register-lsizeitems" v-model="registerForm.lsizeitems">
            </div>
            <div class="form_block_xlstock">
              <label for="register-xlsizeitems" class="form_label">XLサイズ</label>
              <input type="text" class="form__item" :class="{ 'input_notok': registererror.xlsizeitems}" :disabled="inputDisabled" id="register-xlsizeitems" v-model="registerForm.xlsizeitems">
            </div>
          </div>
          <div class="admin_form__buttons">
            <button type="button" v-show="button === 1" class="button admin_form_button" @click="onValidate">入力内容を確認する</button>
            <button type="button" v-show="button === 2" class="button admin_form_button_change" @click="onEdit">入力内容を変更する</button>
            <button type="submit" v-show="button === 2" class="button adminregister_form_button">更新する</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import Loading from '../components/Loading.vue'
import AdminProductEditCheck from '../components/AdminProductEditCheck.vue'
import { OK } from '../util'

export default{
  components: {
    Loading,
    AdminProductEditCheck,
  },
  props: {
    id: {
        type: String,
        required: true
    }
  },
  data(){
    return{
      item: null,
      button: 1,
      inputDisabled: false,
      checkImage: false,
      registerForm: {
        id: 1,
        productId: '',
        displaystatus: '',
        name: '',
        price: '',
        color: '',
        colorname: '',
        content: '',
        imgData: '',
        img: '',
        imgencodeurl: '',
        imgName: '',
        category: '',
        categoryName: '',
        categoryTypeName: '',
        topscategory: '',
        ssizebody: '',
        ssizeshoulder: '',
        ssizelength: '',
        ssizesleeve: '',
        msizebody: '',
        msizeshoulder: '',
        msizelength: '',
        msizesleeve: '',
        lsizebody: '',
        lsizeshoulder: '',
        lsizelength: '',
        lsizesleeve: '',
        xlsizebody: '',
        xlsizeshoulder: '',
        xlsizelength: '',
        xlsizesleeve: '',
        bottomscategory: '',
        ssizewaist: '',
        ssizehips: '',
        ssizerise: '',
        ssizeinseam: '',
        ssizethigh: '',
        ssizehem: '',
        msizewaist: '',
        msizehips: '',
        msizerise: '',
        msizeinseam: '',
        msizethigh: '',
        msizehem: '',
        lsizewaist: '',
        lsizehips: '',
        lsizerise: '',
        lsizeinseam: '',
        lsizethigh: '',
        lsizehem: '',
        xlsizewaist: '',
        xlsizehips: '',
        xlsizerise: '',
        xlsizeinseam: '',
        xlsizethigh: '',
        xlsizehem: '',
        ssizeitems: '',
        msizeitems: '',
        lsizeitems: '',
        xlsizeitems: '',
        ssizecheckitems: '',
        msizecheckitems: '',
        lsizecheckitems: '',
        xlsizecheckitems: '',
        ssizeregisteritems: '',
        msizeregisteritems: '',
        lsizeregisteritems: '',
        xlsizeregisteritems: '',
        Sstock: true,
        Mstock: true,
        Lstock: true,
        XLstock: true,
        sstock: '',
        mstock: '',
        lstock: '',
        xlstock: '',
      },
      registererror: {
        name: false,
        price: false,
        content: false,
        ssizebody: false,
        ssizeshoulder: false,
        ssizelength: false,
        ssizesleeve: false,
        msizebody: false,
        msizeshoulder: false,
        msizelength: false,
        msizesleeve: false,
        lsizebody: false,
        lsizeshoulder: false,
        lsizelength: false,
        lsizesleeve: false,
        xlsizebody: false,
        xlsizeshoulder: false,
        xlsizelength: false,
        xlsizesleeve: false,
        ssizewaist: false,
        ssizehips: false,
        ssizerise: false,
        ssizeinseam: false,
        ssizethigh: false,
        ssizehem: false,
        msizewaist: false,
        msizehips: false,
        msizerise: false,
        msizeinseam: false,
        msizethigh: false,
        msizehem: false,
        lsizewaist: false,
        lsizehips: false,
        lsizerise: false,
        lsizeinseam: false,
        lsizethigh: false,
        lsizehem: false,
        xlsizewaist: false,
        xlsizehips: false,
        xlsizerise: false,
        xlsizeinseam: false,
        xlsizethigh: false,
        xlsizehem: false,
        ssizeitems: false,
        msizeitems: false,
        lsizeitems: false,
        xlsizeitems: false,
      },
      loading: true,
      loadingType: {
        id: 0,
        type: 0
      }
    }
  },
  methods: {
    async fetchProduct(){
      const getData = await axios.get(`/api/editProduct/${this.id}`);
      this.item = getData.data;

      if(this.item){
        this.registerForm.productId = this.id;
        this.registerForm.displaystatus = this.item.display_status;
        this.registerForm.name = this.item.name;
        this.item.price = this.item.price.replace( /,/g , "");
        this.registerForm.price = Number(this.item.price);
        const colorValue = this.getColorAttribute(this.item.color);
        this.registerForm.color = colorValue;
        this.registerForm.content = this.item.content;
        this.registerForm.imgName = this.item.img_name;
        this.registerForm.category = String(this.item.category_status);
        if(this.item.category_status === 0){
          this.registerForm.topscategory = String(this.item.tops_genre_status);
          this.registerForm.ssizebody = this.item.ssize_body;
          this.registerForm.ssizeshoulder = this.item.ssize_shoulder;
          this.registerForm.ssizelength = this.item.ssize_length;
          this.registerForm.ssizesleeve = this.item.ssize_sleeve;
          this.registerForm.msizebody = this.item.msize_body;
          this.registerForm.msizeshoulder = this.item.msize_shoulder;
          this.registerForm.msizelength = this.item.msize_length;
          this.registerForm.msizesleeve = this.item.msize_sleeve;
          this.registerForm.lsizebody = this.item.lsize_body;
          this.registerForm.lsizeshoulder = this.item.lsize_shoulder;
          this.registerForm.lsizelength = this.item.lsize_length;
          this.registerForm.lsizesleeve = this.item.lsize_sleeve;
          this.registerForm.xlsizebody = this.item.xlsize_body;
          this.registerForm.xlsizeshoulder = this.item.xlsize_shoulder;
          this.registerForm.xlsizelength = this.item.xlsize_length;
          this.registerForm.xlsizesleeve = this.item.xlsize_sleeve;
        }else if(this.item.category_status === 1){
          this.registerForm.bottomscategory = String(this.item.bottoms_genre_status);
          this.registerForm.ssizewaist = this.item.ssize_waist;
          this.registerForm.ssizehips = this.item.ssize_hips;
          this.registerForm.ssizerise = this.item.ssize_rise;
          this.registerForm.ssizeinseam = this.item.ssize_inseam;
          this.registerForm.ssizethigh = this.item.ssize_thigh;
          this.registerForm.ssizehem = this.item.ssize_hem;
          this.registerForm.msizewaist = this.item.msize_waist;
          this.registerForm.msizehips = this.item.msize_hips;
          this.registerForm.msizerise = this.item.msize_rise;
          this.registerForm.msizeinseam = this.item.msize_inseam;
          this.registerForm.msizethigh = this.item.msize_thigh;
          this.registerForm.msizehem = this.item.msize_hem;
          this.registerForm.lsizewaist = this.item.lsize_waist;
          this.registerForm.lsizehips = this.item.lsize_hips;
          this.registerForm.lsizerise = this.item.lsize_rise;
          this.registerForm.lsizeinseam = this.item.lsize_inseam;
          this.registerForm.lsizethigh = this.item.lsize_thigh;
          this.registerForm.lsizehem = this.item.lsize_hem;
          this.registerForm.xlsizewaist = this.item.xlsize_waist;
          this.registerForm.xlsizehips = this.item.xlsize_hips;
          this.registerForm.xlsizerise = this.item.xlsize_rise;
          this.registerForm.xlsizeinseam = this.item.xlsize_inseam;
          this.registerForm.xlsizethigh = this.item.xlsize_thigh;
          this.registerForm.xlsizehem = this.item.xlsize_hem;
        }
        this.registerForm.ssizeitems = this.item.ssize_items;
        this.registerForm.msizeitems = this.item.msize_items;
        this.registerForm.lsizeitems = this.item.lsize_items;
        this.registerForm.xlsizeitems = this.item.xlsize_items;
        this.registerForm.ssizecheckitems = this.item.ssize_items;
        this.registerForm.msizecheckitems = this.item.msize_items;
        this.registerForm.lsizecheckitems = this.item.lsize_items;
        this.registerForm.xlsizecheckitems = this.item.xlsize_items;
        this.registerForm.ssizeregisteritems = this.item.ssize_register_items;
        this.registerForm.msizeregisteritems = this.item.msize_register_items;
        this.registerForm.lsizeregisteritems = this.item.lsize_register_items;
        this.registerForm.xlsizeregisteritems = this.item.xlsize_register_items;
      }

      this.loading = false;
    },
    async register(){
      this.loadingType.type = 1;
      this.loading = true;
      if(this.registerForm.imgData){
        const formData = new FormData();
        formData.append('file',this.registerForm.imgData);
        const imgResponse = await axios.post('/api/imgregister', formData);
        if(imgResponse.status !== OK){
            this.$store.commit('error/setCode', imgResponse.status);
            return false;
        }
      }
      const data = this.registerForm;
      const productResponse = await axios.post('/api/producteditregister', data);
      if(productResponse.status !== OK){
          this.$store.commit('error/setCode', productResponse.status);
          return false;
      }
      this.loading = false;
      this.$router.push('/adminProductList');
    },
    onImgset(e){
      this.registerForm.imgData = e.target.files[0];
      if(this.registerForm.imgData){
        this.registerForm.img = this.registerForm.imgData.name;
        // imgencodeurl
        const reader = new FileReader();
        reader.onload = (e)=>{
          this.registerForm.imgencodeurl = reader.result;
        }
        reader.readAsDataURL(this.registerForm.imgData);
      }
    },
    chengeDisplayStatus(){
      if(this.registerForm.displaystatus === 0){
        this.registerForm.displaystatus = 1;
      }else if(this.registerForm.displaystatus === 1){
        this.registerForm.displaystatus = 0;
      }
    },
    async onValidate () {
      this.loadingType.type = 1;
      this.loading = true;
      await this.$store.dispatch('auth/productregistervalidate', this.registerForm);

      //statuscode=200だったら
      if(this.apiStatus){

        if(this.registerForm.category === "0"){
          this.registerForm.categoryName = "トップス";
          if(this.registerForm.topscategory === "0"){
              this.registerForm.categoryTypeName = "Tシャツ";
          }else if(this.registerForm.topscategory  === "1"){
              this.registerForm.categoryTypeName = "シャツ";
          }else if(this.registerForm.topscategory  === "2"){
              this.registerForm.categoryTypeName = "ポロシャツ";
          }
        }else if(this.registerForm.category === "1"){
          this.registerForm.categoryName = "ボトムス";
          if(this.registerForm.bottomscategory === "0"){
              this.registerForm.categoryTypeName = "デニム";
          }else if(this.registerForm.bottomscategory  === "1"){
              this.registerForm.categoryTypeName = "チノパンツ";
          }else if(this.registerForm.bottomscategory  === "2"){
              this.registerForm.categoryTypeName = "スラックス";
          }
        }

        if(this.registerForm.color === "0"){
            this.registerForm.colorname = "BLACK";
        }else if(this.registerForm.color  === "1"){
            this.registerForm.colorname = "WHITE";
        }else if(this.registerForm.color  === "2"){
            this.registerForm.colorname = "GRAY";
        }else if(this.registerForm.color  === "3"){
            this.registerForm.colorname = "RED";
        }else if(this.registerForm.color  === "4"){
            this.registerForm.colorname = "BLUE";
        }else if(this.registerForm.color  === "5"){
            this.registerForm.colorname = "GREEN";
        }else if(this.registerForm.color  === "6"){
            this.registerForm.colorname = "YELLOW";
        }

        if(Number(this.registerForm.ssizeitems) === 0){
            this.registerForm.sstock = "在庫なし";
            this.registerForm.Sstock = false;
        }else if(Number(this.registerForm.ssizeitems) <= 10){
            this.registerForm.sstock = "在庫残りわずか";
        }else{
            this.registerForm.sstock = "在庫あり";
        }

        if(Number(this.registerForm.msizeitems) === 0){
            this.registerForm.mstock = "在庫なし";
            this.registerForm.Mstock = false;
        }else if(Number(this.registerForm.msizeitems) <= 10){
            this.registerForm.mstock = "在庫残りわずか";
        }else{
            this.registerForm.mstock = "在庫あり";
        }

        if(Number(this.registerForm.lsizeitems) === 0){
            this.registerForm.lstock = "在庫なし";
            this.registerForm.Lstock = false;
        }else if(Number(this.registerForm.lsizeitems) <= 10){
            this.registerForm.lstock = "在庫残りわずか";
        }else{
            this.registerForm.lstock = "在庫あり";
        }

        if(Number(this.registerForm.xlsizeitems) === 0){
            this.registerForm.xlstock = "在庫なし";
            this.registerForm.XLstock = false;
        }else if(Number(this.registerForm.xlsizeitems) <= 10){
            this.registerForm.xlstock = "在庫残りわずか";
        }else{
            this.registerForm.xlstock = "在庫あり";
        }

        this.checkImage = true;
      }
      this.loading = false;
    },
    onclose({booleancheck, check}){
      this.checkImage = booleancheck;
      this.inputDisabled = check;
      this.button = 2;
    },
    onEdit(){
      this.inputDisabled = false;
      this.button = 1;
    },
    clearError(){
      this.$store.commit('auth/setProductRegisterErrorMessages', null);
    },
    getColorAttribute($value){
        if($value === "BLACK"){
            return "0";
        }else if($value === "WHITE"){
            return "1";
        }else if($value === "GRAY"){
            return "2";
        }else if($value === "RED"){
            return "3";
        }else if($value === "BLUE"){
            return "4";
        }else if($value === "GREEN"){
            return "5";
        }else if($value === "YELLOW"){
            return "6";
        }
    }
  },
  computed: {
    apiStatus(){
      return this.$store.state.auth.apiStatus;
    },
    registerErrors(){
      const msg = this.$store.state.auth.productRegisterErrorMessages;
      if(msg){
        if(msg.name){
          this.registererror.name = true;
        }else{
          this.registererror.name = false;
        }
        if(msg.price){
          this.registererror.price = true;
        }else{
          this.registererror.price = false;
        }
        if(msg.content){
          this.registererror.content = true;
        }else{
          this.registererror.content = false;
        }
        if(msg.ssizebody){
          this.registererror.ssizebody = true;
        }else{
          this.registererror.ssizebody = false;
        }
        if(msg.ssizeshoulder){
          this.registererror.ssizeshoulder = true;
        }else{
          this.registererror.ssizeshoulder = false;
        }
        if(msg.ssizelength){
          this.registererror.ssizelength = true;
        }else{
          this.registererror.ssizelength = false;
        }
        if(msg.ssizesleeve){
          this.registererror.ssizesleeve = true;
        }else{
          this.registererror.ssizesleeve = false;
        }
        if(msg.msizebody){
          this.registererror.msizebody = true;
        }else{
          this.registererror.msizebody = false;
        }
        if(msg.msizeshoulder){
          this.registererror.msizeshoulder = true;
        }else{
          this.registererror.msizeshoulder = false;
        }
        if(msg.msizelength){
          this.registererror.msizelength = true;
        }else{
          this.registererror.msizelength = false;
        }
        if(msg.msizesleeve){
          this.registererror.msizesleeve = true;
        }else{
          this.registererror.msizesleeve = false;
        }
        if(msg.lsizebody){
          this.registererror.lsizebody = true;
        }else{
          this.registererror.lsizebody = false;
        }
        if(msg.lsizeshoulder){
          this.registererror.lsizeshoulder = true;
        }else{
          this.registererror.lsizeshoulder = false;
        }
        if(msg.lsizelength){
          this.registererror.lsizelength = true;
        }else{
          this.registererror.lsizelength = false;
        }
        if(msg.lsizesleeve){
          this.registererror.lsizesleeve = true;
        }else{
          this.registererror.lsizesleeve = false;
        }
        if(msg.xlsizebody){
          this.registererror.xlsizebody = true;
        }else{
          this.registererror.xlsizebody = false;
        }
        if(msg.xlsizeshoulder){
          this.registererror.xlsizeshoulder = true;
        }else{
          this.registererror.xlsizeshoulder = false;
        }
        if(msg.xlsizelength){
          this.registererror.xlsizelength = true;
        }else{
          this.registererror.xlsizelength = false;
        }
        if(msg.xlsizesleeve){
          this.registererror.xlsizesleeve = true;
        }else{
          this.registererror.xlsizesleeve = false;
        }
        if(msg.ssizewaist){
          this.registererror.ssizewaist = true;
        }else{
          this.registererror.ssizewaist = false;
        }
        if(msg.ssizehips){
          this.registererror.ssizehips = true;
        }else{
          this.registererror.ssizehips = false;
        }
        if(msg.ssizerise){
          this.registererror.ssizerise = true;
        }else{
          this.registererror.ssizerise = false;
        }
        if(msg.ssizeinseam){
          this.registererror.ssizeinseam = true;
        }else{
          this.registererror.ssizeinseam = false;
        }
        if(msg.ssizethigh){
          this.registererror.ssizethigh = true;
        }else{
          this.registererror.ssizethigh = false;
        }
        if(msg.ssizehem){
          this.registererror.ssizehem = true;
        }else{
          this.registererror.ssizehem = false;
        }
        if(msg.msizewaist){
          this.registererror.msizewaist = true;
        }else{
          this.registererror.msizewaist = false;
        }
        if(msg.msizehips){
          this.registererror.msizehips = true;
        }else{
          this.registererror.msizehips = false;
        }
        if(msg.msizerise){
          this.registererror.msizerise = true;
        }else{
          this.registererror.msizerise = false;
        }
        if(msg.msizeinseam){
          this.registererror.msizeinseam = true;
        }else{
          this.registererror.msizeinseam = false;
        }
        if(msg.msizethigh){
          this.registererror.msizethigh = true;
        }else{
          this.registererror.msizethigh = false;
        }
        if(msg.msizehem){
          this.registererror.msizehem = true;
        }else{
          this.registererror.msizehem = false;
        }
        if(msg.lsizewaist){
          this.registererror.lsizewaist = true;
        }else{
          this.registererror.lsizewaist = false;
        }
        if(msg.lsizehips){
          this.registererror.lsizehips = true;
        }else{
          this.registererror.lsizehips = false;
        }
        if(msg.lsizerise){
          this.registererror.lsizerise = true;
        }else{
          this.registererror.lsizerise = false;
        }
        if(msg.lsizeinseam){
          this.registererror.lsizeinseam = true;
        }else{
          this.registererror.lsizeinseam = false;
        }
        if(msg.lsizethigh){
          this.registererror.lsizethigh = true;
        }else{
          this.registererror.lsizethigh = false;
        }
        if(msg.lsizehem){
          this.registererror.lsizehem = true;
        }else{
          this.registererror.lsizehem = false;
        }
        if(msg.xlsizewaist){
          this.registererror.xlsizewaist = true;
        }else{
          this.registererror.xlsizewaist = false;
        }
        if(msg.xlsizehips){
          this.registererror.xlsizehips = true;
        }else{
          this.registererror.xlsizehips = false;
        }
        if(msg.xlsizerise){
          this.registererror.xlsizerise = true;
        }else{
          this.registererror.xlsizerise = false;
        }
        if(msg.xlsizeinseam){
          this.registererror.xlsizeinseam = true;
        }else{
          this.registererror.xlsizeinseam = false;
        }
        if(msg.xlsizethigh){
          this.registererror.xlsizethigh = true;
        }else{
          this.registererror.xlsizethigh = false;
        }
        if(msg.xlsizehem){
          this.registererror.xlsizehem = true;
        }else{
          this.registererror.xlsizehem = false;
        }
         if(msg.ssizeitems){
          this.registererror.ssizeitems = true;
        }else{
          this.registererror.ssizeitems = false;
        }
        if(msg.msizeitems){
          this.registererror.msizeitems = true;
        }else{
          this.registererror.msizeitems = false;
        }
        if(msg.lsizeitems){
          this.registererror.lsizeitems = true;
        }else{
          this.registererror.lsizeitems = false;
        }
        if(msg.xlsizeitems){
          this.registererror.xlsizeitems = true;
        }else{
          this.registererror.xlsizeitems = false;
        }
      }
      return msg;
    }
  },
  created(){
    this.clearError();
  },
  watch: {
    $route: {
        async handler(){
            await this.fetchProduct();
        },
        immediate: true
    }
  }
}
</script>