<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\CalismaSaatleri;
use Illuminate\Http\Request;
use App\Models\Randevu;

class indexController extends Controller
{
   public function getIslemler(Request $request)
   {
       $all = $request->except('_token');
       Randevu::where('id',$all['id'])->update(['isActive' => $all['isActive']]);
       return response()->json(['status' => true]);
   }

   public function all(){
       $returnArray = [];

       /*Onay Bekleyenler*/
       $returnArray["onay_bekleyenler"] = Randevu::where('isActive',0)->orderBy('workingHour', 'asc')->paginate(9);
       $returnArray["onay_bekleyenler"]->getCollection()->transform(function($value) {
           $value['calismaSaatleri'] = CalismaSaatleri::getString($value['workingHour']);
           return $value;
       });

       /*Günü Gelenler*/
       $returnArray["gunu_gelenler"] = Randevu::where('isActive',1)->where('date','=',date("Y-m-d"))->orderBy('workingHour', 'asc')->paginate(9);
       $returnArray["gunu_gelenler"]->getCollection()->transform(function($value) {
           $value['calismaSaatleri'] = CalismaSaatleri::getString($value['workingHour']);
           return $value;
       });

       /*Randevu Listesi*/
       $returnArray["randevu_listesi"] = Randevu::where('isActive',1)->where('date','>',date("Y-m-d"))->orderBy('workingHour', 'asc')->paginate(9);
       $returnArray["randevu_listesi"]->getCollection()->transform(function($value) {
           $value['calismaSaatleri'] = CalismaSaatleri::getString($value['workingHour']);
           return $value;
       });

       /*Günü Geçenler*/
       $returnArray["gunu_gecenler"] = Randevu::where('date','<',date("Y-m-d"))->orderBy('workingHour', 'asc')->paginate(9);
       $returnArray["gunu_gecenler"]->getCollection()->transform(function($value) {
           $value['calismaSaatleri'] = CalismaSaatleri::getString($value['workingHour']);
           return $value;
       });
       /*İptal Edilenler*/
       $returnArray["iptal_edilenler"] = Randevu::where('isActive',2)->orderBy('workingHour', 'asc')->paginate(9);
       $returnArray["iptal_edilenler"]->getCollection()->transform(function($value) {
           $value['calismaSaatleri'] = CalismaSaatleri::getString($value['workingHour']);
           return $value;
       });

       return response()->json($returnArray);
   }

   public function getList()
   {
        $data = Randevu::where('isActive',1)->where('date','>',date("Y-m-d"))->orderBy('workingHour', 'asc')->paginate(9);
        $data->getCollection()->transform(function($value) {
            $value['calismaSaatleri'] = CalismaSaatleri::getString($value['workingHour']);
            return $value;
        });
       return response()->json($data);
   }

   public function getTodayList()
   {
        $data = Randevu::where('isActive',1)->where('date','=',date("Y-m-d"))->orderBy('workingHour', 'asc')->paginate(9);
        $data->getCollection()->transform(function($value) {
            $value['calismaSaatleri'] = CalismaSaatleri::getString($value['workingHour']);
            return $value;
        });
       return response()->json($data);
   }

   public function getLastList()
   {
        $data = Randevu::where('date','<',date("Y-m-d"))->orderBy('workingHour', 'asc')->paginate(9);
        $data->getCollection()->transform(function($value) {
            $value['calismaSaatleri'] = CalismaSaatleri::getString($value['workingHour']);
            return $value;
        });
       return response()->json($data);
   }

   public function getConfirmationList()
   {
        $data = Randevu::where('isActive',0)->orderBy('workingHour', 'asc')->paginate(9);
        $data->getCollection()->transform(function($value) {
            $value['calismaSaatleri'] = CalismaSaatleri::getString($value['workingHour']);
            return $value;
        });
       return response()->json($data);
   }

   public function getIptalList()
   {
        $data = Randevu::where('isActive',2)->orderBy('workingHour', 'asc')->paginate(9);
        $data->getCollection()->transform(function($value) {
            $value['calismaSaatleri'] = CalismaSaatleri::getString($value['workingHour']);
            return $value;
        });
       return response()->json($data);
   }
}
