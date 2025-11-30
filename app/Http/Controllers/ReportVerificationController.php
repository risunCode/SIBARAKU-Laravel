<?php

namespace App\Http\Controllers;

use App\Models\ReportSignature;
use Illuminate\Http\Request;

class ReportVerificationController extends Controller
{
    /**
     * Verify report signature
     */
    public function verify(Request $request, string $hash)
    {
        $signature = ReportSignature::verifySignature($hash);
        
        if (!$signature) {
            return view('verification.result', [
                'status' => 'invalid',
                'message' => 'Laporan tidak valid atau telah dimodifikasi',
                'hash' => $hash
            ]);
        }
        
        // Load report and user data
        $signature->load(['signable', 'user']);
        
        return view('verification.result', [
            'status' => 'valid',
            'message' => 'Laporan valid dan terpercaya',
            'signature' => $signature,
            'hash' => $hash
        ]);
    }
    
    /**
     * Verification page - show form to input hash manually
     */
    public function index()
    {
        return view('verification.index');
    }
    
    /**
     * Handle manual verification form
     */
    public function check(Request $request)
    {
        $request->validate([
            'hash' => 'required|string|min:10'
        ]);
        
        return redirect()->route('report.verify', ['hash' => $request->hash]);
    }
}
