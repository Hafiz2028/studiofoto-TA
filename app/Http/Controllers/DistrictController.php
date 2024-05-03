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
            // Fetch districts directly from the API
            $responseDistricts = Http::get("https://apiwilayah.metrosoftware.id/api-wilayah-indonesia/api/districts/{$regencyId}.json");
            $districts = $responseDistricts->json();

            // Villages will be fetched via proxy
            $villages = [];
            if ($districtId) {
                $villages = $this->fetchVillages($districtId);
            }

            return view('back.layout.districts', compact('districts', 'villages'));
        } catch (\Exception $e) {
            // Return view with error message if there's an exception
            return view('back.layout.districts')->with('error', 'Failed to load data: ' . $e->getMessage());
        }
    }

    private function fetchVillages($districtId)
    {
        try {
            // Proxy request to fetch villages
            $responseVillages = Http::get("https://apiwilayah.metrosoftware.id/api-wilayah-indonesia/api/villages/{$districtId}.json");
            return $responseVillages->json();
        } catch (\Exception $e) {
            // Return empty array if there's an exception
            return [];
        }
    }

    public function submit(Request $request)
    {
        $selectedDistrict = $request->input('district_id');
        $selectedVillage = $request->input('village_id');
        // Process selected data
        return redirect()->back()->with('success', 'Data successfully submitted');
    }
}
