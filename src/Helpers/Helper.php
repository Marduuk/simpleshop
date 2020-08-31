<?php
declare(strict_types=1);

namespace App\Helpers;

use Locale;

/**
 * Class Helper
 * @package App\Helpers
 */
class Helper
{
    /**
     * @return string
     */
    public static function getLocaleCurrency(): string
    {
        $currencies = country(Locale::getDefault())->getCurrencies();
        return reset($currencies)['iso_4217_code'];
    }
}