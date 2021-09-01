<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ContactRepositoryInterface;

class ContactController extends Controller
{
    protected $contactRepository;
    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function index()
    {
        return view('import');
    }

    public function import(Request $request)
    {
        $this->contactRepository->import($request);

        return redirect()->back()->with('success', 'File Imported Successfully');
    }
}
