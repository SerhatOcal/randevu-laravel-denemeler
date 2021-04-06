<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalismaSaatleri extends Model
{
    use HasFactory;
    protected $guarded = [];

    static function getString($calismaSaatiId){
        $control = CalismaSaatleri::where('id', $calismaSaatiId)->count();
        if ($control > 0) {
            $response = CalismaSaatleri::where('id', $calismaSaatiId)->get();
            return $response[0]['hours'];
        }
        return false;
    }
}
