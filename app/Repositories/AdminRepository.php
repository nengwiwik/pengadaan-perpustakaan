<?php

namespace App\Repositories;

use App\Mail\NewInvoice;
use App\Mail\RejectedInvoice;
use App\Models\Procurement;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AdminRepository
{
    public static function sendEmails(Procurement $procurement)
    {
        try {
            $majors = $procurement->procurement_books()->select('major_id')->distinct()->pluck('major_id')->toArray();

            $users = User::select(['name', 'email'])->where('campus_id', $procurement->campus_id)->whereIn('major_id', $majors)->get();
            $mail = Mail::to(config('undira.admin_email'), config('undira.admin_name'));
            $mail->cc($users);
            $mail->queue(new NewInvoice($procurement));
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
    }

    public static function sendRejected(Procurement $procurement)
    {
        $users = User::select(['name', 'email'])->where('publisher_id', $procurement->publisher_id)->get();
        $mail = Mail::to($procurement->publisher->email, $procurement->publisher->name);
        $mail->cc($users);
        $mail->queue(new RejectedInvoice($procurement));
    }
}
