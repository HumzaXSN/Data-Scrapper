<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GoogleBusiness;
use App\DataTables\GoogleBusinessesDataTable;

class GoogleBusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GoogleBusinessesDataTable $dataTable)
    {
        $getJobBusinesses = request()->getJobBusinesses;
        $getBusiness = request()->showBusiness;
        return $dataTable->with(['getBusiness' => $getBusiness, 'getJobBusinesses' => $getJobBusinesses])->render('google-businesses.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(GoogleBusiness $googleBusiness)
    {
        return view('google-businesses.show',compact('googleBusiness'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(GoogleBusiness $googleBusiness)
    {
        $googleBusiness->delete();
        return redirect()->route('google-businesses.index')->with('success', 'Business deleted successfully');
    }
}
