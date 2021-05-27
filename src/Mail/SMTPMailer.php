<?php

namespace Modules\SofEsign\Classes;

use Modules\Core\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

/**
 * Class SendMailSMtp
 *
 * @package Modules\SofEsign\Classes
 */
class SendMailSmtp
{
    /**
     * To send mail via smtp
     *
     * @param          $emails
     * @param Mailable $mailable
     *
     * @return bool
     */
    public static function send($emails, Mailable $mailable)
    {
        Mail::to($emails)->send($mailable);
    }
}