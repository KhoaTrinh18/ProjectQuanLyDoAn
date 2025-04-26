<?php

namespace App\Jobs;

use App\Mail\TuChoiDeTaiMail;
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
    public $lyDoTuChoi;

    /**
     * Create a new job instance.
     */
    public function __construct($emailList, $deTai, $ngayDuaRa, $lyDoTuChoi)
    {
        $this->emailList = $emailList;
        $this->deTai = $deTai;
        $this->ngayDuaRa = $ngayDuaRa;
        $this->lyDoTuChoi = $lyDoTuChoi;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Mail::to($this->emailList)->send(new TuChoiDeTaiMail($this->deTai, $this->ngayDuaRa, $this->lyDoTuChoi));
    }
}
