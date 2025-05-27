<?php

namespace App\Jobs;

use App\Mail\TuChoiDeTaiMail;
use App\Mail\DuyetSuaDeTaiMail;
use App\Mail\DuyetDeTaiMail;
use App\Mail\TuChoiHuongDanMail;
use App\Mail\XacNhanHuongDanMail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $emailList;
    public $deTai;
    public $ngay;
    public $noiDung;
    public $loai;

    /**
     * Create a new job instance.
     */
    public function __construct($emailList, $deTai, $ngayDuaRa, $noiDung, $loai)
    {
        $this->emailList = $emailList;
        $this->deTai = $deTai;
        $this->ngay = $ngayDuaRa;
        $this->noiDung = $noiDung;
        $this->loai = $loai;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        if ($this->loai == 'khong_duyet')
            Mail::to($this->emailList)->send(new TuChoiDeTaiMail($this->deTai, $this->ngay, $this->noiDung));
        elseif ($this->loai == 'duyet_sua')
            Mail::to($this->emailList)->send(new DuyetSuaDeTaiMail($this->deTai, $this->ngay, $this->noiDung));
        elseif ($this->loai == 'xac_nhan_huong_dan')
            Mail::to($this->emailList)->send(new XacNhanHuongDanMail($this->deTai, $this->ngay));
        elseif ($this->loai == 'tu_choi_huong_dan')
            Mail::to($this->emailList)->send(new TuChoiHuongDanMail($this->deTai, $this->ngay));
        else
            Mail::to($this->emailList)->send(new DuyetDeTaiMail($this->deTai, $this->ngay));
    }
}
