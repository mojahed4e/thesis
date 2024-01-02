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

class ComponentPagesController extends Controller
{
    /**
     * Display the buttons page
     *
     * @return \Illuminate\View\View
     */
    public function buttons()
    {
        return view('pages.components.buttons');
    }

    /**
     * Display the grid system page
     *
     * @return \Illuminate\View\View
     */
    public function grid()
    {
        return view('pages.components.grid');
    }

    /**
     * Display the panels page
     *
     * @return \Illuminate\View\View
     */
    public function panels()
    {
        return view('pages.components.panels');
    }

    /**
     * Display the sweet alert page
     *
     * @return \Illuminate\View\View
     */
    public function sweetAlert()
    {
        return view('pages.components.sweet_alert');
    }

    /**
     * Display the notifications page
     *
     * @return \Illuminate\View\View
     */
    public function notifications()
    {
        return view('pages.components.notifications');
    }

    /**
     * Display the icons page
     *
     * @return \Illuminate\View\View
     */
    public function icons()
    {
        return view('pages.components.icons');
    }

    /**
     * Display the typography page
     *
     * @return \Illuminate\View\View
     */
    public function typography()
    {
        return view('pages.components.typography');
    }
}
