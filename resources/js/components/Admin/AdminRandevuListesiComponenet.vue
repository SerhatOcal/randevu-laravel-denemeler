<template>
<div class="container">
    <div class="row">
        <div v-for="item in data" @click="fncModal(item.id)" class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header">Randevu
					<span class="btn btn-sm btn-primary pull-right " v-if="item.isActive == 0">Yeni</span>
					<span class="btn btn-sm btn-success pull-right " v-if="item.isActive == 1">Onaylı </span>
					<span class="btn btn-sm btn-danger pull-right " v-if="item.isActive == 2">İptal Edilen</span>
				</div>
                <div class="card-body">
                    <p class="card-text">{{item.fullName}}</p>
                    <p class="card-text">{{item.date}}</p>
                    <p class="card-text">{{item.CalismaSaatleri}}</p>
                </div>
                <div style="padding:10px;" v-if="item.isActive == 0">
                    <button class="btn btn-sm btn-outline-success" @click="fncOnayla(item.id)">Onayla</button>
                    <button class="btn btn-sm btn-outline-danger" @click="fncIptal(item.id)">İptal</button>
                </div>
            </div>
        </div>
    </div>
    <admin-randevu-modal :modalId="showModalId" v-if="showModal" @close="showModal = false"></admin-randevu-modal>
</div>

</template>

<script>
export default {
    props:['data'],
    data(){
      return {
          showModalId: 0,
          showModal: false
      }
    },
    http    :   {
        headers: {
            'X-CSRF-Token': $('meta[name=_token]').attr('content')
        }
    },
    methods:{
        fncOnayla:function (id){
        	this.$emit("fncUpdate",id);
        },
        fncIptal:function (id) {
			this.$emit("fncIptal",id);
        },
        fncModal: function (id){
            this.showModalId = id;
            this.showModal = true;
        }
    }
}
</script>
