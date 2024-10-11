<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ListLinksExport;
use App\Models\UsersRole;
use App\Models\Emodule;
use Illuminate\Support\Facades\Auth;

class EmoduleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = UsersRole::where('id_users', $user->id)->pluck('id_role')->first(); 

        $subCategories = Emodule::select('sub_cat')->distinct()->get();
        $statuses = Emodule::select('status')->distinct()->get();
        $listItems = Emodule::orderBy('id', 'desc')->get();

        return view('ld.emodule', compact('listItems', 'subCategories', 'statuses'));
    }
    
    public function getModalData()
    {
        $modules = Module::select(['id', 'category', 'sub_cat', 'title', 'status', 'link', 'video']);
        return datatables()->of($modules)
            ->addColumn('action', function ($module) {
                return '
                    <button class="btn btn-sm btn-primary btn-edit-module"
                        data-id="' . $module->id . '"
                        data-title="' . $module->title . '"
                        data-category="' . $module->category . '"
                        data-subcategory="' . $module->sub_cat . '"
                        data-link="' . $module->link . '"
                        data-video="' . $module->video . '"
                        data-status="' . $module->status . '"
                        data-toggle="modal" data-target="#editModuleModal">
                        <i class="bi bi-pencil"></i>
                    </button>';
            })
            ->make(true);
    }
    
    public function addNewModule(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'category' => 'required',
            'subcategory' => 'required',
            'link' => 'required|url',
            'video' => 'required',
            'status' => 'required',
        ]);

        $module = new Emodule();
        $module->title = $validatedData['title'];
        $module->category = $validatedData['category'];
        $module->sub_cat = $validatedData['subcategory'];
        $module->link = $validatedData['link'];
        $module->video = $validatedData['video'];
        $module->status = $validatedData['status'];
        $module->save();
        return redirect()->back()->with('success', 'Module created successfully!');
    }

    public function update(Request $request)
    {

        $validatedData = $request->validate([
            'title' => 'required',
            'category' => 'required',
            'subcategory' => 'required',
            'link' => 'required|url',
            'video' => 'required',
            'status' => 'required',
        ]);

        $module = Emodule::find($request->id);
        if (!$module) {
            return redirect()->back()->with('error', 'Module not found.');
        }
        $module->update($validatedData);

        return redirect()->back()->with('success', 'Module updated successfully!');
    }

    public function destroy($id)
    {
        $module = Emodule::where('id', $id)->delete();

        return redirect()->back()->with('success', 'E-module deleted successfully!');
    }

    public function export()
    {
        return Excel::download(new ListLinksExport, 'List-Links-Export.xlsx');
    }
}
