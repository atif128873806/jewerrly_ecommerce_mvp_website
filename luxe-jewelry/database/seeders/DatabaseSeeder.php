<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Material;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /** Lifestyle / detail shots reused as secondary gallery images. */
    private array $lifestylePool = ['p001', 'p009', 'p028', 'p039', 'p050', 'p016', 'p021'];

    public function run(): void
    {
        $categories = $this->seedCategories();
        $collections = $this->seedCollections();
        $this->seedProducts($categories, $collections);
        $this->seedHomepageReviews();
    }

    /* ============================================================ */

    private function seedCategories(): array
    {
        $data = [
            ['name' => 'Necklaces', 'image' => 'p030', 'desc' => 'Pendants, lockets and layering chains made to be worn close.'],
            ['name' => 'Rings', 'image' => 'p015', 'desc' => 'Signets, stackers and statement stones in gold and silver.'],
            ['name' => 'Earrings', 'image' => 'p003', 'desc' => 'Studs, drops and shells — light enough for every day.'],
            ['name' => 'Bracelets', 'image' => 'p047', 'desc' => 'Bangles, chains and cords designed to stack and layer.'],
        ];

        $out = [];
        foreach ($data as $i => $c) {
            $out[$c['name']] = Category::create([
                'name' => $c['name'],
                'slug' => Str::slug($c['name']),
                'image' => "img/products/{$c['image']}.jpg",
                'description' => $c['desc'],
                'position' => $i,
            ]);
        }

        return $out;
    }

    private function seedCollections(): array
    {
        $data = [
            ['name' => 'Allure', 'tagline' => 'Stones that hold a little light', 'img' => 'p019'],
            ['name' => 'Rivière', 'tagline' => 'Classic lines, made to layer', 'img' => 'p088'],
            ['name' => 'Glam Rock', 'tagline' => 'Bold gold with attitude', 'img' => 'p008'],
            ['name' => 'Love', 'tagline' => 'Worn close to the heart', 'img' => 'p046'],
            ['name' => 'Amber', 'tagline' => 'Warmth, captured in gold', 'img' => 'p003'],
            ['name' => 'Greta', 'tagline' => 'Quiet nature, finely cast', 'img' => 'p035'],
        ];

        $out = [];
        foreach ($data as $i => $c) {
            $out[$c['name']] = Collection::create([
                'name' => $c['name'],
                'slug' => Str::slug($c['name']),
                'tagline' => $c['tagline'],
                'description' => "The {$c['name']} collection — {$c['tagline']}. Each piece is handcrafted in Mallorca and designed to be layered and lived in.",
                'feature_image' => "img/products/{$c['img']}.jpg",
                'is_featured' => $i < 3,
                'position' => $i,
            ]);
        }

        return $out;
    }

    /* ============================================================ */

    private function seedProducts(array $cats, array $cols): void
    {
        // [name, category, primaryImg, priceEUR, comparePriceEUR|null, collection|null, symbol|null, flags]
        // flags: f=featured, n=new, b=bestseller
        $products = [
            // ---- Necklaces ----
            ['Petit Cœur Pendant', 'Necklaces', 'p030', 102, null, 'Love', 'Love', 'fb'],
            ['Soleil Medallion', 'Necklaces', 'p018', 128, null, 'Allure', 'Spirituality', 'f'],
            ['Rayon Sun Necklace', 'Necklaces', 'p013', 119, null, 'Allure', 'Strength', 'f'],
            ['Feuille Leaf Pendant', 'Necklaces', 'p035', 96, 132, 'Greta', 'Balance', 'fb'],
            ['Cœur Blanc Locket', 'Necklaces', 'p037', 144, null, 'Love', 'Love', 'f'],
            ['Maille Chain Necklace', 'Necklaces', 'p042', 168, null, 'Rivière', null, 'fb'],
            ['Heart of Gold Pendant', 'Necklaces', 'p046', 112, null, 'Love', 'Love', 'fb'],
            ['Lune Drop Necklace', 'Necklaces', 'p052', 134, null, 'Allure', 'Protection', 'n'],
            ['Étoile Charm Pendant', 'Necklaces', 'p056', 98, null, 'Greta', 'Luck', 'n'],
            ['Cascade Layered Set', 'Necklaces', 'p061', 212, null, 'Rivière', null, 'n'],
            ['Perle Solitaire Necklace', 'Necklaces', 'p064', 156, null, 'Amber', null, 'n'],
            ['Trois Pendants Necklace', 'Necklaces', 'p070', 178, null, 'Love', 'Love', 'n'],
            ['Médaille Coin Necklace', 'Necklaces', 'p074', 142, null, 'Allure', 'Protection', 'n'],
            ['Charme Multi Necklace', 'Necklaces', 'p091', 189, null, 'Love', 'Protection', 'n'],
            ['Fleur Pendant Necklace', 'Necklaces', 'p095', 124, 156, 'Greta', 'Balance', ''],

            // ---- Rings ----
            ['Dôme Signet Ring', 'Rings', 'p015', 124, null, 'Glam Rock', null, 'fb'],
            ['Émeraude Stone Ring', 'Rings', 'p005', 159, null, 'Allure', 'Healing', 'f'],
            ['Verde Solitaire Ring', 'Rings', 'p048', 168, null, 'Allure', 'Healing', 'f'],
            ['Duo Émeraude Rings', 'Rings', 'p032', 184, null, 'Allure', null, ''],
            ['Chunky Band Ring', 'Rings', 'p033', 138, null, 'Glam Rock', null, 'b'],
            ['Stacked Gold Rings', 'Rings', 'p038', 156, null, 'Glam Rock', null, 'f'],
            ['Tresse Statement Ring', 'Rings', 'p024', 132, null, 'Glam Rock', 'Strength', ''],
            ['Étoile Open Ring', 'Rings', 'p012', 108, null, 'Greta', 'Luck', ''],
            ['Papillon Pearl Ring', 'Rings', 'p034', 116, 148, 'Greta', 'Balance', 'b'],
            ['Bandeau Wide Ring', 'Rings', 'p016', 98, null, 'Rivière', null, ''],
            ['Coeur Ouvert Ring', 'Rings', 'p021', 92, null, 'Love', 'Love', 'n'],
            ['Étoile de Mer Ring', 'Rings', 'p043', 104, null, 'Amber', 'Protection', 'n'],

            // ---- Earrings ----
            ['Coquille Shell Earrings', 'Earrings', 'p003', 138, null, 'Amber', null, 'fb'],
            ['Papillon Butterfly Studs', 'Earrings', 'p011', 89, null, 'Greta', 'Balance', 'f'],
            ['Coquille Gold Drops', 'Earrings', 'p004', 124, null, 'Amber', null, 'b'],
            ['Perle Shell Studs', 'Earrings', 'p026', 96, null, 'Amber', null, ''],
            ['Goutte Drop Earrings', 'Earrings', 'p036', 112, 142, 'Allure', null, 'n'],
            ['Fleur Gold Studs', 'Earrings', 'p040', 84, null, 'Greta', 'Balance', 'n'],
            ['Fleur Statement Earrings', 'Earrings', 'p041', 128, null, 'Greta', null, ''],

            // ---- Bracelets ----
            ['Empilable Bangle Set', 'Bracelets', 'p047', 186, null, 'Rivière', null, 'fb'],
            ['Fine Chain Bracelet', 'Bracelets', 'p022', 98, null, 'Rivière', null, 'b'],
            ['Maille Wrist Chain', 'Bracelets', 'p027', 124, null, 'Rivière', null, ''],
            ['Double Cord Bracelet', 'Bracelets', 'p029', 142, null, 'Rivière', 'Protection', 'f'],
            ['Pierre Bleue Bracelet', 'Bracelets', 'p044', 116, null, 'Allure', 'Healing', ''],
            ['Velours Bangle', 'Bracelets', 'p082', 168, null, 'Rivière', null, 'n'],
            ['Rivière Tennis Bracelet', 'Bracelets', 'p088', 248, null, 'Rivière', null, 'nf'],
            ['Stack Chain Bracelet', 'Bracelets', 'p079', 132, 168, 'Glam Rock', null, 'n'],
            ['Goutte Pearl Bracelet', 'Bracelets', 'p066', 116, null, 'Amber', null, 'n'],
            ['Charme Wrist Bracelet', 'Bracelets', 'p049', 108, null, 'Love', 'Love', ''],
        ];

        $skuN = 1000;
        foreach ($products as $pos => $row) {
            [$name, $catName, $img, $price, $compare, $colName, $symbol, $flags] = $row;

            $material = $this->guessMaterial($name);

            $product = Product::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'sku' => 'LX-' . (++$skuN),
                'description' => $this->description($name, $material, $catName),
                'price' => $price * 100,
                'compare_price' => $compare ? $compare * 100 : null,
                'category_id' => $cats[$catName]->id,
                'collection_id' => $colName ? $cols[$colName]->id : null,
                'material' => $material->value,
                'symbol' => $symbol,
                'is_featured' => str_contains($flags, 'f'),
                'is_new' => str_contains($flags, 'n'),
                'is_bestseller' => str_contains($flags, 'b'),
                'in_stock' => true,
                'stock_qty' => random_int(8, 60),
                'rating' => [4.6, 4.7, 4.8, 4.9, 5.0][array_rand([4.6, 4.7, 4.8, 4.9, 5.0])],
                'reviews_count' => random_int(6, 84),
                'position' => $pos,
            ]);

            $this->attachImages($product, $img, $pos);
            $this->attachReviews($product);
        }
    }

    private function attachImages(Product $product, string $primary, int $pos): void
    {
        ProductImage::create([
            'product_id' => $product->id,
            'path' => "img/products/{$primary}.jpg",
            'alt' => $product->name,
            'is_primary' => true,
            'position' => 0,
        ]);

        // Two lifestyle/detail gallery shots (deterministic rotation through the pool)
        $a = $this->lifestylePool[$pos % count($this->lifestylePool)];
        $b = $this->lifestylePool[($pos + 3) % count($this->lifestylePool)];
        $p = 1;
        foreach ([$a, $b] as $img) {
            if ($img === $primary) {
                continue;
            }
            ProductImage::create([
                'product_id' => $product->id,
                'path' => "img/products/{$img}.jpg",
                'alt' => "{$product->name} — styled",
                'is_primary' => false,
                'position' => $p++,
            ]);
        }
    }

    private function attachReviews(Product $product): void
    {
        $samples = [
            ['Sofia R.', 5, 'Obsessed', 'Even more beautiful in person. Hasn\'t tarnished at all and I never take it off.'],
            ['Camille D.', 5, 'Incredible quality', 'Feels weighty and luxurious for the price. The finish is flawless.'],
            ['Lucía M.', 4, 'So elegant', 'Catches the light perfectly. Get compliments every time I wear it.'],
            ['Emma T.', 5, 'Gift-worthy', 'Packaging felt like a real gift. Looks far more expensive than it was.'],
        ];

        $count = random_int(1, 3);
        foreach (array_slice($samples, 0, $count) as $r) {
            Review::create([
                'product_id' => $product->id,
                'author_name' => $r[0],
                'rating' => $r[1],
                'title' => $r[2],
                'body' => $r[3],
                'is_verified' => true,
                'is_featured' => false,
            ]);
        }
    }

    private function seedHomepageReviews(): void
    {
        $featured = [
            ['Sofia R.', 'Heart of Gold Pendant', 5, 'Even more beautiful in person. The gold tone is rich and it hasn\'t tarnished at all. I never take it off.'],
            ['Camille D.', 'Rivière Tennis Bracelet', 5, 'The quality is incredible for the price. Feels weighty and luxurious, and the clasp is secure. Obsessed.'],
            ['Lucía M.', 'Émeraude Stone Ring', 5, 'The stone catches the light perfectly. I get compliments every single time I wear it. Shipping was fast too.'],
            ['Emma T.', 'Empilable Bangle Set', 5, 'Perfect for stacking. They look like they cost three times as much. Packaging felt like a real gift.'],
            ['Noor A.', 'Coquille Shell Earrings', 5, 'Lightweight and so elegant. They go with everything from jeans to evening wear. Will be ordering more.'],
        ];

        foreach ($featured as $r) {
            Review::create([
                'product_id' => Product::where('name', $r[1])->value('id'),
                'author_name' => $r[0],
                'rating' => $r[2],
                'title' => null,
                'body' => $r[3],
                'is_verified' => true,
                'is_featured' => true,
            ]);
        }
    }

    /* ============================================================ */

    private function guessMaterial(string $name): Material
    {
        $n = Str::lower($name);
        return match (true) {
            str_contains($n, 'pearl') || str_contains($n, 'perle') => Material::Pearl,
            str_contains($n, 'silver') || str_contains($n, 'argent') => Material::Silver,
            str_contains($n, 'gold') && (str_contains($n, '24') || str_contains($n, 'solid')) => Material::Gold,
            default => Material::GoldPlated,
        };
    }

    private function description(string $name, Material $material, string $category): string
    {
        $type = Str::lower(Str::singular($category));

        return "The {$name} is a handcrafted {$type} made in our Mallorca atelier in {$material->label()}. "
            . 'Designed to be layered and worn every day, it resists tarnish and keeps its shine over time. '
            . 'Each piece arrives in signature gift packaging, ready to give — or to keep.';
    }
}
