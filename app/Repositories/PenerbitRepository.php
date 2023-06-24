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
            //code...
            // $majors = $procurement->procurement-books()->select('major_id')->distinct()->pluck('major_id')->toArray();
            $majors = $procurement->procurement_books()->select('major_id')->get();
            $res = [];
            foreach ($majors as $major) {
                $d = explode(",", $major->major_id);
                array_push($res, $d);
            }

            $result = [];
            foreach ($res as $d) {
                foreach ($d as $e) {
                    array_push($result, $e);
                }
            }
            $majors = array_unique($result);
            $last_major = array_key_last($majors);
            $data_majors = Major::all();
            $res = "";
            foreach ($data_majors as $key => $dmajor) {
                foreach ($majors as $k => $major) {
                    if ($key == $major) {
                        $res .= $dmajor->getKey();
                        if ($k != $last_major) $res .= ",";
                    }
                }
            }
            $majors = explode(",", $res);
            info($majors);

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
        $users = User::role(User::ROLE_ADMIN_PRODI)->where('campus_id', $procurement->campus_id)->get();
        $mail = Mail::to($users);
        $mail->queue(new SendInvoice($procurement));
    }
}
