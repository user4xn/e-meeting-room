<?php

use Carbon\Carbon;

function indoDate($param)
{
    setlocale(LC_ALL, 'id_ID');
    $conv = Carbon::parse($param)
        ->setTimezone('Asia/Jakarta')
        ->isoFormat('dddd, D MMMM YYYY');

    return $conv;
}