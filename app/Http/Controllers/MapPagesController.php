<?php
/*

=========================================================
* Argon Dashboard PRO - v1.0.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard-pro-laravel
* Copyright 2018 Creative Tim (https://www.creative-tim.com) & UPDIVISION (https://www.updivision.com)

* Coded by www.creative-tim.com & www.updivision.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

*/
namespace App\Http\Controllers;

class MapPagesController extends Controller
{
    /**
     * Display the google maps page
     *
     * @return \Illuminate\View\View
     */
    public function googleMaps()
    {
        return view('pages.maps.maps_google');
    }

    /**
     * Display the full screen maps page
     *
     * @return \Illuminate\View\View
     */
    public function fullscreenMaps()
    {
        return view('pages.maps.maps_fullscreen');
    }

    /**
     * Display the vector maps page
     *
     * @return \Illuminate\View\View
     */
    public function vectorMaps()
    {
        return view('pages.maps.maps_vector');
    }
}
