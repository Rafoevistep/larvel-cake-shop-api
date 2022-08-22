<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrderExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user_id = auth('sanctum')->user()->id;

       return $order = Order::where(['user_id' => $user_id])->get();
    }
}
