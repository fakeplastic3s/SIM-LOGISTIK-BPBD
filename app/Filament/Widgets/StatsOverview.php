<?php

namespace App\Filament\Widgets;

use App\Models\Barang;
use App\Models\StokBarang;
use App\Models\Item;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;


class StatsOverview extends BaseWidget
{
    // protected static ?int $sort = -2;

    protected function getStats(): array
    {
        $totalBarang = StokBarang::where('stok', '>', 0)->count();
        // $totalBarangNonKedaluwarsa = Item::where('category', 'Barang Non-Kedaluwarsa')->count();
        $totalKategoriSandang = StokBarang::where('kategori', 'Sandang')->where('stok', '>', 0)->count();
        $totalKategoriPangan = StokBarang::where('kategori', 'Pangan')->where('stok', '>', 0)->count();
        $hampirKedaluwarsa = StokBarang::where('kategori', 'Barang Kedaluwarsa')->where('tanggal_exp', '<=', now()->addDays(30))->count();
        return [
            Stat::make('Total Barang', $totalBarang),
            // Stat::make('Barang Non-Kedaluwarsa', $totalBarangNonKedaluwarsa),
            Stat::make('Kategori Sandang', $totalKategoriSandang)
            // ->description($hampirKedaluwarsa . ' Barang Hampir Kedaluwarsa ')
            // ->descriptionIcon('heroicon-m-exclamation-triangle')
            // ->color('warning')
            ,
            Stat::make('Kategori Pangan', $totalKategoriPangan)
            // ->description($hampirKedaluwarsa . ' Barang Hampir Kedaluwarsa ')
            // ->descriptionIcon('heroicon-m-exclamation-triangle')
            // ->color('warning')
            ,
        ];
    }
}
