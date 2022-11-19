<?php

namespace App\Repositories;

use App\Mail\NewInvoice;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class PenerbitRepository
{
    public static function sendEmails(Invoice $invoice)
    {
        $data = $invoice->with('books', 'campus')->first();
        $campus_id = $data->campus->id;
        $majors = $data->books()->select('major_id')->distinct()->pluck('major_id')->toArray();

        $users = User::select(['name','email'])->where('campus_id', $campus_id)->whereIn('major_id', $majors)->get();
        $mail = Mail::to('41119051@mahasiswa.undira.ac.id', 'Wiwik Listiyarini');
        $mail->cc($users);
        $mail->send(new NewInvoice($invoice));
    }
}
