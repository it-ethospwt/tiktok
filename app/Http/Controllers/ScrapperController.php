<?php

namespace App\Http\Controllers;

use App\Exports\ExportPDF;
use App\Models\Scrapper;
use Smalot\PdfParser;
use Spatie\PdfToText\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;


class ScrapperController extends Controller
{
    public function index()
    {
        return view('dash/app', [
            'scrapper' => Scrapper::all()
        ]);
    }

    public function upload(Request $request)
    {
        // Simpan file PDF
        $path = $request->file('file')->storeAs('pdfs', 'Resi-Tiktok-' . uniqid() . '.pdf', 'public');

        // Parse PDF
        $path = str_replace('/', '\\', $path);
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile(public_path('storage' . '\\' . $path));
        $text = $pdf->getText();

        // Proses data dan simpan ke database
        $data = $this->parseData($text);
        DB::table('scrappers')->insert($data);

        Alert::success('Horee', 'Berhasil Upload PDF');

        return redirect()->back()->with('success', 'Data berhasil diimport.');
    }

    function export_excel()
    {
        return Excel::download(new ExportPDF, "HasilScrapping.xlsx");
    }

    public function delete()
    {
        DB::table('scrappers')->truncate();
        return redirect()->back()->with('success', 'Berhasil Hapus Semua Data');
    }

    private function parseData($text)
    {
        // Contoh penggunaan ekspresi reguler untuk mengekstrak nomor resi
        preg_match('/Penerima : (\w+), \(\+(\d+)\)(\d+)/', $text, $matchesPenerima);

        if (!empty($matchesPenerima)) {
            $nama = $matchesPenerima[1];
            $telp = '+' . $matchesPenerima[2] . $matchesPenerima[3];

            // Cari alamat yang mungkin berada di bawah teks penerima
            preg_match('/Penerima : \w+, \(\+\d+\)\d+[\s\n]+(.+)/s', $text, $matchesAlamat);
            preg_match('/Qty\s*:\s*(\d+)/', $text, $matchesQty);
            preg_match('/SKU\[Seller SKU\]\s*:\s*(.+)/', $text, $matchesSellerSKU);
            preg_match('/(\w+)/', $text, $matchesResi);

            $alamat = !empty($matchesAlamat) ? trim($matchesAlamat[1]) : 'N/A';
            $qty = !empty($matchesQty) ? $matchesQty[1] : 'N/A';
            $item = !empty($matchesSellerSKU) ? $matchesSellerSKU[1] : 'N/A';
            $resi = $matchesResi[0];
        } else {
            $nama = 'N/A';
            $telp = 'N/A';
            $alamat = 'N/A';
            $item = 'N/A';
            $qty = 'N/A';
            $resi = 'N/A';
        }

        // Implementasikan logika parsing sesuai dengan struktur PDF yang Anda miliki
        // Contoh sederhana:
        $parsedData = [
            'nama' => $nama,
            'telp' => $telp,
            'alamat' => $alamat,
            'item' => $item,
            'qty' => $qty,
            'resi' => $resi,
            // ... tambahkan field lainnya sesuai kebutuhan Anda
        ];

        return $parsedData;
    }
}
