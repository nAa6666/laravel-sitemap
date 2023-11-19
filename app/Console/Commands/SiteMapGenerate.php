<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;
use Symfony\Component\Console\Output\OutputInterface;

class SiteMapGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'site-map-generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Start generating sitemap...');
        $this->start();
        $this->info('Sitemap generated successfully!');
    }

    public function start()
    {
        $categories = Category::all();
        $products = Product::all();

        SitemapIndex::create()->add('/categories.xml')->add('/products.xml')
            ->writeToFile(public_path('sitemap.xml'));

        //Generate sitemap for categories
        $sitemap = Sitemap::create();
        foreach ($categories as $category) {
            $sitemap->add(Url::create(route('category', $category->id))
                ->setLastModificationDate($category->updated_at)
                ->setPriority(0.5));
        }
        $sitemap->writeToFile(public_path('categories.xml'));
        $this->info('Categories count: '.$categories->count());

        //Generate sitemap for products
        $sitemap = Sitemap::create();
        foreach ($products as $product) {
            $sitemap->add(Url::create(route('product', [$product->category_id, $product->id]))
                ->setLastModificationDate($product->updated_at)
                ->setPriority(0.5));
        }
        $sitemap->writeToFile(public_path('products.xml'));
        $this->info('Products count: '.$products->count());
    }
}
