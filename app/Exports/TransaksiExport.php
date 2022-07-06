<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TransaksiExport implements FromView
{
    public $data;
    public $untukadmin = true;

    public function __construct($data,$has){
        $this->data = $data;
        $this->untukadmin = $has;
    }
    public function view(): View
    {
        return view('laporan.transaksiexcel', [
            'datas' => $this->data,
            'has'=>$this->untukadmin
        ]);
    }
}
