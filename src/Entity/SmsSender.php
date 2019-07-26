<?php
/**
 * Created by PhpStorm.
 * User: Administrateur
 * Date: 20/03/2019
 * Time: 23:30
 */

namespace App\Entity;

use Osms\Osms;

class SmsSender
{
    public function __construct()
    {

    }

    public static function Send($content,$adresseUser)
    {
        $config = array(
            'clientId' => 'ZUixAqbImGe0VJSPC0mvY2nkhlwgZABf',
            'clientSecret' => 'lJZKZAAXAmGvhqp4'
        );
        $osms = new Osms($config);
        $response = $osms->getTokenFromConsumerKey();
        if (!empty($response['access_token'])) {
            //$sms = new Osms($conf);
            $senderAddress = 'tel:+2210000';
            $receiverAddress = 'tel:+221'.$adresseUser;
            $message = $content;
            $osms->sendSMS($senderAddress, $receiverAddress, $message);
        } else {
            dump($response);
        }
    }
}