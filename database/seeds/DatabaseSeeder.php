<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();


        $this->call(UsersTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(PassportClientSeeder::class);
        $this->call(PlaylistsTableSeeder::class);

        Schema::enableForeignKeyConstraints();

        Artisan::call('aparat:clear');
        $this->command->info('Clear all aparat temporary files');
    }
}
