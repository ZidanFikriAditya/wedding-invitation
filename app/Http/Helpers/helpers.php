<?php

use Illuminate\Support\Facades\Crypt;

function cryptId($string)
{
    return Crypt::encrypt($string);
}

function decryptId($string)
{
    return Crypt::decrypt($string);
}