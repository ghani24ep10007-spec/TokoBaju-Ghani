<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'nama_produk' => 'Kaos Oversize Streetwear Ghani Edition',
                'kategori'    => 'Kaos & T-Shirt',
                'harga'       => 125000,
                'stok'        => 25,
                'ukuran'      => 'L',
                'deskripsi'   => 'Bahan katun combed 24s premium, adem, nyaman dipakai seharian dengan sablon discharge.',
                'gambar'      => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'nama_produk' => 'Kemeja Flanel Casual Vintage',
                'kategori'    => 'Kemeja',
                'harga'       => 185000,
                'stok'        => 18,
                'ukuran'      => 'XL',
                'deskripsi'   => 'Kemeja flanel motif kotak klasik, bahan wool blend tebal dan lembut, cocok untuk hangout dan kuliah.',
                'gambar'      => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'nama_produk' => 'Hoodie Zipper Minimalist Charcoal',
                'kategori'    => 'Outerwear',
                'harga'       => 230000,
                'stok'        => 15,
                'ukuran'      => 'All Size',
                'deskripsi'   => 'Hoodie bahan cotton fleece 300gsm dengan resleting YKK premium, saku kangaroo dan tali serut tebal.',
                'gambar'      => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'nama_produk' => 'Celana Chino Slim Fit Dark Khaki',
                'kategori'    => 'Celana',
                'harga'       => 175000,
                'stok'        => 20,
                'ukuran'      => '32',
                'deskripsi'   => 'Celana chino katun twill stretch, potongan modern slim-fit elegan untuk kasual maupun semi-formal.',
                'gambar'      => 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'nama_produk' => 'Kemeja Batik Modern Slimfit Eksklusif',
                'kategori'    => 'Batik Modern',
                'harga'       => 210000,
                'stok'        => 12,
                'ukuran'      => 'L',
                'deskripsi'   => 'Batik cap primisima dengan lapisan furing lembut, motif kontemporer cocok untuk acara resmi.',
                'gambar'      => 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'nama_produk' => 'Jaket Denim Vintage Classic Blue',
                'kategori'    => 'Outerwear',
                'harga'       => 275000,
                'stok'        => 10,
                'ukuran'      => 'XL',
                'deskripsi'   => 'Jaket jeans denim 14oz non-stretch dengan efek bio-wash natural dan kancing tembaga antik.',
                'gambar'      => 'https://images.unsplash.com/photo-1576995853123-5a10305d93c0?w=600&auto=format&fit=crop&q=80',
            ],
        ];

        foreach ($products as $p) {
            Product::create($p);
        }
    }
}
