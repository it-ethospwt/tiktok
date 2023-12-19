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
        // echo $text;

        // Proses data dan simpan ke database
        $data = $this->parseData($text);
        DB::table('scrappers')->ParseData($data);

        Alert::success('Horee', 'Berhasil Upload PDF');

        // return redirect()->back()->with('success', 'Data berhasil diimport.');
        return $text;
    }


    private function parseData($text)
    {
        // Contoh penggunaan ekspresi reguler untuk mengekstrak nomor resi
        preg_match('/JX (\d+)/', $text, $matchesResi);

        $resi = !empty($matchesResi) ? 'JX' . $matchesResi[1] : 'N/A';

        // Mengekstrak informasi penerima
        preg_match('/P en erim a : (\w+), \(\+(\d+)\)(\d+)/', $text, $matchesPenerima);
        $nama = !empty($matchesPenerima) ? $matchesPenerima[1] : 'N/A';
        $telp = !empty($matchesPenerima) ? '+' . $matchesPenerima[2] . $matchesPenerima[3] : 'N/A';

        // Cari alamat yang mungkin berada di bawah teks penerima
        preg_match('/P en erim a : \w+, \(\+\d+\)\d+[\s\n]+(.+)/s', $text, $matchesAlamat);
        $alamat = !empty($matchesAlamat) ? trim($matchesAlamat[1]) : 'N/A';

        // Mengekstrak informasi qty
        preg_match('/Q ty\s*:\s*(\d+)/', $text, $matchesQty);
        $qty = !empty($matchesQty) ? $matchesQty[1] : 'N/A';

        // Mengekstrak informasi SKU dan produk
        preg_match('/SK U\[S elle r S K U\]\s*:\s*(.+)/', $text, $matchesSKU);
        $produk = !empty($matchesSKU) ? $matchesSKU[1] : 'N/A';

        // Mengekstrak informasi nama produk
        preg_match('/([^\n]+)Tota/', $text, $matchesProduk);
        $produkDetail = !empty($matchesProduk) ? $matchesProduk[1] : 'N/A';

        // Implementasikan logika parsing sesuai dengan struktur PDF yang Anda miliki
        // Contoh sederhana:
        $parsedData = [
            'nama' => $nama,
            'telp' => $telp,
            'alamat' => $alamat,
            'item' => $produk . ' ' . $produkDetail,
            'qty' => $qty,
            'resi' => $resi,
            // ... tambahkan field lainnya sesuai kebutuhan Anda
        ];

        return $parsedData;
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
}
