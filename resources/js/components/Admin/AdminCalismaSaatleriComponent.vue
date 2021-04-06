<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <admin-calisma-saatleri-gun day="monday" @fncekle="fncekle" title="Pazartesi" :data="monday"></admin-calisma-saatleri-gun>
                <admin-calisma-saatleri-gun day="tuesday" @fncekle="fncekle" title="Salı" :data="tuesday"></admin-calisma-saatleri-gun>
                <admin-calisma-saatleri-gun day="wednesday" @fncekle="fncekle" title="Çarşamba" :data="wednesday"></admin-calisma-saatleri-gun>
                <admin-calisma-saatleri-gun day="thursday" @fncekle="fncekle" title="Perşembe" :data="thursday"></admin-calisma-saatleri-gun>
                <admin-calisma-saatleri-gun day="friday" @fncekle="fncekle" title="Cuma" :data="friday"></admin-calisma-saatleri-gun>
                <admin-calisma-saatleri-gun day="saturday" @fncekle="fncekle" title="Cumartesi" :data="saturday"></admin-calisma-saatleri-gun>
                <admin-calisma-saatleri-gun day="sunday" @fncekle="fncekle" title="Pazar" :data="sunday"></admin-calisma-saatleri-gun>
                <button class="btn btn-success" @click="fncKaydet">Kaydet</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            monday: [],
            tuesday: [],
            wednesday: [],
            thursday: [],
            friday: [],
            saturday: [],
            sunday: [],
        }
    },
    created() {
        axios.get("http://proje.com/api/calisma-saatleri-listesi").then((res) => {
            this.monday    = (typeof res.data.monday !== "undefined") ? res.data.monday : [];
            this.tuesday   = (typeof res.data.tuesday !== "undefined") ? res.data.tuesday : [];
            this.wednesday = (typeof res.data.wednesday !== "undefined") ? res.data.wednesday : [];
            this.thursday  = (typeof res.data.thursday !== "undefined") ? res.data.thursday : [];
            this.friday    = (typeof res.data.friday !== "undefined") ? res.data.friday : [];
            this.saturday  = (typeof res.data.saturday !== "undefined") ? res.data.saturday : [];
            this.sunday    = (typeof res.data.sunday !== "undefined") ? res.data.sunday : [];
        });
    },
    methods: {
        fncekle: function(data){
            this[data.day].push(data.text);
        },
        fncKaydet: function () {
            axios.post('http://proje.com/api/calisma-saatleri-kaydet', {
                monday: this.monday,
                tuesday: this.tuesday,
                wednesday: this.wednesday,
                thursday: this.thursday,
                friday: this.friday,
                saturday: this.saturday,
                sunday: this.sunday
            }).then((res) => {
                console.log(res);
            });
        }
    }
}
</script>