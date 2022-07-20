<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Lists;
use App\Models\ListType;
use Illuminate\Http\Request;
use App\Exports\ExportContacts;
use App\DataTables\ListsDataTable;

class ListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ListsDataTable $dataTable, Request $request)
    {
        $list_type = ListType::all();
        return $dataTable->render('lists.index',compact('list_type'));
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
        Lists::create([
            'name' => $request->region . '-' . $request->industry . '-' . $request->title . '-' . $request->month . '-' . $request->year . '-' . $request->createdBy,
            'description' => $request->input('description'),
            'list_type_id' => 2,
            'user_id' => auth()->user()->id,
        ]);
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
        return view('lists.show', compact('list'));
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

    public function exportContacts(Request $request)
    {
        ini_set('memory_limit', '256M');
        $listId = $request->listId;
        $listName = $request->listName;
        $listName = ucwords($listName);
        $updatecount = Lists::find($request->listId);
        $updatecount->export_count = $updatecount->export_count+1;
        $updatecount->update();
        return (new ExportContacts($listId))->download('List: ' . $listName . ' ' . Carbon::now() . '.csv');
    }
}
