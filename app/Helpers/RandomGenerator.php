<?php
namespace App\Helpers;

use DateTime;

class RandomGenerator
{
    /**
     * return random date between range of years from today's date
     *
     * @param  string $start 
     * @param  string $end 
     * 
     * @return Date 
     */
    public function randomBirthdayDate($start, $end)
    {
        $start_date = strtotime(date('d-m-Y', strtotime($start."year")));
        $end_date = strtotime(date('d-m-Y', strtotime($end." year")));

        return date('Y-m-d H:i:s', rand($start_date, $end_date));
    }

    /**
     * return a random start date and maybe an end date between the week range from today's date
     *
     * @param  string $start 
     * @param  string $end 
     * 
     * @return array
     */
    public function randomDates($start, $end)
    {
        $dates = [];
        $startDate = strtotime(date('d-m-Y', strtotime("$start week")));
        $endDate = strtotime(date('d-m-Y', strtotime("$end week")));
        $startTime = date('Y-m-d H:i:s', rand($startDate, $endDate));

        $startTime = DateTime::createFromFormat('Y-m-d H:i:s', $startTime);

        $startTime->setTime(rand(9, 19), rand(0, 1) ? 30 : 0);
        $dates[] = $startTime;
        if (!rand(0, 4)){
            $endTime = clone $startTime;
            $h = $endTime->format('H');
            $i = $endTime->format('i');
            $h++;
            $dates[] = $endTime->setTime($h, $i);
        }
        return $dates;

    }
    
    /**
     * return random string of number
     *
     * @param  integer $length 
     * 
     * @return String 
     */
    public function randomNumberString($length)
    {
        $numbers = '0123456789';
        $numbersLength = strlen($numbers);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $numbers[rand(0, $numbersLength - 1)];
        }
        return $randomString;
    } 

    public static function instance()
    {
        return new RandomGenerator();
    }
}