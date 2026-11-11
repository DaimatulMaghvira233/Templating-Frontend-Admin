<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesanan baru - Kopi Kita</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400..700&family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --pine: #1F3D36;
            --pine-soft: #2D5247;
            --bg: #E3E9E1;
            --panel: #FBFBF7;
            --ink: #1B2623;
            --muted: #66756F;
            --line: #D3DBD4;
            --aren: #B3701F;
            --err: #B3261E;
            --paper: #FFFEFA;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: var(--bg);
            color: var(--ink);
            font-family: 'Bricolage Grotesque', system-ui, -apple-system, 'Segoe UI', sans-serif;
            font-size: 16px;
            line-height: 1.5;
        }
        .wrap { max-width: 1040px; margin: 0 auto; padding: 36px 20px 72px; }
        .wrap.sempit { max-width: 680px; }
        .brand {
            display: flex; align-items: center; gap: 10px;
            color: var(--pine); font-weight: 700; font-size: 20px;
            letter-spacing: -0.01em; margin-bottom: 30px;
        }
        .page {
            display: grid; grid-template-columns: minmax(0, 1fr);
            gap: 48px; align-items: start;
        }
        .page.dua { grid-template-columns: minmax(0, 1.15fr) minmax(0, 0.85fr); }
        h1 {
            font-size: clamp(36px, 5.4vw, 56px); line-height: 1.02;
            letter-spacing: -0.03em; font-weight: 700;
            margin: 0 0 10px; color: var(--pine);
        }
        .lead { color: var(--muted); margin: 0 0 28px; max-width: 44ch; }

        .panel {
            background: var(--panel); border: 1px solid var(--line);
            border-radius: 18px; padding: 24px;
        }
        .galat {
            background: #FBEAE8; border: 1px solid #EBC3BE; color: var(--err);
            border-radius: 10px; padding: 10px 14px; margin-bottom: 16px; font-size: 15px;
        }

        .head, .item {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 116px 116px 34px;
            gap: 10px; align-items: center;
        }
        .head { font-size: 14px; color: var(--muted); padding-bottom: 6px; }
        .item { margin-bottom: 10px; }

        input { font: inherit; color: inherit; }
        input[type=number] { -moz-appearance: textfield; appearance: textfield; }
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }

        .nama, .harga, .diskon-in input {
            width: 100%; padding: 11px 12px; background: #fff;
            border: 1px solid var(--line); border-radius: 10px;
        }
        .nama::placeholder, .harga::placeholder { color: #9AA8A2; }

        .qty {
            display: flex; background: #fff; border: 1px solid var(--line);
            border-radius: 10px; overflow: hidden;
        }
        .qty button {
            width: 34px; border: 0; background: transparent; color: var(--pine);
            font-size: 18px; cursor: pointer;
        }
        .qty button:hover { background: #EEF3EE; }
        .qty .jml { width: 100%; min-width: 0; border: 0; text-align: center; padding: 11px 0; background: transparent; }

        .hapus {
            width: 34px; height: 34px; border: 0; border-radius: 50%;
            background: transparent; color: var(--muted); font-size: 20px; cursor: pointer;
        }
        .hapus:hover { background: #F3E3E1; color: var(--err); }

        .tambah {
            width: 100%; margin-top: 4px; padding: 11px;
            border: 1.5px dashed #B7C4BC; border-radius: 10px;
            background: transparent; color: var(--pine);
            font: inherit; font-weight: 600; cursor: pointer;
        }
        .tambah:hover { background: #F0F4EF; }

        .diskon {
            display: flex; align-items: center; justify-content: space-between;
            gap: 12px; margin-top: 22px;
        }
        .diskon label { color: var(--muted); }
        .diskon-in { position: relative; width: 190px; }
        .diskon-in span {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            color: var(--muted); font-size: 15px; pointer-events: none;
        }
        .diskon-in input { padding-right: 32px; text-align: right; }

        .foot {
            display: flex; align-items: center; justify-content: space-between;
            gap: 16px; margin-top: 22px; padding-top: 20px; border-top: 1px solid var(--line);
        }
        .total-label { color: var(--muted); font-size: 14px; }
        .total-angka { font-size: 32px; font-weight: 700; letter-spacing: -0.02em; color: var(--pine); line-height: 1.1; }
        .cetak {
            background: var(--pine); color: #fff; border: 0; border-radius: 12px;
            padding: 15px 28px; font: inherit; font-weight: 600; font-size: 17px;
            cursor: pointer; transition: background .15s;
        }
        .cetak:hover { background: var(--pine-soft); }

        input:focus-visible, button:focus-visible {
            outline: 3px solid #7FB5A4; outline-offset: 1px;
        }

        /* Struk */
        .sisi { position: sticky; top: 24px; }
        .struk-wrap {
            position: relative; width: fit-content; max-width: 100%;
            margin: 6px auto 0; transform: rotate(1.2deg);
            filter: drop-shadow(0 12px 18px rgba(31, 61, 54, .2));
        }
        .kertas {
            position: relative; background: var(--paper);
            padding: 30px 28px 52px; border-radius: 3px 3px 0 0;
            font-family: 'Courier Prime', 'Courier New', monospace;
            font-size: 13.5px; line-height: 1.45; color: #222;
        }
        .kertas::after {
            content: ""; position: absolute; left: 0; right: 0; top: 100%; height: 8px;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='8' viewBox='0 0 16 8'%3E%3Cpath d='M0 0 L8 8 L16 0 Z' fill='%23FFFEFA'/%3E%3C/svg%3E") repeat-x;
        }
        .baris { white-space: pre; min-height: 1.45em; }
        .baris.judul { font-weight: 700; }
        .cap {
            position: absolute; right: 16px; bottom: 10px; transform: rotate(-8deg);
            border: 2px solid var(--aren); color: var(--aren); border-radius: 6px;
            padding: 1px 10px; font-family: 'Bricolage Grotesque', sans-serif;
            font-weight: 700; font-size: 16px; opacity: .85; pointer-events: none;
            background: rgba(255, 254, 250, .6);
        }
        .cap[hidden] { display: none; }
        .catatan { text-align: center; color: var(--muted); font-size: 14px; margin: 26px 0 0; }

        .struk-wrap.baru { animation: cetak .9s cubic-bezier(.3, .7, .2, 1) both; }
        @keyframes cetak {
            from { clip-path: inset(-30px -30px 100% -30px); }
            to   { clip-path: inset(-30px -30px -30px -30px); }
        }
        @media (prefers-reduced-motion: reduce) {
            .struk-wrap.baru { animation: none; }
        }

        @media (max-width: 860px) {
            .page.dua { grid-template-columns: 1fr; gap: 36px; }
            .sisi { position: static; }
        }
        @media (max-width: 560px) {
            .panel { padding: 18px; }
            .head { display: none; }
            .item { grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) 34px; }
            .item .nama { grid-column: 1 / -1; }
            .foot { flex-direction: column; align-items: stretch; }
            .cetak { width: 100%; }
            .kertas { font-size: 12.5px; padding: 26px 20px 30px; }
        }
    </style>
</head>
<body>
<div class="wrap{{ isset($baris) ? '' : ' sempit' }}">
    <div class="brand">
        <svg width="28" height="28" viewBox="0 0 28 28" aria-hidden="true">
            <path d="M5 11h14v5a6 6 0 0 1-6 6h-2a6 6 0 0 1-6-6z" fill="currentColor"/>
            <path d="M19 13h1.5a3 3 0 0 1 0 6H19" fill="none" stroke="currentColor" stroke-width="2"/>
            <path d="M9 4c0 2 2 2 2 4M14 4c0 2 2 2 2 4" fill="none" stroke="#B3701F" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
        Kopi Kita
    </div>

    <div class="page{{ isset($baris) ? ' dua' : '' }}">
        <main>
            <h1>Pesanan baru</h1>
            <p class="lead">Isi produk yang dibeli, lalu cetak struknya.</p>

            <div class="panel">
                @if ($errors->any())
                    <div class="galat" role="alert">
                        @foreach ($errors->all() as $e)
                            <div>{{ $e }}</div>
                        @endforeach
                    </div>
                @endif

                <form id="form" method="POST" action="{{ route('struk.cetak') }}">
                    @csrf
                    <div class="head" aria-hidden="true">
                        <span>Produk</span><span>Jumlah</span><span>Harga (Rp)</span><span></span>
                    </div>
                    <div id="daftar"></div>
                    <button type="button" class="tambah" id="tambah">+ Tambah produk</button>

                    <div class="diskon">
                        <label for="diskon">Diskon</label>
                        <div class="diskon-in">
                            <input type="number" id="diskon" name="diskon" value="0" min="0" max="100" step="any" inputmode="decimal">
                            <span>%</span>
                        </div>
                    </div>

                    <div class="foot">
                        <div>
                            <div class="total-label">Total bayar</div>
                            <div class="total-angka" id="total">Rp0</div>
                        </div>
                        <button type="submit" class="cetak">Cetak struk</button>
                    </div>
                </form>
            </div>
        </main>

        @isset($baris)
        <aside class="sisi" aria-label="Struk">
            <div class="struk-wrap baru">
                <div class="kertas">
                    @foreach ($baris as $i => $b)
                        <div class="baris{{ $i === 0 ? ' judul' : '' }}">{{ $b === '' ? ' ' : $b }}</div>
                    @endforeach
                    <div class="cap">Tercetak</div>
                </div>
            </div>
            <p class="catatan">
                @if (!empty($lokasi))
                    Tersimpan sebagai {{ basename($lokasi) }} di storage/app/receipts.
                @else
                    Struk sudah dikirim ke printer.
                @endif
            </p>
        </aside>
        @endisset
    </div>
</div>

<script>
(function () {
    const daftar = document.getElementById('daftar');
    const diskonEl = document.getElementById('diskon');
    const totalEl = document.getElementById('total');
    let n = 0;

    const rp = v => Number(v || 0).toLocaleString('id-ID');

    function tambahBaris() {
        const r = document.createElement('div');
        r.className = 'item';
        r.innerHTML =
            '<input class="nama" type="text" name="items[' + n + '][nama]" placeholder="Contoh: Kopi susu gula aren" autocomplete="off" required>' +
            '<div class="qty">' +
                '<button type="button" data-d="-1" aria-label="Kurangi jumlah">&minus;</button>' +
                '<input class="jml" type="number" name="items[' + n + '][qty]" value="1" min="1" inputmode="numeric" required>' +
                '<button type="button" data-d="1" aria-label="Tambah jumlah">+</button>' +
            '</div>' +
            '<input class="harga" type="number" name="items[' + n + '][harga]" placeholder="18000" min="0" inputmode="numeric" required>' +
            '<button type="button" class="hapus" aria-label="Hapus produk">&times;</button>';
        daftar.appendChild(r);
        n++;
        return r;
    }

    function hitung() {
        let sub = 0;
        daftar.querySelectorAll('.item').forEach(r => {
            sub += (parseInt(r.querySelector('.jml').value) || 0) * (parseInt(r.querySelector('.harga').value) || 0);
        });
        const persen = Math.min(Math.max(parseFloat(diskonEl.value) || 0, 0), 100);
        const diskon = Math.round(sub * persen / 100);
        totalEl.textContent = 'Rp' + rp(sub - diskon);
    }

    daftar.addEventListener('click', function (e) {
        const b = e.target.closest('button');
        if (!b) return;
        const row = b.closest('.item');
        if (b.dataset.d) {
            const inp = row.querySelector('.jml');
            inp.value = Math.max(1, (parseInt(inp.value) || 1) + parseInt(b.dataset.d));
        } else if (b.classList.contains('hapus')) {
            if (daftar.querySelectorAll('.item').length > 1) {
                row.remove();
            } else {
                row.querySelector('.nama').value = '';
                row.querySelector('.jml').value = 1;
                row.querySelector('.harga').value = '';
            }
        }
        hitung();
    });

    document.getElementById('tambah').addEventListener('click', function () {
        tambahBaris().querySelector('.nama').focus();
    });
    document.getElementById('form').addEventListener('input', hitung);

    tambahBaris();
    hitung();
})();
</script>
</body>
</html>