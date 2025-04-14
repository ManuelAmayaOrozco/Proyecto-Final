<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InsectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('insects')->insert([
            [
                'registered_by' => 4,
                'name' => 'Abeja Europea',
                'scientificName' => 'Apis mellifera',
                'family' => 'Apidae',
                'diet' => 'Herbívoro',
                'description' => 'La abeja europea (Apis mellifera), también conocida como abeja doméstica o abeja melífera, es una especie de himenóptero apócrito de la familia Apidae. Es la especie de abeja con mayor distribución en el mundo. Originaria de Europa, África y parte de Asia, fue introducida en América y Oceanía. La abeja fue clasificada por Carlos Linneo en 1758. A partir de entonces numerosos taxónomos describieron variedades geográficas o subespecies que, en la actualidad, superan las treinta razas. Actualmente la población de abejas en algunos países se halla en franco retroceso sin que se conozca de manera clara las causas, que bien podría ser un cúmulo de diversos factores. Son importantes en la polinización de un número de cosechas.',
                'n_spotted' => 100000000,
                'maxSize' => 6.35,
                'protectedSpecies' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'registered_by' => 4,
                'name' => 'Hormiga Carpintera',
                'scientificName' => 'Camponotus Mus',
                'family' => 'Formicidae',
                'diet' => 'Omnívoro',
                'description' => 'Las hormigas carpinteras u "hormigas madereras" son especies de Camponotus que se caracterizan por hacer su nido dentro de la madera. Por eso muchas veces pueden ser confundidas con termitas. No comen madera, solo la utilizan para formar su nido, excavando más y más galerías a medida que crece la colonia. Suelen ser un problema económico por deteriorar la madera de los cercos y de las casas.',
                'n_spotted' => 200000000,
                'maxSize' => 0.8,
                'protectedSpecies' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'registered_by' => 4,
                'name' => 'Mantis Religiosa',
                'scientificName' => 'Mantis Religiosa',
                'family' => 'Mantidae',
                'diet' => 'Carnívoro',
                'description' => 'Mantis religiosa es el nombre científico de una especie de insecto mantodeo de la familia Mantidae comúnmente llamado santateresa, silbata, mamboretá, campamocha, tatadiós, cerbatana o simplemente mantis. Tiene una amplia distribución geográfica en todo el Viejo Mundo (Eurasia y África), con numerosas subespecies según las regiones. Se introdujo en Norteamérica en 1899, en un barco con plantones, y a pesar de ser una especie introducida, es el insecto oficial del estado estadounidense de Connecticut.',
                'n_spotted' => 500000,
                'maxSize' => 15,
                'protectedSpecies' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}