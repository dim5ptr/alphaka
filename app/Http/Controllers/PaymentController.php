<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY'); // Ganti dengan server key Anda
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY'); // Ganti dengan client key Anda
        Config::$isProduction = false; // Ubah ke true jika menggunakan mode produksi
    }

    public function createTransaction(Request $request)
    {
        // Data transaksi
        $transactionDetails = [
            'order_id' => 'order-id-' . time(),
            'gross_amount' => 5000, // Jumlah total
        ];

        // Data item
        $itemDetails = [
            [
                'id' => 'item1',
                'price' => 5000,
                'quantity' => 1,
                'name' => 'Item Name',
            ],
        ];

        // Data customer
        $customerDetails = [
            'first_name' => "Litto",
            'last_name' => "Widodo",
            'email' => "projekfedweb2@example.com",
            'phone' => "085648129574",
        ];

        // Data transaksi lengkap
        $transactionData = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
        ];

        // Mengambil URL pembayaran
        try {
            $snapToken = Snap::createTransaction($transactionData)->token;
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
