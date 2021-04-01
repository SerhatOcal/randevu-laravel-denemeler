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
                  "time" => CalismaSaatleri::getString($item["workingHour"])
                ];
                try {
                    Mail::send("email",$data, function ($message) use ($data){
                        $message->to($data["email"],$data["name"])->subject("Randevu Hatırlatma");
                        $message->from("serhatocal1@gmail.com","Randevu Hatırlatma Sistemi");
                    });
                    Randevu::where("id", $item["id"])->update(["isSend" => HATIRLATMA_CRON_ONAY]);
                } catch (\Exception $exception){
                    dd($exception->getMessage());
                }
            }
        }

    }
}
