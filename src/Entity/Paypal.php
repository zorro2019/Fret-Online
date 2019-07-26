<?php

namespace App\Entity;


class Paypal
{
    public static $paypal;

    /**
     * @return mixed
     */
    public static function getPaypal()
    {
        return [
            'id' => 'AZUGb_M77IPNen6O0JiJcu32kmE-pQkxaTQC-q4LneKqBB2oZx39EH0QOoYfv8aroYdhtsVqwRE-0U0P',
            'secret' => 'EMpHAw_ayLjJvAnvO61vb5hBaxomjI8Oslj2RorDrT-mCpljJEorEW74IvUdQeFBToxfsiYPs4ppSv8T'
        ];
    }
}