<template>
<div class="container">
    <div class="row">
        <div v-for="item in data" class="col-md-4">
            <div class="card">
                <div class="card-header">Randevu
					<span class="btn btn-sm btn-primary pull-right " v-if="item.isActive == 0">Yeni</span>
					<span class="btn btn-sm btn-success pull-right " v-if="item.isActive == 1">Onaylı </span>
					<span class="btn btn-sm btn-danger pull-right " v-if="item.isActive == 2">İptal Edilen</span>
				</div>
                <div class="card-body">
                    <p class="card-text">{{item.fullName}}</p>
                    <p class="card-text">{{item.date}}</p>
                    <p class="card-text">{{item.calismaSaatleri}}</p>
                    <p class="card-text">{{item.calismaSaatleri}}</p>
                </div>
                <div style="padding:10px;" v-if="item.isActive == 0">
                    <button class="btn btn-sm btn-outline-success" @click="fncOnayla(item.id)">Onayla</button>
                    <button class="btn btn-sm btn-outline-danger" @click="fncIptal(item.id)">İptal</button>
                </div>
            </div>
        </div>
    </div>
</div>

</template>

<script>
export default {
    props:['data'],
    created() {
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
        }

    }
}
</script>
