<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\CalismaSaatleri;
use App\Models\Randevu;
use Illuminate\Http\Request;

class indexController extends Controller
{
    public function getWorkingHours($date = null)
    {
        $date = ($date == null) ? date("Y-m-d") : $date;
        $returnArray = [];
        $hours = CalismaSaatleri::all();
        foreach ($hours as $row){
            $control = Randevu::where('date',$date)->where('workingHour', $row['id'])->count();
            $row['isActive'] = ($control == 0) ? true : false;
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
}
