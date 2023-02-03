<?php

namespace App\Repositories;

use App\Mail\NewInvoice;
use App\Mail\VerifiedMail;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PenerbitRepository
{
    public static function sendEmails(Invoice $invoice)
    {
        $campus_id = $invoice->campus->id;
        $majors = $invoice->books()->select('major_id')->distinct()->pluck('major_id')->toArray();

        // $users = User::select(['name', 'email'])->where('campus_id', $campus_id)->whereIn('major_id', $majors)->get();
        $users = User::select(['name', 'email'])->where('campus_id', $campus_id)->get();
        $mail = Mail::to(config('undira.admin_email'), config('undira.admin_name'));
        $mail->cc($users);
        $mail->send(new NewInvoice($invoice));
    }

    public static function sendVerified(Invoice $invoice)
    {
        $mail = Mail::to($invoice->publisher->email, $invoice->publisher->name);
        $mail->send(new VerifiedMail($invoice));
    }
}
