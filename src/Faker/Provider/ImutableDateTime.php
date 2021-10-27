<?php


namespace App\Faker\Provider;


use Faker\Provider\Base;
use Faker\Provider\DateTime;

final class ImutableDateTime extends Base
{
    public static function immutableDateTimeBetween($startDate = '-2 years', $endDate = 'now', $timezone = 'Europe/Paris'){
        return \DateTimeImmutable::createFromMutable(
           DateTime::dateTimeBetween($startDate, $endDate, $timezone)
        );
    }
}