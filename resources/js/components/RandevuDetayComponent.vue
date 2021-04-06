<template>
    <div class="container">
        <div class="complateForm">
            <h4 class="text-center mb-5">Randevu bilgilerinize ulaşmak içim lütfen size gönderilen Randevu Kodunu giriniz !</h4>
            <div class="row">
                <div class="col-md-12">
                    <li v-for="i in errors" class="alert alert-danger">{{i}}</li>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" class="form-control" v-model="code" placeholder="Randevu Kodu">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <button class="btn btn-success" v-on:click="fncAra">Randevu Ara</button>
                </div>
            </div>
        </div>
        <div class="row" v-if="complateForm">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header"><h4>Randevu Bilgileri</h4></div>
                    <div class="card-body">
                        <p class="card-text"> Tarih: {{info.date}} </p>
                        <p class="card-text"> Saat: {{info.calismaSaatleri}} </p>
                        <p class="card-text"> Bildirim Şekli: {{info.bildirim_tipi}}</p>
                    </div>
                    <div class="row">
                        <div class="col-md-12" v-for="item in note">
                            <span>{{item.text}}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
export default{
    data(){
        return {
            errors:[],
            code:null,
            complateForm: false,
            info:[],
            note:[]

        }
    },
    
    methods:{
        fncAra: function () {
            if (this.code != null){
                axios.post("http://proje.com/api/randevu-detay",{
                    code:this.code
                }).then((res) => {
                    if (res.data.status){
                        this.info = res.data.info;
                        this.note = res.data.note;
                        this.complateForm =  true;
                    }else{
                        this.errors = [];
                        this.errors.push(res.data.message);
                    }
                }).catch((e) => {
                   console.log(e);
                });
            }
            this.errors = [];
            if (this.code == null){
                this.errors.push("Randevu Kodu Boş Olamaz !");
            }
        }
    }
}
</script>