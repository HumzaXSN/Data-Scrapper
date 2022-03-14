<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lists;
use App\Models\ListType;
use Illuminate\Http\Request;

class ListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all();
        $list_type = ListType::all();
        $lists = Lists::all();
        return view('lists.index', compact('lists', 'list_type', 'user'));
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
        $list = new Lists;
        $list->name = $request->input('name');
        $list->description = $request->input('description');
        $list->slug = strtolower(str_replace(' ', '-', $request->input('name')));
        $list->list_type_id = $request->input('type');
        $list->user_id = auth()->user()->id;
        $list->save();

        return redirect()->route('lists.index')->with('success', 'List created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\List  $list
     * @return \Illuminate\Http\Response
     */
    public function show(Lists $list)
    {
       //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\List  $list
     * @return \Illuminate\Http\Response
     */
    public function edit(Lists $list)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\List  $list
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lists $list)
    {
        $input = $request->except(['_token', '_method']);
        $list->update($input);

        return redirect()->route('lists.index')->with('success', 'List updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\List  $list
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lists $list)
    {
        $list->delete();

        return redirect()->route('lists.index')->with('success', 'List deleted successfully');
    }
}
