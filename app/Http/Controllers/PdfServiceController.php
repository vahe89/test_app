<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PdfServiceController extends Controller
{
    public function searchFor($searchText, $pdfFilePath)
    {
        try {
            $parser = new Parser();
            $pdf = $parser->parseFile($pdfFilePath);
            $text = $pdf->getText();
            return strpos($text, $searchText) !== false;
        } catch (\Exception $e) {

            return false;
        }
    }

    public function uploadPDF(Request $request)
    {
        $rules = [
            'pdf_file' => 'required|mimes:pdf',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return abort(422);
        }
        $uploadedFile = $request->file('pdf_file');
        $fileName = $uploadedFile->getClientOriginalName();
        $fileSize = $uploadedFile->getSize();

        $containsProposal = $this->searchFor('Proposal', $uploadedFile->path());
        if (!$containsProposal) {
            return abort(422);
        }

        $existingFile = File::where('name', $fileName)
            ->where('size', $fileSize)
            ->first();

        if ($existingFile) {

            Storage::delete('public/pdf/' . $fileName);

            $uploadedFile->storeAs('public/pdf', $fileName);
        } else {

            File::create([
                'name' => $fileName,
                'size' => $fileSize,

            ]);
            $uploadedFile->storeAs('public/pdf', $fileName);

        }

        return redirect()->back()->with('success', 'File uploaded successfully.');
    }
}
