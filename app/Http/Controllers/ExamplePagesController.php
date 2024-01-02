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

class ExamplePagesController extends Controller
{
    /**
     * Display the pricing page
     *
     * @return \Illuminate\View\View
     */
    public function pricing()
    {
        return view('pages.example_pages.pricing');
    }

    /**
     * Display the lock page
     *
     * @return \Illuminate\View\View
     */
    public function lock()
    {
        return view('pages.example_pages.lock');
    }

    /**
     * Display the rtl support page
     *
     * @return \Illuminate\View\View
     */
    public function rtlSupport()
    {
        return view('pages.example_pages.language');
    }

    /**
     * Display the rtl support page
     *
     * @return \Illuminate\View\View
     */
    public function error()
    {
        return view('pages.example_pages.error');
    }

    /**
     * Display the timeline page
     *
     * @return \Illuminate\View\View
     */
    public function timeline()
    {
        return view('pages.example_pages.timeline');
    }

    /**
     * Display the widgets page
     *
     * @return \Illuminate\View\View
     */
    public function widgets()
    {
        return view('pages.widgets');
    }

    /**
     * Display the charts page
     *
     * @return \Illuminate\View\View
     */
    public function charts()
    {
        return view('pages.charts');
    }

    /**
     * Display the calendar page
     *
     * @return \Illuminate\View\View
     */
    public function calendar()
    {
        return view('pages.calendar');
    }
}
