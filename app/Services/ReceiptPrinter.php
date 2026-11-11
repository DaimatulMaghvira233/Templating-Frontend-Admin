<?php

namespace App\Services;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class ReceiptPrinter
{
    // Lebar kertas thermal 58 mm = 32 karakter
    private const LEBAR = 32;

    public function buatBaris(array $data): array
    {
        $garisDouble = str_repeat('=', self::LEBAR);
        $garisSingle = str_repeat('-', self::LEBAR);

        $subtotal = 0;
        foreach ($data['items'] as $item) {
            $subtotal += $item['qty'] * $item['harga'];
        }
        $diskon = min((int) ($data['diskon'] ?? 0), $subtotal);
        $total  = $subtotal - $diskon;

        $baris = [];
        $baris[] = $this->tengah(strtoupper($data['toko']));
        if (!empty($data['alamat'])) {
            $baris[] = $this->tengah($data['alamat']);
        }
        $baris[] = $garisDouble;
        if (!empty($data['kode'])) {
            $baris[] = $this->kiriKanan('No', $data['kode']);
        }
        $baris[] = $this->kiriKanan('Tanggal', $data['tanggal']);
        $baris[] = $garisDouble;

        foreach ($data['items'] as $item) {
            $baris[] = $this->potong($item['nama']);
            $baris[] = $this->kiriKanan(
                '  ' . $item['qty'] . ' x ' . $this->rp($item['harga']),
                $this->rp($item['qty'] * $item['harga'])
            );
        }

        $baris[] = $garisSingle;
        $baris[] = $this->kiriKanan('Subtotal', $this->rp($subtotal));
        if ($diskon > 0) {
            // Tampilkan persen di label jika diskon diinput dalam persen
            $label = !empty($data['diskon_persen'])
                ? 'Diskon ' . ($data['diskon_persen'] + 0) . '%'
                : 'Diskon';
            $baris[] = $this->kiriKanan($label, '-' . $this->rp($diskon));
        }
        $baris[] = $this->kiriKanan('TOTAL', 'Rp' . $this->rp($total));
        $baris[] = $garisDouble;
        $baris[] = $this->tengah('Terima kasih!');
        $baris[] = $this->tengah('Selamat menikmati');

        return $baris;
    }

    public function cetak(array $data): ?string
    {
        $baris = $this->buatBaris($data);
        $driver = env('PRINTER_DRIVER', 'file');
        $lokasi = null;

        if ($driver === 'windows') {
            $connector = new WindowsPrintConnector(env('PRINTER_NAME', 'POS-58'));
        } else {
            $folder = storage_path('app/receipts');
            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }
            $nama   = 'struk-' . preg_replace('/[^A-Za-z0-9_-]/', '', $data['kode'] ?? date('YmdHis'));
            $lokasi = $folder . DIRECTORY_SEPARATOR . $nama . '.txt';

            // Versi teks biasa: mudah dibuka dan di-screenshot untuk laporan
            file_put_contents($lokasi, implode(PHP_EOL, $baris) . PHP_EOL);

            // Versi ESC/POS mentah: persis data yang dikirim ke printer
            $connector = new FilePrintConnector($folder . DIRECTORY_SEPARATOR . $nama . '.prn');
        }

        $printer = new Printer($connector);
        try {
            foreach ($baris as $i => $teks) {
                $printer->setEmphasis($i === 0); // nama toko tebal
                $printer->text($teks . "\n");
            }
            $printer->setEmphasis(false);
            $printer->feed(2);
            $printer->cut();
        } finally {
            $printer->close();
        }

        return $lokasi;
    }

    private function rp($angka): string
    {
        return number_format($angka, 0, ',', '.');
    }

    private function potong(string $teks): string
    {
        return mb_strlen($teks) > self::LEBAR ? mb_substr($teks, 0, self::LEBAR - 1) . '.' : $teks;
    }

    private function kiriKanan(string $kiri, string $kanan): string
    {
        $spasi = max(1, self::LEBAR - mb_strlen($kiri) - mb_strlen($kanan));
        return $kiri . str_repeat(' ', $spasi) . $kanan;
    }

    private function tengah(string $teks): string
    {
        $teks = $this->potong($teks);
        return str_repeat(' ', intdiv(self::LEBAR - mb_strlen($teks), 2)) . $teks;
    }
}