<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class DistrictController extends Controller
{
    function getDistricts()
    {
        $client = new Client();

        try {
            $response = $client->get('https://www.emsifa.com/api-wilayah-indonesia/api/districts/1371.json');
            $districts = json_decode($response->getBody()->getContents(), true);

            return view('back.layout.districts', ['districts' => $districts]);
        } catch (\Exception $e) {
            return view('error_page')->with('error', 'Gagal memuat daftar distrik: ' . $e->getMessage());
        }
    }

    public function submit(Request $request)
    {
        $selectedDistrict = $request->input('district');
        // Proses data yang dipilih
        return redirect()->back()->with('success', 'Data berhasil dikirim');
    }
}
