<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\CalismaSaatleri;
use App\Models\Randevu;
use App\Models\RandevuNotlari;
use Illuminate\Http\Request;

class indexController extends Controller
{
    public function getWorkingHours($date = null)
    {
        $date = ($date == null) ? date("Y-m-d") : $date;
        $day = date("l",strtotime($date));
        $returnArray = [];
        $hours = CalismaSaatleri::where("day",$day)->get();

        foreach ($hours as $row){
            $control = Randevu::where('date',$date)
                                ->where('workingHour', $row['id'])
                                ->where(function ($control){
                                    $control->orWhere("isActive", 0);
                                    $control->orWhere("isActive", 1);
                                })->count();
            $exp = explode("-", $row["hours"]);
            $nowTime = date("H.i");

            $row['isActive'] = ($control == 0 AND $exp[0] > $nowTime) ? true : false;
            $returnArray[] = $row;
        }
        return response()->json($returnArray);
    }

    public function randevuOlustur(Request $request)
    {
        $returnArray = [];
        $returnArray['status'] = false;
        $all = $request->except('_token');
        $control = Randevu::where('date', $all['date'])->where('workingHour',$all['workingHour'])->count();

        if ($control != 0) {
           $returnArray['message'] = "Bu Randevu tarihi doludur";
           return response()->json($returnArray);
        }

        $all["code"] = substr(md5(uniqid()),0,6);
        $create = Randevu::create($all);
        if ($create) {
            $returnArray['status'] = true;
            $returnArray['message'] = "Randevunuz Başarı ile Alındı";
            return response()->json($returnArray);
        } else {
            $returnArray['message'] = "Hata! Randevu oluşturulamadı bizim ile iletişime geçiniz...!";
            return response()->json($returnArray);
        }
    }

    public function getCalismaSaatiKaydet(Request $request){
        $all = $request->except("_token");

        CalismaSaatleri::query()->delete();
        foreach ($all as $key => $value){
            foreach ($value as $key1 => $value1) {
                CalismaSaatleri::create([
                    "day" => $key,
                    "hours" => $value1
                ]);
            }
        }

        return response()->json();
    }

    public function getCalismaSaatleriListesi(){
        $returnArray = [];
        $data = CalismaSaatleri::all();
        foreach ($data as $key => $value) {
            $returnArray[$value["day"]][] = $value["hours"];
        }
        return response()->json($returnArray);
    }

    public function randevDetay(Request $request){

        $returnArray = [];
        $returnArray["status"] = false;
        $all = $request->except("_token");
        $control = Randevu::where("code",$all["code"])->count();

        if ($all["code"] == ""){
            $returnArray["message"] = "Lütfen kod kısmını doldurunuz !";
            return response()->json($returnArray);
        }

        if ($control == 0){
            $returnArray["message"] = "Bu Kod ile eşleşen herhangi bir randevu bulunamadı !";
            return response()->json($returnArray);
        }

        $info = Randevu::where("code", $all["code"])->get();
        $info[0]["calismaSaatleri"] = CalismaSaatleri::getString($info[0]["workingHour"]);
        $info[0]["bildirim_tipi"] = ($info[0]["notification_type"] == 2 ? "Email" : "Sms");
        $returnArray["status"] = true;
        $returnArray["info"] = $info[0];
        $returnArray["note"] = RandevuNotlari::where("randevuId", $info[0]["id"])->orderBy("id","desc")->get();

        return response()->json($returnArray);

    }
}
