<?php

namespace App;

use Slim\Csrf\Guard;
use Slim\Http\Request;

if (!function_exists('App\generate_csrf_form_fields')) {

    function generate_csrf_form_fields(Request $request, Guard $guard)
    {
        $nameKey = $guard->getTokenNameKey();
        $valueKey = $guard->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);

        return "<input type=\"hidden\" name=\"{$nameKey}\" value=\"{$name}\">
            <input type=\"hidden\" name=\"{$valueKey}\" value=\"{$value}\">";
    }
}


if (!function_exists('App\formatDateTime')) {

    function formatDateTime(\DateTime $dateTime, $format = "Y-m-d")
    {
        return $dateTime->format($format);
    }
}

if (!function_exists('App\clear_all_errors_from_session')) {

    function clear_all_errors_from_session()
    {
        return Session::getInstance()->remove("errors");
    }
}
