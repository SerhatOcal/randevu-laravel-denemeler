<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\CalismaSaatleri;
use App\Models\RandevuNotlari;
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

   public function getRandevuDetay($id)
   {
       $returnArray = [];
       $data = Randevu::where("id",$id)->get();
       $data[0]["calismaSaatleri"] = CalismaSaatleri::getString($data[0]["workingHour"]);
       $data[0]["bildirim_tipi"] = ($data[0]["notification_type"] == 2 ? "Email" : "Sms");
       $returnArray["data"] = $data[0];
       $returnArray["note"] = RandevuNotlari::where("randevuId", $id)->orderBy("id","desc")->get();
       return response()->json($returnArray);
   }

   public function randevuDetayNotu(Request $request)
   {
       $returnArray = [];
       $all = $request->except("_token");
       $create = RandevuNotlari::create([
           "randevuId"  => $all["id"],
           "text"       => $all["text"]
       ]);
       if ($create){
           $returnArray["status"] = true;
       }else{
           $returnArray["status"] = false;
       }
       return response()->json($returnArray);
   }

   public function all(){
       $returnArray = [];

       /*Onay Bekleyenler*/
       $returnArray["onay_bekleyenler"] = Randevu::where('isActive',0)->orderBy('workingHour', 'asc')->paginate(3,["*"],"onay_bekleyenler_page");
       $returnArray["onay_bekleyenler"]->getCollection()->transform(function($value) {
           $value['calismaSaatleri'] = CalismaSaatleri::getString($value['workingHour']);
           return $value;
       });

       /*Günü Gelenler*/
       $returnArray["gunu_gelenler"] = Randevu::where('isActive',1)->where('date','=',date("Y-m-d"))->orderBy('workingHour', 'asc')->paginate(3,["*"],"gunu_gelenler_page");
       $returnArray["gunu_gelenler"]->getCollection()->transform(function($value) {
           $value['calismaSaatleri'] = CalismaSaatleri::getString($value['workingHour']);
           return $value;
       });

       /*Randevu Listesi*/
       $returnArray["randevu_listesi"] = Randevu::where('isActive',1)->where('date','>',date("Y-m-d"))->orderBy('workingHour', 'asc')->paginate(3,["*"],"randevu_listesi_page");
       $returnArray["randevu_listesi"]->getCollection()->transform(function($value) {
           $value['calismaSaatleri'] = CalismaSaatleri::getString($value['workingHour']);
           return $value;
       });

       /*Günü Geçenler*/
       $returnArray["gunu_gecenler"] = Randevu::where('date','<',date("Y-m-d"))->orderBy('workingHour', 'asc')->paginate(3,["*"],"gunu_gecenler_page");
       $returnArray["gunu_gecenler"]->getCollection()->transform(function($value) {
           $value['calismaSaatleri'] = CalismaSaatleri::getString($value['workingHour']);
           return $value;
       });
       /*İptal Edilenler*/
       $returnArray["iptal_edilenler"] = Randevu::where('isActive',2)->orderBy('workingHour', 'asc')->paginate(3,["*"],"iptal_edilenler_page");
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
