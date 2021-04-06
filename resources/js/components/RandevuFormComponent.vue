<template>
    <div class="container">
        <div class="successResponse" v-if="!complateForm">
            <i class="fa fa-check-circle-o" aria-hidden="true"></i>
            <span>Randevunuz Başarılı ile Alınmıştır.</span>
        </div>
       <div class="complateForm" v-if="complateForm">
           <h3 class="text-center mb-5">Randevu oluşturmak için formu doldurunuz.</h3>
           <div class="row">
            <div class="col-md-12">
                <li v-for="i in errors" class="alert alert-danger">{{i}}</li>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <input type="text" class="form-control" v-model="name" placeholder="Ad Soyad ">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <input type="text" class="form-control" v-model="email" placeholder="Email ">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <input type="text" class="form-control" v-model="phone" v-mask="'###-###-##-##'" placeholder="Telefon ">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <input type="date" @change="selectDate" :min="minDate" v-model="date" class="form-control">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <ul class="select-times">
                    <li v-for="item in workingHours" class="select-time">
                        <input type="radio" v-if="item.isActive" v-model="workingHour" v-bind:value="item.id">
                        <span>{{item.hours}}</span>
                        <span class="text-danger" v-if="!item.isActive">Dolu</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <textarea v-model="note" class="form-control" cols="30" rows="5"></textarea>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-1">
                <div class="form-group">
                    <input v-model="notification_type" type="radio" value="0"/>
                    <label>Sms</label>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <input v-model="notification_type" type="radio" value="1"/>
                    <label>Email</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <button class="btn btn-success" v-on:click="randevuOlustur">Randevu Oluştur</button>
            </div>
        </div>
       </div>
    </div>
</template>

<script>
import io from "socket.io-client";
var socket = io("http://localhost:3000");
    export default {
        data(){
            return {
                errors:[],
                workingHour:0,
                name:null,
                email:null,
                phone:null,
                note:null,
                minDate:new Date().toISOString().slice(0,10),
                date:new Date().toISOString().slice(0,10),
                workingHours:[],
                notification_type:null,
                complateForm: true
            }
        },
		created() {
        	socket.emit('hello');
		},
		mounted(){
            axios.get('http://proje.com/api/calisma-saatleri')
                .then((res) => {
                    this.workingHours = res.data;
                });
        },
        methods: {
            randevuOlustur:function(){
                if (this.name && this.email && this.validEmail(this.email) && this.phone && this.workingHour && this.notification_type) {
                    axios.post('http://proje.com/api/randevu-olustur',{
                        fullName:this.name,
                        phone:this.phone,
                        email:this.email,
                        date:this.date,
                        note:this.note,
                        workingHour:this.workingHour,
                        notification_type:this.notification_type
                    }).then((res) => {
                        if (res.status) {
                        	socket.emit("yeni_randevu_olustur");
                            this.complateForm = false;
                        }
                    });
                }
                this.errors = [];
                if (!this.name) {
                    this.errors.push('İsim Soyisim Girilmelidir');
                }
                if (!this.email || !this.validEmail(this.email)) {
                    this.errors.push('Email Girilmelidir ve Formatı Doğru Olmalıdır');
                }
                if (!this.phone) {
                    this.errors.push('Telefon Girilmelidir');
                }
                if (!this.workingHour) {
                    this.errors.push('Çalışma Saati Seçilmelidir');
                }
                if (!this.notification_type) {
                    this.errors.push('Sms yada Email Bildirimi Seçilmelidir');
                }
            },
            selectDate:function(){
                axios.get('http://proje.com/api/calisma-saatleri/${this.date}')
                .then((res) => {
                    this.workingHours = res.data;
                });
            },
            validEmail: function (email) {
                var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            }
        }
    }
</script>
