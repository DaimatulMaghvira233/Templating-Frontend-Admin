<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
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

        if (ob_get_length()) {
            ob_end_clean();
        }

        return $pdf->stream('invoice_' . $invoice->id . '.pdf');
    }
}