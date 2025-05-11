<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use App\Models\City;
use App\Models\District;
use App\Models\Province;
use App\Models\Subdistrict;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function __construct()
    {
    }

    public function province(Request $request)
    {
        $id = $request->get('id');
        $nama_provinsi = $request->get('name');

        $province = new Province();

        if ($id != null || $id != '') $province = $province->where('id', $id);
        if ($nama_provinsi != null || $nama_provinsi != '') $province = $province->where('nama_provinsi', 'like', "%$nama_provinsi%");

        $province = $province->select('id', 'nama_provinsi')->orderBy('nama_provinsi', 'asc')->get();

        if (count($province) <= 0) return ResponseHelper::error(null, 'Province not found', 200);

        return ResponseHelper::success(
            message: 'Success get all data',
            status_code: 200,
            data: $province
        );
    }

    public function city(Request $request)
    {
        $id = $request->get('id');
        $id_provinsi = $request->get('province_id');
        $nama_kota = $request->get('name');

        $city = new City();

        if ($id != null || $id != '') $city = $city->where('id', $id);
        if ($id_provinsi != null || $id_provinsi != '') $city = $city->where('id_provinsi', $id_provinsi);
        if ($nama_kota != null || $nama_kota != '') $city = $city->where('nama_kota', 'like', "%$nama_kota%");

        $city = $city->select('id', 'id_provinsi', 'nama_kota')->orderBy('nama_kota', 'asc')->get();

        if (count($city) <= 0) return ResponseHelper::error(null, 'City not found', 200);

        return ResponseHelper::success(
            message: 'Success get all data',
            status_code: 200,
            data: $city
        );
    }

    public function district(Request $request)
    {
        $id = $request->get('id');
        $id_kota = $request->get('city_id');
        $nama_kecamatan = $request->get('name');

        $district = new District();

        if ($id != null || $id != '') $district = $district->where('id', $id);
        if ($id_kota != null || $id_kota != '') $district = $district->where('id_kota', $id_kota);
        if ($nama_kecamatan != null || $nama_kecamatan != '') $district = $district->where('nama_kecamatan', 'like', "%$nama_kecamatan%");

        $district = $district->select('id', 'nama_kecamatan', 'id_kota')->orderBy('nama_kecamatan', 'asc')->get();

        if (count($district) <= 0) return ResponseHelper::error(null, 'District not found', 200);

        return ResponseHelper::success(
            message: 'Success get all data',
            status_code: 200,
            data: $district
        );
    }

    public function subdistrict(Request $request)
    {
        $id = $request->get('id');
        $id_kecamatan = $request->get('district_id');
        $nama_kelurahan = $request->get('name');

        $subdistrict = new Subdistrict();

        if ($id != null || $id != '') $subdistrict = $subdistrict->where('id', $id);
        if ($id_kecamatan != null || $id_kecamatan != '') $subdistrict = $subdistrict->where('id_kecamatan', $id_kecamatan);
        if ($nama_kelurahan != null || $nama_kelurahan != '') $subdistrict = $subdistrict->where('nama_kelurahan', 'like', "%$nama_kelurahan%");

        $subdistrict = $subdistrict->select('id', 'id_kecamatan', 'nama_kelurahan')->orderBy('nama_kelurahan', 'asc')->get();

        if (count($subdistrict) <= 0) return ResponseHelper::error(null, 'Subdistrict not found', 200);

        return ResponseHelper::success(
            message: 'Success get all data',
            status_code: 200,
            data: $subdistrict
        );
    }
}
