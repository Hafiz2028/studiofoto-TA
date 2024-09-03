<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\ServiceType;
use App\Models\District;
use App\Models\Village;
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
        $mail->setFrom(env('EMAIL_FROM_ADDRESS'), env('EMAIL_FROM_NAME'));
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


/** GET FRONTEND VENUES By District */
if (!function_exists('get_venues_with_service_slug')) {
    function get_venues_with_service_slug()
    {
        $venues = Venue::where('status', 1)->orderBy('id', 'ASC')->get();

        $filteredVenues = [];
        foreach ($venues as $venue) {
            $serviceSlugs = [];
            $minPrice = PHP_INT_MAX;
            $hasPackageDetails = false;
            foreach ($venue->serviceEvents as $serviceEvent) {
                $serviceSlugs[] = $serviceEvent->serviceType->service_slug;
                foreach ($serviceEvent->servicePackages as $servicePackage) {
                    foreach ($servicePackage->servicePackageDetails as $packageDetail) {
                        $hasPackageDetails = true;
                        if ($packageDetail->price < $minPrice) {
                            $minPrice = $packageDetail->price;
                        }
                    }
                }
            }
            if ($hasPackageDetails) {
                $venue->service_slugs = $serviceSlugs;
                $venue->min_price = $minPrice != PHP_INT_MAX ? $minPrice : null;
                $filteredVenues[] = $venue;
            }
        }
        return $filteredVenues;
    }
}
if (!function_exists('get_venues_with_min_price')) {
    function get_venues_with_min_price()
    {
        $venues = Venue::where('status', 1)->orderBy('id', 'ASC')->get();

        foreach ($venues as $venue) {
            $minPrice = PHP_INT_MAX;
            $hasValidPrice = false;

            foreach ($venue->serviceEvents as $serviceEvent) {
                foreach ($serviceEvent->servicePackages as $servicePackage) {
                    foreach ($servicePackage->servicePackageDetails as $packageDetail) {
                        if ($packageDetail->price < $minPrice) {
                            $minPrice = $packageDetail->price;
                            $hasValidPrice = true;
                        }
                    }
                }
            }

            $venue->min_price = $hasValidPrice ? $minPrice : null;
        }

        return $venues;
    }
}

/** GET VENUE's DETAIL */
if (!function_exists('getVenueData')) {
    function getVenueData($venueId)
    {
        $venue = Venue::with(['serviceEvents.servicePackages', 'venueImages', 'serviceEvents.serviceEventImages'])
            ->findOrFail($venueId);

        return $venue;
    }
}
