<?php

use Illuminate\Database\Seeder;
use App\Models\Idea\Page;
use App\Models\Idea\PageTranslation;

class PageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('pages')->truncate();
        $page = [
            array('id'=>'1' , 'code'=>'about_us', 'parent_id' => null ),
            array('id'=>'2' , 'code'=>'more_about_us' , 'parent_id' => '1' ),
        ];
        Page::insert($page);

        \DB::table('page_translations')->truncate();
        $pageTranslation = [
            ['page_id' => '1', 'locale' => 'en', 'title' => 'About Us' , 'body' => 'lorem ipsum'],
        ];
        PageTranslation::insert($pageTranslation);
    }
}
