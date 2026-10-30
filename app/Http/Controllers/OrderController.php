<?php

namespace App\Http\Controllers;

use App\Events\OrderPlaced;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Produk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'quantity'  => 'required|integer|min:1',
        ]);

        $user = auth()->user();
        $produk = Produk::findOrFail($request->produk_id);
        $subtotal = $produk->harga * $request->quantity;

        $customer = Customer::firstOrCreate(['name' => $user->name]);

        $order = Order::create([
            'customer_id'  => $customer->id,
            'total_amount' => $subtotal,
        ]);

        OrderItem::create([
            'order_id'  => $order->id,
            'produk_id' => $produk->id,
            'quantity'  => $request->quantity,
            'price'     => $produk->harga,
            'subtotal'  => $subtotal,
        ]);

        // ... setelah proses simpan order berhasil ...
        event(new OrderPlaced($order, auth()->user()));

        return redirect()->route('admin.produk')->with('success', 'Pesanan berhasil dibuat');
    }

    public function invoice(Order $order)
    {
        $order = Order::with('items.product', 'customer')->findOrFail($order->id);

        // normalize fields expected by the invoice view
        // invoice view expects $invoice->items[*]->total and $invoice->total
        $invoice = $order;
        foreach ($invoice->items as $item) {
            // ensure 'total' key exists on each item
            if (!isset($item->total)) {
                $item->total = $item->subtotal ?? ($item->price * $item->quantity);
            }
        }
        $invoice->total = $invoice->total_amount ?? 0;

        $pdf = Pdf::loadView('invoices.invoice', ['invoice' => $invoice]);

        return $pdf->download('invoice_' . $invoice->id . '.pdf');
    }
}