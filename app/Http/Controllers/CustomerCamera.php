<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\district;
use App\Models\provinsi;
use App\Models\regencies;
use App\Models\Villages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class CustomerCamera extends Controller
{
    public function index()
    {
        $customers = Customer::orderByDesc('id')->get();

        return view('CustomerCamera.index', compact('customers'));
    }

    public function createBlob()
    {
        $provinces = provinsi::select('id', 'name')->orderBy('name')->get();

        return view('CustomerCamera.create_blob', compact('provinces'));
    }

    public function storeBlob(Request $request)
    {
        $validated = $request->validate([
            'nama_customer' => 'required|string|max:255',
            'province_id' => 'required|exists:reg_provinces,id',
            'regency_id' => 'required|exists:reg_regencies,id',
            'village_id' => 'required|exists:reg_villages,id',
            'photo_data' => 'required|string',
        ]);

        [$province, $regency, $village] = $this->resolveRegion($validated);
        [$mimeType, $binaryContent] = $this->decodePhotoData($validated['photo_data']);

        Customer::create([
            'nama_customer' => $validated['nama_customer'],
            'province_id' => $province->id,
            'province_name' => $province->name,
            'regency_id' => $regency->id,
            'regency_name' => $regency->name,
            'village_id' => $village->id,
            'village_name' => $village->name,
            'foto_blob' => $binaryContent,
            'foto_mime' => $mimeType,
            'foto_path' => null,
        ]);

        return redirect()->route('customer-camera.customers.index')
            ->with('success', 'Customer berhasil disimpan ke database sebagai BLOB.');
    }

    public function createPath()
    {
        $provinces = provinsi::select('id', 'name')->orderBy('name')->get();

        return view('CustomerCamera.create_path', compact('provinces'));
    }

    public function storePath(Request $request)
    {
        $validated = $request->validate([
            'nama_customer' => 'required|string|max:255',
            'province_id' => 'required|exists:reg_provinces,id',
            'regency_id' => 'required|exists:reg_regencies,id',
            'village_id' => 'required|exists:reg_villages,id',
            'photo_data' => 'required|string',
        ]);

        [$province, $regency, $village] = $this->resolveRegion($validated);
        [$mimeType, $binaryContent, $extension] = $this->decodePhotoData($validated['photo_data']);
        $filename = 'customer_' . now()->format('YmdHis') . '_' . uniqid() . '.' . $extension;
        $relativePath = 'customer/' . $filename;

        Storage::disk('public')->put($relativePath, $binaryContent);

        Customer::create([
            'nama_customer' => $validated['nama_customer'],
            'province_id' => $province->id,
            'province_name' => $province->name,
            'regency_id' => $regency->id,
            'regency_name' => $regency->name,
            'village_id' => $village->id,
            'village_name' => $village->name,
            'foto_path' => $relativePath,
            'foto_mime' => $mimeType,
            'foto_blob' => null,
        ]);

        return redirect()->route('customer-camera.customers.index')
            ->with('success', 'Customer berhasil disimpan sebagai file gambar dan path disimpan ke database.');
    }

    public function blobImage(Customer $customer)
    {
        if (! $customer->foto_blob) {
            abort(404);
        }

        $mimeType = $customer->foto_mime ?? 'image/jpeg';
        $fileName = 'customer-' . $customer->id . '.jpg';

        return response($customer->foto_blob, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Length', strlen($customer->foto_blob))
            ->header('Content-Disposition', 'inline; filename="' . $fileName . '"')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    public function regenciesByProvince(Request $request)
    {
        $request->validate([
            'province_id' => 'required',
        ]);

        $regencies = regencies::select('id', 'name')
            ->where('province_id', $request->province_id)
            ->orderBy('name')
            ->get();

        return response()->json($regencies);
    }

    public function villagesByRegency(Request $request)
    {
        $request->validate([
            'regency_id' => 'required',
        ]);

        $villages = Villages::query()
            ->select('reg_villages.id', 'reg_villages.name')
            ->join('reg_districts', 'reg_districts.id', '=', 'reg_villages.district_id')
            ->where('reg_districts.regency_id', $request->regency_id)
            ->orderBy('reg_villages.name')
            ->distinct()
            ->get();

        return response()->json($villages);
    }

    private function resolveRegion(array $validated): array
    {
        $province = provinsi::select('id', 'name')->where('id', $validated['province_id'])->firstOrFail();
        $regency = regencies::select('id', 'name', 'province_id')
            ->where('id', $validated['regency_id'])
            ->where('province_id', $province->id)
            ->firstOrFail();

        $village = Villages::query()
            ->select('reg_villages.id', 'reg_villages.name')
            ->join('reg_districts', 'reg_districts.id', '=', 'reg_villages.district_id')
            ->where('reg_villages.id', $validated['village_id'])
            ->where('reg_districts.regency_id', $regency->id)
            ->firstOrFail();

        return [$province, $regency, $village];
    }

    private function decodePhotoData(string $photoData): array
    {
        if (! preg_match('/^data:(image\/(jpeg|jpg|png|webp));base64,/', $photoData, $matches)) {
            throw ValidationException::withMessages([
                'photo_data' => 'Format gambar tidak valid.',
            ]);
        }

        $mimeType = $matches[1] === 'image/jpg' ? 'image/jpeg' : $matches[1];
        $extensionMap = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        $base64Data = substr($photoData, strpos($photoData, ',') + 1);
        $binaryContent = base64_decode($base64Data, true);

        if ($binaryContent === false) {
            throw ValidationException::withMessages([
                'photo_data' => 'Data gambar tidak valid.',
            ]);
        }

        return [$mimeType, $binaryContent, $extensionMap[$mimeType] ?? 'jpg'];
    }
}
