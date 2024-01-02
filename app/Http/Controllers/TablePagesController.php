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

class TablePagesController extends Controller
{
    /**
     * Display the regular tables page
     *
     * @return \Illuminate\View\View
     */
    public function regularTables()
    {
        return view('pages.tables.tables_regular');
    }

    /**
     * Display the extended tables page
     *
     * @return \Illuminate\View\View
     */
    public function extendedTables()
    {
        return view('pages.tables.tables_extended');
    }

    /**
     * Display the datatable tables page
     *
     * @return \Illuminate\View\View
     */
    public function datatableTables()
    {
        return view('pages.tables.tables_datatable');
    }
}
