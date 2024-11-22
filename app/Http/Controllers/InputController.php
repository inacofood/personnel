<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Emodule;
use PhpOffice\PhpSpreadsheet\IOFactory;


class InputController extends Controller
{
    public function addNewModule(Request $request)
{
    $title = $request->title;
    $category = $request->category;
    $subcategory = $request->subcategory;
    $link = $request->link;
    $video = $request->video;
    $status = $request->status;

    try {
        $item = new Emodule();
        $item->title = $title;
        $item->category = $category;
        $item->sub_cat = $subcategory;
        $item->link = $link;
        $item->video = $video;
        $item->status = $status;
        $item->save();

        return back()->with('success', 'Data Berhasil Di Input ');
    } catch (\Exception $e) {
        return back()->with('error', 'Data Berhasil Di Input '. $e->getMessage());
    }
}

    public function Download() {
        $file_name = "template_import_emodule.xlsx";
        $file_path = public_path($file_name);
        return response()->download($file_path);
    }

    public function importFromExcel(Request $request)
        {
            try {
                if ($request->hasFile('excelFile') && $request->file('excelFile')->isValid()) {
                    
                    $excelFile = $request->file('excelFile');
                    
                    $reader = IOFactory::createReaderForFile($excelFile->getPathname());
                 
                    $spreadsheet = $reader->load($excelFile->getPathname());
            
                    $worksheet = $spreadsheet->getActiveSheet();
                    $highestRow = $worksheet->getHighestDataRow();

                    for ($row = 2; $row <= $highestRow; ++$row) {
                       
                        $category = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $subcategory = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $title = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $status = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $link = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $video = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                     
                        $item = new Emodule();
                        $item->category  = $category ;
                        $item->sub_cat = $subcategory;
                        $item->title = $title;
                        $item->status = $status;
                        $item->link  = $link ;
                        $item->video = $video;
                        $item->save();
                       
                    }
                    return redirect()->back()->with('success', 'Modules imported successfully.');
                } else {
                    return back()->with('error', 'No valid file uploaded.');
                }
            } catch (\Exception $e) {
                return back()->with('error', 'Error during import: ' . $e->getMessage());
            }
        }
    }


