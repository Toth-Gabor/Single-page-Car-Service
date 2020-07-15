<?php

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

abstract class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    abstract public function run();

    /**
     * Ellenőrzi hogy létezik a kért json file
     * @param string $fileName
     * @return mixed
     * @throws FileNotFoundException
     */
    public function getJson(string $fileName)
    {
        if (File::exists($fileName)){
            $json = File::get($fileName);
            return json_decode($json);
        }
        throw new FileNotFoundException();
    }
}
