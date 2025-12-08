<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->type == 'admin') {
            $companies = Company::orderBy('id', 'DESC')->paginate(12);
        } else {
            $companies = Company::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->paginate(12);
        }
        return view('companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('companies.new');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->required_statement);
        // dd($request);
        // die();
        $request->validate([
            'name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'address' => 'required',
            'report_type' => 'required',
            'account_type' => 'required',
            'comparative_accounts' => 'required',
        ]);

        try {
            $company = new Company();
            $company->user_id = 1;
            $company->name = $request->name;
            $company->start_date = $request->start_date;
            $company->end_date = $request->end_date;
            $company->address = $request->address;
            $company->required_statements = implode(',', $request->required_statements);
            $company->report_type = $request->report_type;
            $company->account_type = $request->account_type;
            $company->modified_by = 1;
            $company->save();

            $company->company_meta()->create([
                'company_id' => $company->id,
                'meta_key' => 'comparative_accounts',
                'meta_value' => $request->comparative_accounts,
            ]);

            return redirect()->route('fixed-assets.index', $company->id)->with('success', 'Company created successfully.');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating company: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $company = Company::findOrFail($id);

        return view('companies.view', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (Auth::user()->type != 'admin') {
            // return redirect()->route('companies.index')->with('error', 'You are not authorized to edit this company.');
            $company = Company::find($id);

            if ($company->user_id != Auth::user()->id) {
                return redirect()->route('companies.index')->with('error', 'You are not authorized to edit this company.');
            }
        } else {
            $company = Company::findOrFail($id);
        }
        // $company = Company::findOrFail($id);

        return view('companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'address' => 'required',
            'report_type' => 'required',
            'account_type' => 'required',
            'comparative_accounts' => 'required',
        ]);
        
        try {
            $company = Company::findOrFail($id);
            $company->name = $request->name;
            $company->start_date = $request->start_date;
            $company->end_date = $request->end_date;
            $company->address = $request->address;
            $company->required_statements = implode(',', $request->required_statements);
            $company->report_type = $request->report_type;
            $company->account_type = $request->account_type;
            $company->modified_by = 1;
            $company->save();

            $company->company_meta()->updateOrCreate(
                ['company_id' => $company->id, 'meta_key' => 'comparative_accounts'],
                ['meta_value' => $request->comparative_accounts]
            );

            return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating company: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $company = Company::findOrFail($id);

        try {
            $company->delete();

            return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating company: ' . $e->getMessage()
            ], 500);
        }
    }
}
