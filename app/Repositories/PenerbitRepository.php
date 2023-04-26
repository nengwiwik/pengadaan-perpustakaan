<?php

namespace App\Repositories;

use App\Mail\NewInvoice;
use App\Mail\NewProcurement;
use App\Mail\RejectedInvoice;
use App\Mail\SendInvoice;
use App\Mail\VerifiedMail;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PenerbitRepository
{
    public static function sendEmails(Invoice $invoice)
    {
        $majors = $invoice->books()->select('major_id')->distinct()->pluck('major_id')->toArray();

        $users = User::select(['name', 'email'])->where('campus_id', $invoice->campus_id)->whereIn('major_id', $majors)->get();
        // $users = User::select(['name', 'email'])->where('campus_id', $campus_id)->get();
        $mail = Mail::to(config('undira.admin_email'), config('undira.admin_name'));
        $mail->cc($users);
        $mail->queue(new NewInvoice($invoice));
    }

    public static function sendVerified(Invoice $invoice)
    {
        $users = User::select(['name', 'email'])->where('publisher_id', $invoice->publisher_id)->get();
        $mail = Mail::to($invoice->publisher->email, $invoice->publisher->name);
        $mail->cc($users);
        $mail->queue(new VerifiedMail($invoice));
    }

    public static function sendRejected(Invoice $invoice)
    {
        $users = User::select(['name', 'email'])->where('publisher_id', $invoice->publisher_id)->get();
        $mail = Mail::to($invoice->publisher->email, $invoice->publisher->name);
        $mail->cc($users);
        $mail->queue(new RejectedInvoice($invoice));
    }

    public static function newProcurement(Invoice $invoice)
    {
        $users = User::role('Super Admin')->get();
        $mail = Mail::to($users);
        $mail->queue(new NewProcurement($invoice));
    }

    public static function sendInvoice(Invoice $invoice)
    {
        $users = User::role(User::ROLE_SUPER_ADMIN)->get();
        $mail = Mail::to($users);
        $mail->queue(new SendInvoice($invoice));

        $users = User::role(User::ROLE_ADMIN_PRODI)->where('campus_id', $invoice->campus_id)->get();
        $mail = Mail::to($users);
        $mail->queue(new SendInvoice($invoice));
    }
}
