<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <admin-calisma-saatleri-gun title="Pazartesi" :data="pazartesi"></admin-calisma-saatleri-gun>
                <admin-calisma-saatleri-gun title="Salı" :data="sali"></admin-calisma-saatleri-gun>
                <admin-calisma-saatleri-gun title="Çarşamba" :data="carsamba"></admin-calisma-saatleri-gun>
                <admin-calisma-saatleri-gun title="Perşembe" :data="persembe"></admin-calisma-saatleri-gun>
                <admin-calisma-saatleri-gun title="Cuma" :data="cuma"></admin-calisma-saatleri-gun>
                <admin-calisma-saatleri-gun title="Cumartesi" :data="cumartesi"></admin-calisma-saatleri-gun>
                <admin-calisma-saatleri-gun title="Pazar" :data="pazar"></admin-calisma-saatleri-gun>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    data(){
      return {
        pazartesi:[],
        sali:[],
        carsamba:[],
        persembe:[],
        cuma:[],
        cumartesi:[],
        pazar:[],
      }
    }
  }
</script>