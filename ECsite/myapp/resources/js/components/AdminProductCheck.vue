<template>
    <div v-if="item">
        <div class="check_modal">
            <h2 class="check_title">表示イメージ</h2>
            <div v-if="item" class="photo-detail">
                <figure class="photo-detail__pane photo-detail__image">
                    <img class="check_photo" :src="item.imgencodeurl" :alt="`Photo by ${item.img}`">
                </figure>
                <div class="photo-detail__pane">
                    <div class="photo-detail__block">
                        <h2 class="photo-detail__title">
                            {{item.name}}
                        </h2>
                        <button class="photo-detail__like check-Likebutton" disabled title="お気に入り">
                            <i class="icon ion-md-heart"></i>
                        </button>
                    </div>
                    <h2 class="photo-detail__color">
                        {{item.colorname}}
                    </h2>
                    <h2 class="photo-detail__price">
                        ¥{{Number(item.price).toLocaleString()}}
                    </h2>
                    <div class="photo-detail__sizeBlock">
                        <span class="photo-detail__size">S / {{item.sstock}}</span>
                        <button v-if="item.Sstock" class="button button--cart check-button" disabled>
                            <i class="fas fa-shopping-cart"></i>カートへ入れる
                        </button>
                        <span v-else class="photo-detail__notsize">完売しました</span>
                    </div>
                    <div class="photo-detail__sizeBlock">
                        <span class="photo-detail__size">M / {{item.mstock}}</span>
                        <button v-if="item.Mstock" class="button button--cart check-button" disabled>
                            <i class="fas fa-shopping-cart"></i>カートへ入れる
                        </button>
                        <span v-else class="photo-detail__notsize">完売しました</span>
                    </div>
                    <div class="photo-detail__sizeBlock">
                        <span class="photo-detail__size">L / {{item.lstock}}</span>
                        <button v-if="item.Lstock" class="button button--cart check-button" disabled>
                            <i class="fas fa-shopping-cart"></i>カートへ入れる
                        </button>
                        <span v-else class="photo-detail__notsize">完売しました</span>
                    </div>
                    <div class="photo-detail__sizeBlock lastBlock">
                        <span class="photo-detail__size">XL / {{item.xlstock}}</span>
                        <button v-if="item.XLstock" class="button button--cart check-button" disabled>
                            <i class="fas fa-shopping-cart"></i>カートへ入れる
                        </button>
                        <span v-else class="photo-detail__notsize">完売しました</span>
                    </div>
                    <ul class="tab">
                        <li class="tab__item" :class="{'tab__item--active': tab === 1 }" @click="tab = 1">
                            アイテム説明
                        </li>
                        <li class="tab__item" :class="{'tab__item--active': tab === 2 }" @click="tab = 2">
                            サイズ詳細
                        </li>
                    </ul>
                    <div class="panel" v-show="tab === 1">
                        <h2 class="photo-detail__content check_detail__content">{{item.content}}</h2>
                    </div>
                    <div class="panel" v-show="tab === 2">
                        <table v-if="item.category === '0'" class="size_table">
                            <thead>
                                <tr>
                                    <th>サイズ</th>
                                    <th>身幅</th>
                                    <th>肩幅</th>
                                    <th>着丈</th>
                                    <th>そで丈</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>S</td>
                                    <td>{{item.ssizebody}}</td>
                                    <td>{{item.ssizeshoulder}}</td>
                                    <td>{{item.ssizelength}}</td>
                                    <td>{{item.ssizesleeve}}</td>
                                </tr>
                                <tr>
                                    <td>M</td>
                                    <td>{{item.msizebody}}</td>
                                    <td>{{item.msizeshoulder}}</td>
                                    <td>{{item.msizelength}}</td>
                                    <td>{{item.msizesleeve}}</td>
                                </tr>
                                <tr>
                                    <td>L</td>
                                    <td>{{item.lsizebody}}</td>
                                    <td>{{item.lsizeshoulder}}</td>
                                    <td>{{item.lsizelength}}</td>
                                    <td>{{item.lsizesleeve}}</td>
                                </tr>
                                <tr>
                                    <td>X</td>
                                    <td>{{item.xlsizebody}}</td>
                                    <td>{{item.xlsizeshoulder}}</td>
                                    <td>{{item.xlsizelength}}</td>
                                    <td>{{item.xlsizesleeve}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <table v-else-if="item.category === '1'" class="size_table">
                            <thead>
                                <tr>
                                    <th>サイズ</th>
                                    <th>ウエスト</th>
                                    <th>お尻</th>
                                    <th>股上</th>
                                    <th>股下</th>
                                    <th>太股</th>
                                    <th>すそ周り</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>S</td>
                                    <td>{{item.ssizewaist}}</td>
                                    <td>{{item.ssizehips}}</td>
                                    <td>{{item.ssizerise}}</td>
                                    <td>{{item.ssizeinseam}}</td>
                                    <td>{{item.ssizethigh}}</td>
                                    <td>{{item.ssizehem}}</td>
                                </tr>
                                <tr>
                                    <td>M</td>
                                    <td>{{item.msizewaist}}</td>
                                    <td>{{item.msizehips}}</td>
                                    <td>{{item.msizerise}}</td>
                                    <td>{{item.msizeinseam}}</td>
                                    <td>{{item.msizethigh}}</td>
                                    <td>{{item.msizehem}}</td>
                                </tr>
                                <tr>
                                    <td>L</td>
                                    <td>{{item.lsizewaist}}</td>
                                    <td>{{item.lsizehips}}</td>
                                    <td>{{item.lsizerise}}</td>
                                    <td>{{item.lsizeinseam}}</td>
                                    <td>{{item.lsizethigh}}</td>
                                    <td>{{item.lsizehem}}</td>
                                </tr>
                                <tr>
                                    <td>X</td>
                                    <td>{{item.xlsizewaist}}</td>
                                    <td>{{item.xlsizehips}}</td>
                                    <td>{{item.xlsizerise}}</td>
                                    <td>{{item.xlsizeinseam}}</td>
                                    <td>{{item.xlsizethigh}}</td>
                                    <td>{{item.xlsizehem}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="caution">
                ※在庫数に伴う表示について
                <h3 class="cautions">在庫数が11個以上の場合「在庫あり」と表示されます。</h3>
                <h3 class="cautions">在庫数が10個以下の場合「在庫残りわずか」と表示されます。</h3>
                <h3 class="cautions">在庫数が0個の場合「在庫なし」と表示されます。</h3>
            </div>
            <div class="check_content">
                <h2 class="check_title">入力内容</h2>
                <table class="check_table">
                    <thead>
                        <tr>
                            <th>項目</th>
                            <th>入力内容</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>商品名</td>
                            <td>{{item.name}}</td>
                        </tr>
                        <tr>
                            <td>金額(税込)</td>
                            <td>¥{{Number(item.price).toLocaleString()}}</td>
                        </tr>
                        <tr>
                            <td>色</td>
                            <td>{{item.colorname}}</td>
                        </tr>
                        <tr>
                            <td>商品説明</td>
                            <td class="check__content">{{item.content}}</td>
                        </tr>
                        <tr>
                            <td>カテゴリー</td>
                            <td>{{item.categoryName}}</td>
                        </tr>
                        <tr v-if="item.category === '0'">
                            <td>トップスカテゴリー</td>
                            <td>{{item.categoryTypeName}}</td>
                        </tr>
                        <tr v-if="item.category === '0'">
                            <td>Sサイズ</td>
                            <td>身幅:{{item.ssizebody}}　肩幅:{{item.ssizeshoulder}}　着丈:{{item.ssizelength}}　そで丈:{{item.ssizesleeve}}</td>
                        </tr>
                        <tr v-if="item.category === '0'">
                            <td>Mサイズ</td>
                            <td>身幅:{{item.msizebody}}　肩幅:{{item.msizeshoulder}}　着丈:{{item.msizelength}}　そで丈:{{item.msizesleeve}}</td>
                        </tr>
                        <tr v-if="item.category === '0'">
                            <td>Lサイズ</td>
                            <td>身幅:{{item.lsizebody}}　肩幅:{{item.lsizeshoulder}}　着丈:{{item.lsizelength}}　そで丈:{{item.lsizesleeve}}</td>
                        </tr>
                        <tr v-if="item.category === '0'">
                            <td>XLサイズ</td>
                            <td>身幅:{{item.xlsizebody}}　肩幅:{{item.xlsizeshoulder}}　着丈:{{item.xlsizelength}}　そで丈:{{item.xlsizesleeve}}</td>
                        </tr>
                        <tr v-if="item.category === '1'">
                            <td>ボトムスカテゴリー</td>
                            <td>{{item.categoryTypeName}}</td>
                        </tr>
                        <tr v-if="item.category === '1'">
                            <td>Sサイズ</td>
                            <td>ウエスト:{{item.ssizewaist}}　お尻:{{item.ssizehips}}　股上:{{item.ssizerise}}　股下:{{item.ssizeinseam}}　太股:{{item.ssizethigh}}　すそ周り:{{item.ssizehem}}</td>
                        </tr>
                        <tr v-if="item.category === '1'">
                            <td>Mサイズ</td>
                            <td>ウエスト:{{item.msizewaist}}　お尻:{{item.msizehips}}　股上:{{item.msizerise}}　股下:{{item.msizeinseam}}　太股:{{item.msizethigh}}　すそ周り:{{item.msizehem}}</td>
                        </tr>
                        <tr v-if="item.category === '1'">
                            <td>Lサイズ</td>
                            <td>ウエスト:{{item.lsizewaist}}　お尻:{{item.lsizehips}}　股上:{{item.lsizerise}}　股下:{{item.lsizeinseam}}　太股:{{item.lsizethigh}}　すそ周り:{{item.lsizehem}}</td>
                        </tr>
                        <tr v-if="item.category === '1'">
                            <td>XLサイズ</td>
                            <td>ウエスト:{{item.xlsizewaist}}　お尻:{{item.xlsizehips}}　股上:{{item.xlsizerise}}　股下:{{item.xlsizeinseam}}　太股:{{item.xlsizethigh}}　すそ周り:{{item.xlsizehem}}</td>
                        </tr>
                        <tr>
                            <td>在庫数</td>
                            <td>Sサイズ:{{item.ssizeitems}}　Mサイズ:{{item.msizeitems}}　Lサイズ:{{item.lsizeitems}}　XLサイズ:{{item.xlsizeitems}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="button_double">
                <button class="button check_form_button" @click="close">
                    閉じる
                </button>
            </div>
        </div>
    </div>
</template>
<script>
import { OK } from '../util';

export default {
    props: {
        item: {
            type: Object,
            required: true
        }
    },
    data(){
        return {
            tab: 1,
        }
    },
    methods: {
        close(){
            this.$emit('close', {
                booleancheck: false,
                check: true,
            })
        }
    }
}
</script>