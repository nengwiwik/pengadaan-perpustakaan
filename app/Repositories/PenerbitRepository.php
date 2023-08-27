<?php

namespace App\Repositories;

use App\Mail\NewProcurement;
use App\Mail\RejectedInvoice;
use App\Mail\SendInvoice;
use App\Mail\VerifiedMail;
use App\Models\Procurement;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class PenerbitRepository
{
    public static function sendVerified(Procurement $procurement)
    {
        $users = User::select(['name', 'email'])->where('publisher_id', $procurement->publisher_id)->get();
        $mail = Mail::to($procurement->publisher->email, $procurement->publisher->name);
        $mail->cc($users);
        $mail->queue(new VerifiedMail($procurement));
    }

    public static function newProcurement(Procurement $procurement)
    {
        try {
            $users = User::role(User::ROLE_SUPER_ADMIN)->get();
            $mail = Mail::to($users);
            $mail->queue(new NewProcurement($procurement));
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
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
