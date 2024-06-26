<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Categorie;
use App\Models\Endpoint;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch products from cocacola api';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $endpoints = Endpoint::all();
            foreach ($endpoints as $endpoint) {
                $response = Http::withHeaders([
                    'apikey' => $endpoint->api_key
                ])->get($endpoint->url);
                if ($response->successful()) {
                    $data = $response->json();
                    $products = collect([]);
                    $products = $products->merge($data['products']);
                    for ($i = 0; $i <= $data['paging']['pages']; $i++) {
                        if ($i % 9 == 0) {
                            sleep(65);
                        }

                        $response = Http::withHeaders([
                            'apikey' => $endpoint->api_key
                        ])->get($endpoint->url . "?page=" . $i + 1);
                        if (isset($response->json()['paging'])) {
                            $data = $response->json();
                            $products = $products->merge($data['products']);
                        } else {
                            Log::error("empty data", ['data' => $response->json()]);
                        }
                    }
                    $this->saveProducts($products, $endpoint->wharehouse_name, $endpoint->wharehouse_id);
                } else {
                    Log::error("error sincronizando", ['api_key' => $endpoint->api_key, 'data' => $response->json()]);
                }
            }
        } catch (\Throwable $th) {
            report($th);
        }
    }

    private function saveProducts($products, $warehouseName, $warehouseid)
    {
        foreach ($products as $product) {
            $newProduct = Product::updateOrCreate(
                [
                    'sku' => $product['sku'],
                    'extracted' => Carbon::parse($product['extracted'])->format('Y-m-d'),
                    'warehouse_name' => $warehouseName,
                    'warehouse_id' => $warehouseid,
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
                    'offer_price' => $product['prices']['offerPrice'] ?? 0,
                    'normal_price' => $product['prices']['normalPrice'] ?? null,
                ]
            );
            foreach ($product['competitors'] as $competitor) {
                $newProduct->competitors()->updateOrCreate(
                    [
                        'sku' => $competitor['sku'],
                        'extracted' => Carbon::parse($competitor['extracted'])->format('Y-m-d'),
                        'warehouse_name' => $warehouseName,
                        'warehouse_id' => $warehouseid,
                    ],
                    [
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
                    ]
                );
            }
            if (isset($product['categories']['web']) && is_array($product['categories']['web']) && count($product['categories']['web']) > 0) {
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
}
