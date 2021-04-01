<template>
    <div class="container">
        <div class="row">
            <admin-randevu-listesi :data="items.data"></admin-randevu-listesi>
        </div>
        <div class="row">
            <div class="col-md-12" style="margin-top:10px;">
                <pagination :data="items" @pagination-change-page="getData"></pagination>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            items: {
                data: []
            }
        }
    },
    created(){
        this.getData();
    },
    mounted(){
    },
    methods:{
        getData(page){
            if (typeof page === 'undefined') {
                page = 1 ;
            }
            axios.get('http://proje.com/api/admin/iptal-edilen-randevular/?page=' + page)
                .then((res) =>{
                    this.items = res.data;
                });
        }
    }
}
</script>
