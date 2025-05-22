<?php

namespace App\Jobs;

use App\Mail\TuChoiDeTaiMail;
use App\Mail\DuyetSuaDeTaiMail;
use App\Mail\DuyetDeTaiMail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $emailList;
    public $deTai;
    public $ngayDuaRa;
    public $noiDung;
    public $loai;

    /**
     * Create a new job instance.
     */
    public function __construct($emailList, $deTai, $ngayDuaRa, $noiDung, $loai)
    {
        $this->emailList = $emailList;
        $this->deTai = $deTai;
        $this->ngayDuaRa = $ngayDuaRa;
        $this->noiDung = $noiDung;
        $this->loai = $loai;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        if ($this->loai == 'khong_duyet')
            Mail::to($this->emailList)->send(new TuChoiDeTaiMail($this->deTai, $this->ngayDuaRa, $this->noiDung));
        elseif ($this->loai == 'duyet_sua')
            Mail::to($this->emailList)->send(new DuyetSuaDeTaiMail($this->deTai, $this->ngayDuaRa, $this->noiDung));
        else
            Mail::to($this->emailList)->send(new DuyetDeTaiMail($this->deTai, $this->ngayDuaRa));
    }
}
