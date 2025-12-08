<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\IpsecService;
use App\Models\IpsecLog;

class IpsecController extends Controller
{
    public function index()
    {
        $logs = IpsecLog::latest()->get();

        return view('admin.protocolos.ipsec', compact('logs'));
    }

    public function send(IpsecService $ipsec)
    {
        $resultado = $ipsec->simulateTunnel();

        return back()->with('ipsec', $resultado);
    }
}
