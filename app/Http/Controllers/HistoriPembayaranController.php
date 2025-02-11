<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\HistoriPembayaranResource;
use App\Models\HistoriPembayaran;
use Illuminate\Http\Request;

class HistoriPembayaranController extends Controller
{
    public function index()
    {
        return HistoriPembayaranResource::collection(HistoriPembayaran::all());
    }

    public function store(Request $request)
    {
        $data = $this->getRequestData($request);

        return new HistoriPembayaranResource(HistoriPembayaran::create($data));
    }

    public function show(HistoriPembayaran $historiPembayaran)
    {
        return new HistoriPembayaranResource($historiPembayaran);
    }

    public function update(Request $request, HistoriPembayaran $historiPembayaran)
    {
        $data = $this->getRequestData($request);

        $historiPembayaran->update($data);

        return new HistoriPembayaranResource($historiPembayaran);
    }

    public function destroy(HistoriPembayaran $historiPembayaran)
    {
        $historiPembayaran->delete();

        return response()->json();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    private function getRequestData(Request $request): array
    {
        return $request->validate([
            'transaction_id' => ['required'],
            'order_id' => ['required'],
            'merchant_id' => ['required'],
            'status_message' => ['required'],
            'status_code' => ['required'],
            'signature_key' => ['required'],
            'settlement_time' => ['nullable', 'date'],
            'transaction_time' => ['nullable', 'date'],
            'payment_type' => ['required'],
            'gross_amount' => ['required'],
            'fraud_status' => ['required'],
            'currency' => ['required'],
            'transaction_status' => ['required'],
        ]);
    }
}
