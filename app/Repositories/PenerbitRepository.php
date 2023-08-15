<?php

namespace App\Repositories;

use App\Mail\NewInvoice;
use App\Mail\NewProcurement;
use App\Mail\RejectedInvoice;
use App\Mail\SendInvoice;
use App\Mail\VerifiedMail;
use App\Models\Procurement;
use App\Models\Major;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PenerbitRepository
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

    public static function sendVerified(Procurement $procurement)
    {
        $users = User::select(['name', 'email'])->where('publisher_id', $procurement->publisher_id)->get();
        $mail = Mail::to($procurement->publisher->email, $procurement->publisher->name);
        $mail->cc($users);
        $mail->queue(new VerifiedMail($procurement));
    }

    public static function sendRejected(Procurement $procurement)
    {
        $users = User::select(['name', 'email'])->where('publisher_id', $procurement->publisher_id)->get();
        $mail = Mail::to($procurement->publisher->email, $procurement->publisher->name);
        $mail->cc($users);
        $mail->queue(new RejectedInvoice($procurement));
    }

    public static function newProcurement(Procurement $procurement)
    {
        $users = User::role('Super Admin')->get();
        $mail = Mail::to($users);
        $mail->queue(new NewProcurement($procurement));
    }

    public static function sendInvoice(Procurement $procurement)
    {
        $users = User::role(User::ROLE_SUPER_ADMIN)->get();
        $mail = Mail::to($users);
        $mail->queue(new SendInvoice($procurement));

        // seharusnya prodi tidak dapat email
        // $users = User::role(User::ROLE_ADMIN_PRODI)->where('campus_id', $procurement->campus_id)->get();
        // $mail = Mail::to($users);
        // $mail->queue(new SendInvoice($procurement));
    }
}
