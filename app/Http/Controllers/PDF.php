<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class PDF extends Controller
{
    /**
     * Show main page
     * @return View
     * GET
     */
    public function index(){

        //get all files from storage
        $pdfs = $this->getFilesFromStorage();

        $pdfs = collect($pdfs)->map(function ($pdf){
            return [
                "name" => $pdf,
                "url" => Storage::url("uploads/" . $pdf)
            ];
        });

        return view("pdf.pdf", compact("pdfs"));
    }

    /**
     * Upload the file
     * @param Request $request
     * @return
     * POST
     */
    public function upload(Request $request){

        //validate the request
        $request->validate([
            "file" => ["required", "max:5000", "mimes:pdf"]
        ]);

        $fileName = $this->getFileName($request->file);
        $filePath = 'uploads/' . $fileName;

        $file = Storage::disk('public')->put($filePath, file_get_contents($request->file));

        //handle failed upload
        if (! $file) {
            return redirect()
                ->back()
                ->with([
                "error" => "File could not be stored on your system."
            ]);
        }

        //file is now uploaded
        return redirect()
            ->back()
            ->with([
                "message" => "File uploaded successfully."
            ]);
    }


    /**
     * Merge the pdf files
     * @return \Illuminate\Http\RedirectResponse
     * GET
     */
    public function merge(){

        //get files from storage
        $pdfs = $this->getFilesFromStorage();

        if(count($pdfs) > 0){
            $mergedPDF = PDFMerger::init();

            foreach ($pdfs as $pdf){
                //add pdf for merge
                $mergedPDF->addPDF(Storage::path("public/uploads/" . $pdf), 'all');
            }

            try{
                $meredFileName = time().'.pdf';
                $mergedPDF->merge();
                $mergedPDF->save(public_path($meredFileName));
            }catch (\Throwable $exception){

                return redirect()
                    ->back()
                    ->with([
                        "error" => $exception->getMessage()
                    ]);
            }

            //generate file download response
            return response()
                ->download(public_path($meredFileName));
        }

        return redirect()
            ->back()
            ->with([
                "error" => "No pdf files found to merge."
            ]);
    }

    /**
     * Delete the file
     * @param $fileName
     * @return \Illuminate\Http\RedirectResponse
     * GET
     */
    public function delete($fileName){

        if(Storage::disk("public")->exists("/uploads/" . $fileName)){

            try{
                Storage::disk("public")->delete("uploads/" . $fileName);
                return redirect()
                    ->back()
                    ->with([
                        "message" => "File deleted successfully."
                    ]);
            }catch (\Throwable $exception){
                return redirect()
                    ->back()
                    ->with([
                        "error" => $exception->getMessage()
                    ]);
            }

        }

        return redirect()
            ->back()
            ->with([
                "error" => "File does not exist"
            ]);

    }


    /**
     * @param $file
     * @return string
     */
    public function getFileName($file){
        $fileName = $file->getClientOriginalName();
        $i = 1;

        while(Storage::disk("public")->exists("/uploads/" . $fileName)){
            $fileName = $i . "-" . $fileName;
            $i++;
        }

        return $fileName;
    }

    /**
     * returns all files
     * @return array|\Illuminate\Support\Collection
     */
    public function getFilesFromStorage(){
        $pdfs = Storage::disk("public")->allFiles("uploads");

        if(count($pdfs) > 0){
            $pdfs = collect($pdfs)->map(function ($pdf){
                //remove dir name from file names
                $pdf = explode("uploads/", $pdf);

                return $pdf[1];
            });
        }

        return $pdfs;
    }

}
