<template>
    <div class="card mb-2">
        <div class="card-header" >
            <h4 class="card-title" style="width: 7rem; float: left;">{{title}}</h4>
            <button class="btn btn-secondary" @click="showForm" style="background-color:#38c172;">+</button>
            <div v-if="isShow" class="col-md-6" style="padding: 10px;">
                <input type="text" class="form-control" v-model="time" placeholder="Çalışma Saati : 10.00 - 11.00">
                <button @click="fncEkle" class="btn btn-outline-primary" style="margin-top: 10px;">Ekle</button>
            </div>
        </div>
        <div class="card-body">
            <div v-for="item in data">{{item}}</div>
        </div>
    </div>
</template>

<script>
  export default {
      props:['title','data','day'],
      data(){
          return {
              isShow:false,
              time:""
          }
      },
      methods:{
          showForm:function () {
              this.isShow = !this.isShow;
          },
          fncEkle:function () {
            this.$emit('fncekle',{text:this.time,day:this.day});
            this.time = "";
            this.isShow = false;
          }
          
      }
  }
</script>