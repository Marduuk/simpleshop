<?php
declare(strict_types=1);

namespace App\DataFixtures\ORM\Provider;

use Faker\Provider\Base;
use DateTime;

class DateTimeProvider extends Base
{
    /**
     * @return DateTime
     */
    public function currentDateTime(): DateTime
    {
        return new DateTime();
    }

    /**
     * @return DateTime
     */
    public function futureDateTime(): DateTime
    {
        return new DateTime("+1 month");
    }

    /**
     * @return DateTime
     */
    public function pastDateTime(): DateTime
    {

        $date = new DateTime('-'.rand(2,59).' days');

        return $date;
    }
}
