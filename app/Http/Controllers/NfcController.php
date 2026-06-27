<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NfcController extends Controller
{
    public function index()
    {
        return view('Nfc.index');
    }

    public function scan(Request $request)
    {
        $request->validate([
            'nfc_uid' => 'required|string|max:50',
        ]);

        $nfcUid = $request->input('nfc_uid');
        $user = User::where('nfc_uid', $nfcUid)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'NFC UID tidak terdaftar.',
                'nfc_uid' => $nfcUid,
            ], 404);
        }

        $now = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();

        $alreadyScanned = Attendance::where('user_id', $user->iduser)
            ->whereDate('scanned_at', $today)
            ->first();

        if ($alreadyScanned) {
            return response()->json([
                'status' => 'already',
                'message' => 'Sudah absen hari ini.',
                'user' => [
                    'name' => $user->username ?? $user->email,
                    'scanned_at' => $alreadyScanned->scanned_at->format('H:i:s'),
                ],
            ]);
        }

        $attendance = Attendance::create([
            'user_id' => $user->iduser,
            'scanned_at' => $now,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Absensi berhasil!',
            'user' => [
                'name' => $user->username ?? $user->email,
                'nfc_uid' => $nfcUid,
            ],
            'scanned_at' => $now->format('Y-m-d H:i:s'),
            'attendance_id' => $attendance->id,
        ]);
    }

    public function readNfc(Request $request)
    {
        $request->validate([
            'nfc_uid' => 'required|string|max:50',
        ]);

        $nfcUid = $request->input('nfc_uid');
        $user = User::where('nfc_uid', $nfcUid)->first();

        return response()->json([
            'status' => (bool) $user,
            'nfc_uid' => $nfcUid,
            'user' => $user ? [
                'id' => $user->iduser,
                'name' => $user->username ?? $user->email,
            ] : null,
        ]);
    }

    public function history()
    {
        $attendances = Attendance::with('user')
            ->orderByDesc('scanned_at')
            ->paginate(20);

        return view('Nfc.history', compact('attendances'));
    }

    public function today()
    {
        $today = Carbon::today('Asia/Jakarta');

        $attendances = Attendance::with('user')
            ->whereDate('scanned_at', $today)
            ->orderByDesc('scanned_at')
            ->get();

        $totalUsers = User::whereNotNull('nfc_uid')->count();

        return view('Nfc.today', compact('attendances', 'totalUsers', 'today'));
    }
}
