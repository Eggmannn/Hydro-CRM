<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::all();
        return view('crd_admin.company.index', compact('companies'));
    }
    public function create()
    {
        return view('crd_admin.company.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'nullable|email',
        ]);
        Company::create($request->all());
        return redirect()->route('companies.index')->with('success', 'Company created successfully.');
    }

    public function destroy($id)
    {
        Company::destroy($id);
        return back()->with('success', 'Company deleted successfully.');
    }
}
