<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function fetchProductsFromApi()
    {
        $response = Http::withHeaders([
            'apikey' => '3b5489661f334fda8b811d6c8bfccbd9'
        ])->get("https://services.retailcompass.com/api/pricing/v2/products");
        $data = $response->json();
        $paginator = $data['paging'];
        $products = collect([]);
        $products = $products->merge($data['products']);
        for ($i = 0; $i <= $data['paging']['pages']; $i++) {
            if ($i == 9)
                sleep(65);

            $response = Http::withHeaders([
                'apikey' => '3b5489661f334fda8b811d6c8bfccbd9'
            ])->get("https://services.retailcompass.com/api/pricing/v2/products?page=" . $i + 1);
            if (isset($response->json()['paging'])) {
                Log::error("Log response", ['data' => $response->json()['paging']]);
                $data = $response->json();
                $products = $products->merge($data['products']);
            } else {
                Log::error("empty data", ['data' => $response->json()]);
            }
        }
        $this->saveProducts($products, "VDM", "c105");
        return $products->count();
    }

    private function saveProducts($products, $warehouseName, $warehouseid)
    {
        foreach ($products as $product) {
            $newProduct = Product::firstOrCreate(
                [
                    'sku' => $product['sku'],
                    'extracted' => Carbon::parse($product['extracted']),
                ],
                [
                    'product_id' => $product['productId'],
                    'store_id' => $product['storeId'],
                    'store_name' => $product['storeName'],
                    'name' => $product['name'],
                    'brand' => $product['brand'] ?? null,
                    'model' => $product['model'] ?? null,
                    'url' => $product['url'],
                    'image_url' => $product['imageUrl'],
                    'status' => $product['status'],
                    'created_cocacola' => Carbon::parse($product['created']),
                    'updated_cocacola' => Carbon::parse($product['updated']),
                    'lowest_price' => $product['prices']['lowest'],
                    'offer_price' => $product['prices']['offerPrice'],
                    'normal_price' => $product['prices']['normalPrice'] ?? null,
                    'warehouse_name' => $warehouseName,
                    'warehouse_id' => $warehouseid,
                ]
            );

            foreach ($product['competitors'] as $competitor) {
                $newProduct->competitors()->create(
                    [
                        'sku' => $competitor['sku'],
                        'extracted' => Carbon::parse($competitor['extracted']),
                        'retail_product_id' => $competitor['productId'],
                        'store_id' => $competitor['storeId'],
                        'store_name' => $competitor['storeName'],
                        'name' => $competitor['name'],
                        'brand' => $competitor['brand'] ?? null,
                        'model' => $competitor['model'] ?? null,
                        'url' => $competitor['url'],
                        'image_url' => $competitor['imageUrl'],
                        'status' => $competitor['status'],
                        'created_retailer' => Carbon::parse($competitor['created']),
                        'updated_retailer' => Carbon::parse($competitor['updated']),
                        'lowest_price' => $competitor['prices']['lowest'],
                        'offer_price' => $competitor['prices']['offerPrice'] ?? null,
                        'normal_price' => $competitor['prices']['normalPrice'] ?? null,
                        'match_status' => $competitor['matchStatus'],
                        'warehouse_name' => $warehouseName,
                        'warehouse_id' => $warehouseid,
                    ]
                );
            }

            foreach ($product['categories']['web'] as $categorie) {
                $newCategorie = Categorie::firstOrCreate(
                    [
                        'cocacola_id' => $categorie['id'],
                    ],
                    [
                        'category_id_path' => $categorie['categoryIdPath'],
                        'full_path' => $categorie['fullPath'],
                    ]
                );

                if (!$newProduct->categories()->where('categories.id', $newCategorie->id)->exists())
                    $newProduct->categories()->attach($newCategorie);
            }
        }
    }
}
