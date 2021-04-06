<?php

namespace App\Console\Commands;

use App\Models\CalismaSaatleri;
use App\Models\Randevu;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class Hatirlatma extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Hatirlatma:Start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tarih = date("Y-m-d");
        $ileriTarih = date("Y-m-d", strtotime("+1 Day",time()));
        $list = Randevu::whereIn("date",[$tarih,$ileriTarih])->where("isActive",1)->get();
        foreach ($list as $item) {
            if ($item["notification_type"] == 2){
                $data = [
                    "email" => $item["email"],
                    "name" => $item["fullName"],
                    "date" => $item["date"],
                    "time" => CalismaSaatleri::getString($item["workingHour"]),
                    "code" => $item["code"]
                ];
                try {
                    Mail::send("email",$data, function ($message) use ($data){
                        $message->to($data["email"],$data["name"])->subject("Randevu Hatırlatma");
                        $message->from("serhatocal1@gmail.com","Randevu Hatırlatma Sistemi");
                    });
                    Randevu::where("id", $item["id"])->update(["isSend" => HATIRLATMA_CRON_ONAY]);
                } catch (\Exception $exception){
                    Randevu::where("id", $item["id"])->update(["isSend" => HATIRLATMA_CRON_RED]);
                    dd($exception->getMessage());
                }
            }
            /*Sms entegrasyonu yapıldığında açılabilir.
            if ($item["notification_type"] == 1){
                $username   = '';
                $password   = '';
                $orgin_name = 'ILETI MRKZI';
                $message  = 'Merhaba '.$item["fullName"].' , Randevunuz '.$item["date"].' tarihinde '.CalismaSaatleri::getString($item["workingHour"]).' saatleri arasındadır. Takip Kodunuz:'.$item["code"].'Lütfen geç kalmayınız.';
                $number = str_replace("-","",$item["phone"]);
                $xml ='
                     <request>
                         <authentication>
                             <username>{$username}</username>
                             <password>{$password}</password>
                         </authentication>
                         <order>
                             <sender>{$orgin_name}</sender>
                             <sendDateTime>'.date("d/m/Y H:i").'</sendDateTime>
                             <message>
                                 <text>'.$message.'</text>
                                 <receipents>
                                     <number>'.$number.'</number>
                                 </receipents>
                             </message>
                         </order>
                     </request>
                    ';
                $result = self::sendRequest('http://api.iletimerkezi.com/v1/send-sms',$xml,array('Content-Type: text/xml'));
                try {
                    $xml = new \SimpleXMLElement($result);
                    if ($xml->status->code == "200"){
                        Randevu::where("id", $item["id"])->update(["isSend" => HATIRLATMA_CRON_ONAY]);
                    }else{
                        Randevu::where("id", $item["id"])->update(["isSend" => HATIRLATMA_CRON_RED]);
                    }
                    dd($xml->status->message);
                }catch (\Exception $exception){
                    dd($exception->getMessage());
                }
            }
            */
            if ($item["notification_type"] == 1){
                return true;
            }
        }
    }

    static function sendRequest($site_name,$send_xml,$header_type) {
        //die('SITENAME:'.$site_name.'SEND XML:'.$send_xml.'HEADER TYPE '.var_export($header_type,true));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$site_name);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$send_xml);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header_type);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);

        $result = curl_exec($ch);

        return $result;
    }
}
