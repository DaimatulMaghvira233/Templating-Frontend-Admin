<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

class ReceiptController extends Controller
{    // Lebar struk printer thermal 58mm = 32 karakter
    private const LEBAR = 32;

    // Buat satu baris dengan teks kiri dan kanan (rata kanan)
    private function baris(string $kiri, string $kanan): string
    {
        $spasi = max(1, self::LEBAR - mb_strlen($kiri) - mb_strlen($kanan));
        return $kiri . str_repeat(' ', $spasi) . $kanan;
    }

    // Teks rata tengah
    private function tengah(string $teks): string
    {
        $kiri = max(0, intdiv(self::LEBAR - mb_strlen($teks), 2));
        return str_repeat(' ', $kiri) . $teks;
    }

    public function print()
    {
        try {
            // Printer asli (Windows): ganti 'POS-58' dengan nama printer di Control Panel > Printers
            // $connector = new WindowsPrintConnector("POS-58");

            // Simulasi tanpa printer fisik: struk disimpan ke file storage/app/struk.txt
            $connector = new FilePrintConnector(storage_path('app/struk.txt'));

            $printer = new Printer($connector);

            $garisDouble = str_repeat('=', self::LEBAR);
            $garisSingle = str_repeat('-', self::LEBAR);

            // Data contoh (nanti diganti data order di Acara 36)
            $items = [
                ['nama' => 'Barang A', 'qty' => 2, 'harga' => 5000],
                ['nama' => 'Barang B', 'qty' => 1, 'harga' => 10000],
            ];
            $total = 0;

            $struk = [];

            // Header
            $struk[] = $this->tengah('TOKO CELL');
            $struk[] = $this->tengah('Jl. Mawar No. 123');
            $struk[] = $garisDouble;
            $struk[] = $this->baris('Tanggal', now()->format('d/m/Y H:i'));
            $struk[] = $garisDouble;

            // Daftar item
            foreach ($items as $item) {
                $subtotal = $item['qty'] * $item['harga'];
                $total += $subtotal;
                $struk[] = $item['nama'];
                $struk[] = $this->baris(
                    '  ' . $item['qty'] . ' x ' . number_format($item['harga'], 0, ',', '.'),
                    number_format($subtotal, 0, ',', '.')
                );
            }

            // Total
            $struk[] = $garisSingle;
            $struk[] = $this->baris('TOTAL', 'Rp' . number_format($total, 0, ',', '.'));
            $struk[] = $garisDouble;
            $struk[] = $this->tengah('Terima kasih!');

            // Kirim ke printer (file simulasi)
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            foreach ($struk as $i => $baris) {
                // 2 baris pertama (nama toko & alamat) dicetak tebal
                $printer->setEmphasis($i === 0);
                $printer->text($baris . "\n");
            }
            $printer->setEmphasis(false);
            $printer->feed(2);
            $printer->cut();
            $printer->close();

            // Tampilan di browser: seperti kertas struk
            $isi = e(implode("\n", $struk));
            $html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Struk</title></head>'
                . '<body style="background:#e9e9e9;margin:0;padding:30px;">'
                . '<pre style="font-family:\'Courier New\',monospace;font-size:14px;line-height:1.4;'
                . 'background:#fff;width:fit-content;margin:0 auto;padding:20px 24px;'
                . 'box-shadow:0 2px 8px rgba(0,0,0,.2);">' . $isi . '</pre>'
                . '<p style="text-align:center;font-family:sans-serif;color:#2e7d32;">'
                . 'Struk berhasil dicetak (tersimpan di storage/app/struk.txt)</p>'
                . '</body></html>';

            return response($html);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}