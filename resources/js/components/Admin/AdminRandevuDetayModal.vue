<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <div class="modal-header">
                        <h3 slot="header">{{data.fullName}}</h3>
                        <button class="btn btn-danger" @click="$emit('close')">X</button>
                    </div>
                    <div class="modal-body">
                        <slot name="body">
                            <div class="col-md-12">
                                <div>
                                    <span>Telefon</span>:<span>{{data.phone}}</span>
                                </div>
                                <div>
                                    <span>Email</span>:<span>{{data.email}}</span>
                                </div>
                                <div>
                                    <span>Tarih</span>:<span>{{data.date}}</span>
                                </div>
                                <div>
                                    <span>Saat</span>:<span>{{data.calismaSaatleri}}</span>
                                </div>
                                <div>
                                    <span>Bildirim Tipi</span>:<span>{{data.bildirim_tipi}}</span>
                                </div>
                                <div>
                                    <span>Not</span>:<span>{{data.note}}</span>
                                </div>
                            </div>
                        </slot>
                        <div class="col-md-12" style="border-top:1px solid #ddd; padding:10px; 0px;">
                            <div v-for="item in note">{{item.text}}</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <slot name="footer">
                            <div class="col-md-12">
                                <textarea v-model="text" id="" cols="46" rows="5"></textarea>
                            </div>
                            <div>
                                <button @click="fncKaydet" class="btn btn-primary">Kaydet</button>
                            </div>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>
<script>
export default {
    props:["modalId"],
    data(){
        return {
            data:[],
            note:[],
            text:""
        }
    },
    http    :   {
        headers: {
            'X-CSRF-Token': $('meta[name=_token]').attr('content')
        }
    },
    created() {
        this.getData();
    },
    methods:{
        fncKaydet: function (){
            axios.post("http://proje.com/api/admin/detay-not",{
                    id:this.modalId,
                    text:this.text
            }).then((res) =>{
                if(res.status){
                    this.text = "";
                    this.getData();
                }
            })
        },
        getData:function (){
            axios.get("http://proje.com/api/admin/randevu-detay/" + this.modalId)
                .then((res) =>{
                    this.data = res.data.data;
                    this.note = res.data.note;
                });
        }
    }
}
</script>