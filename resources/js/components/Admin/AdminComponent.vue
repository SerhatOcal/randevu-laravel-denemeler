<template>
    <div class="container">
        <div class="row">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="onaybekleyen-tab" data-toggle="tab" href="#onaybekleyen" role="tab" aria-controls="onaybekleyen" aria-selected="false">Onay Bekleyenler</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="gunugelen-tab" data-toggle="tab" href="#gunugelen" role="tab" aria-controls="gunugelen" aria-selected="false">Günu Gelen Randevular</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="gelecek-tab" data-toggle="tab" href="#gelecek" role="tab" aria-controls="gelecek" aria-selected="false">Gelecek Randevular</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="gecmis-tab" data-toggle="tab" href="#gecmis" role="tab" aria-controls="gecmis" aria-selected="false">Geçmiş Randevular</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="iptal-tab" data-toggle="tab" href="#iptal" role="tab" aria-controls="iptal" aria-selected="false">İptal Edilen Randevular</a>
                </li>
            </ul>
        </div>
        <div class="tab-content" id="myTabContent" style="margin-top: 10px;">
            <div class="tab-pane fade show active" id="onaybekleyen" role="tabpanel" aria-labelledby="onaybekleyen-tab">
                <div class="row">
                    <admin-randevu-listesi :data="onay_bekleyenler.data" @fncUpdate="fncUpdate" @fncIptal="fncIptal"></admin-randevu-listesi>
                </div>
                <div class="row">
                    <div class="col-md-12" style="margin-top:10px;">
                        <pagination :data="onay_bekleyenler" @pagination-change-page="onay_bekleyenlerData"></pagination>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="gunugelen" role="tabpanel" aria-labelledby="gunugelen-tab">
                <div class="row">
                    <admin-randevu-listesi :data="gunu_gelenler.data" @fncUpdate="fncUpdate" @fncIptal="fncIptal"></admin-randevu-listesi>
                </div>
                <div class="row">
                    <div class="col-md-12" style="margin-top:10px;">
                        <pagination :data="gunu_gelenler" @pagination-change-page="gunu_gelenlerData"></pagination>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="gelecek" role="tabpanel" aria-labelledby="gelecek-tab">
                <div class="row">
                    <admin-randevu-listesi :data="randevu_listesi.data" @fncUpdate="fncUpdate" @fncIptal="fncIptal"></admin-randevu-listesi>
                </div>
                <div class="row">
                    <div class="col-md-12" style="margin-top:10px;">
                        <pagination :data="randevu_listesi" @pagination-change-page="randevu_listesiData"></pagination>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="gecmis" role="tabpanel" aria-labelledby="gecmis-tab">
                <div class="row">
                    <admin-randevu-listesi :data="gunu_gecenler.data" @fncUpdate="fncUpdate" @fncIptal="fncIptal"></admin-randevu-listesi>
                </div>
                <div class="row">
                    <div class="col-md-12" style="margin-top:10px;">
                        <pagination :data="gunu_gecenler" @pagination-change-page="gunu_gecenlerData"></pagination>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="iptal" role="tabpanel" aria-labelledby="iptal-tab">
                <div class="row">
                    <admin-randevu-listesi :data="iptal_edilenler.data" @fncUpdate="fncUpdate" @fncIptal="fncIptal"></admin-randevu-listesi>
                </div>
                <div class="row">
                    <div class="col-md-12" style="margin-top:10px;">
                        <pagination :data="iptal_edilenler" @pagination-change-page="iptal_edilenlerData"></pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import io from "socket.io-client";
const socket = io("http://localhost:3000");
export default {
    data(){
        return {
            onay_bekleyenler:{
                data:[]
            },
            gunu_gelenler:{
                data:[]
            },
            gunu_gecenler:{
                data:[]
            },
            randevu_listesi:{
                data:[]
            },
            iptal_edilenler:{
                data:[]
            },
        }
    },
    created() {
        this.getData();
        socket.on('admin_randevu_listesi', (data) => {
            this.getData();
        });
    },
    methods: {
        fncUpdate(id){
            axios.post('http://proje.com/api/admin/islemler', {
                isActive : 1,
                id:id
            }).then((res) => {
                this.getData();
            });
        },
        fncIptal(id){
            axios.post('http://proje.com/api/admin/islemler', {
                isActive : 2,
                id:id
            }).then((res) => {
                this.getData();
            });
        },
        getData(url='http://proje.com/api/admin/randevu-islemleri') {
            axios.get(url)
                .then((res) => {
                    this.onay_bekleyenler 	= res.data.onay_bekleyenler;
                    this.gunu_gelenler 		= res.data.gunu_gelenler;
                    this.gunu_gecenler 		= res.data.gunu_gecenler;
                    this.randevu_listesi 	= res.data.randevu_listesi;
                    this.iptal_edilenler 	= res.data.iptal_edilenler;
                });
        },
        onay_bekleyenlerData(page){
            this.getData('http://proje.com/api/admin/randevu-islemleri/?onay_bekleyenler_page=' + page);
        },
        gunu_gelenlerData(page){
            this.getData('http://proje.com/api/admin/randevu-islemleri/?gunu_gelenler_page=' + page);
        },
        gunu_gecenlerData(page){
            this.getData('http://proje.com/api/admin/randevu-islemleri?gunu_gecenler_page=' + page);
        },
        randevu_listesiData(page){
            this.getData('http://proje.com/api/admin/randevu-islemleri?randevu_listesi_page=' + page);
        },
        iptal_edilenlerData(page){
            this.getData('http://proje.com/api/admin/randevu-islemleri?iptal_edilenler_page=' + page);
        }
    }
}
</script>