<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\ServiceType;
use App\Models\Venue;

/** SEND EMAIL FUNCTION USING PHPMAILER LIBRARY */
if (!function_exists('sendEmail')) {
    function sendEmail($mailConfig)
    {
        require 'PHPMailer/src/Exception.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';

        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = env('EMAIL_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = env('EMAIL_USERNAME');
        $mail->Password = env('EMAIL_PASSWORD');
        $mail->SMTPSecure = env('EMAIL_ENCRYPTION');
        $mail->Port = env('EMAIL_PORT');
        $mail->setFrom($mailConfig['mail_from_email'], $mailConfig['mail_from_name']);
        $mail->addAddress($mailConfig['mail_recipient_email'], $mailConfig['mail_recipient_name']);
        $mail->isHTML(true);
        $mail->Subject = $mailConfig['mail_subject'];
        $mail->Body = $mailConfig['mail_body'];
        if ($mail->send()) {
            return true;
        } else {
            return false;
        }
    }
    //FRONTEND::
    /** GET FRONTEND SERVICE TYPE */
    if (!function_exists('get_service_types')) {
        function get_service_types()
        {
            $service_types = ServiceType::orderBy('id', 'ASC')->get();
            return !empty($service_types) ? $service_types : [];
        }
    }

    /** GET FRONTEND VENUES */
    if (!function_exists('get_venues_with_service_slug')) {
        function get_venues_with_service_slug()
        {
            $venues = Venue::orderBy('id', 'ASC')->get();

            // Loop through each venue
            foreach ($venues as $venue) {
                $serviceSlugs = [];

                // Loop through each service event of the venue
                foreach ($venue->serviceEvents as $serviceEvent) {
                    // Add service slug to the array
                    $serviceSlugs[] = $serviceEvent->serviceType->service_slug;
                }

                // Add service slugs array to venue object
                $venue->service_slugs = $serviceSlugs;
            }

            return !empty($venues) ? $venues : [];
        }
    }
}
