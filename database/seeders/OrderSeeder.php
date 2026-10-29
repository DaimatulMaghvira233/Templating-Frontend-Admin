<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Produk;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customer = Customer::create(['name' => 'Budi Santoso']);
        $order = Order::create(['customer_id' => $customer->id, 'total_amount' => 0]);

        $total = 0;
        foreach (Produk::take(2)->get() as $p) {
            $qty = 2;
            $subtotal = $p->harga * $qty;

            OrderItem::create([
                'order_id'  => $order->id,
                'produk_id' => $p->id,
                'quantity'  => $qty,
                'price'     => $p->harga,
                'subtotal'  => $subtotal,
            ]);
            $total += $subtotal;
        }

        $order->update(['total_amount' => $total]);
    }
}