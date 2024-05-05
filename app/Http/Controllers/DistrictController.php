<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class DistrictController extends Controller
{
    public function getDistricts(Request $request)
    {
        $regencyId = 1371;
        $districtId = $request->input('district_id');

        try {
            $responseDistricts = Http::get("https://apiwilayah.metrosoftware.id/api-wilayah-indonesia/api/districts/{$regencyId}.json");
            $districts = $responseDistricts->json();

            $villages = [];
            if ($districtId) {
                $villages = $this->fetchVillages($districtId);
            }

            return view('back.layout.districts', compact('districts', 'villages'));
        } catch (\Exception $e) {
            return view('back.layout.districts')->with('error', 'Failed to load data: ' . $e->getMessage());
        }
    }

    private function fetchVillages($districtId)
    {
        try {
            $responseVillages = Http::get("https://apiwilayah.metrosoftware.id/api-wilayah-indonesia/api/villages/{$districtId}.json");
            return $responseVillages->json();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function submit(Request $request)
    {
        $selectedDistrict = $request->input('district_id');
        $selectedVillage = $request->input('village_id');
        return redirect()->back()->with('success', 'Data successfully submitted');
    }
}
