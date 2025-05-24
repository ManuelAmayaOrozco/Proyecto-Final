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
                'registered_by' => 1,
                'name' => 'Abeja Europea',
                'scientificName' => 'Apis mellifera',
                'family' => 'Apidae',
                'diet' => 'Herbívoro',
                'description' => '{"time":1748099868839,"blocks":[{"id":"YpjYsdqgAJ","type":"header","data":{"text":"<b>Abeja Europea</b>","level":2}},{"id":"8SKp8HNIDR","type":"paragraph","data":{"text":"La&nbsp;<b>abeja europea</b>&nbsp;(<i><b>Apis mellifera</b></i>), también conocida como&nbsp;<b>abeja doméstica</b>&nbsp;o&nbsp;<b>abeja melífera</b>, es una&nbsp;<a href=\"https://es.wikipedia.org/wiki/Especie\">especie</a>&nbsp;de&nbsp;<a href=\"https://es.wikipedia.org/wiki/Himen%C3%B3ptero\">himenóptero</a>&nbsp;<a href=\"https://es.wikipedia.org/wiki/Ap%C3%B3crito\">apócrito</a>&nbsp;de la&nbsp;<a href=\"https://es.wikipedia.org/wiki/Familia_(biolog%C3%ADa)\">familia</a>&nbsp;<a href=\"https://es.wikipedia.org/wiki/Apidae\">Apidae</a>. Es la especie de&nbsp;<a href=\"https://es.wikipedia.org/wiki/Abeja\">abeja</a>&nbsp;con mayor distribución en el mundo. Originaria de&nbsp;<a href=\"https://es.wikipedia.org/wiki/Europa\">Europa</a>,&nbsp;<a href=\"https://es.wikipedia.org/wiki/%C3%81frica\">África</a>&nbsp;y parte de&nbsp;<a href=\"https://es.wikipedia.org/wiki/Asia\">Asia</a>, fue introducida en&nbsp;<a href=\"https://es.wikipedia.org/wiki/Am%C3%A9rica\">América</a>&nbsp;y&nbsp;<a href=\"https://es.wikipedia.org/wiki/Ocean%C3%ADa\">Oceanía</a>. La abeja fue clasificada por&nbsp;<a href=\"https://es.wikipedia.org/wiki/Carlos_Linneo\">Carlos Linneo</a>&nbsp;en 1758. A partir de entonces numerosos&nbsp;<a href=\"https://es.wikipedia.org/wiki/Tax%C3%B3nomo\">taxónomos</a>&nbsp;describieron variedades geográficas o&nbsp;<a href=\"https://es.wikipedia.org/wiki/Subespecie\">subespecies</a>&nbsp;que, en la actualidad, superan las treinta razas. Actualmente la población de abejas en algunos países se halla en franco retroceso sin que se conozca de manera clara las causas, que bien podría ser un cúmulo de diversos factores.​ Son importantes en la&nbsp;<a href=\"https://es.wikipedia.org/wiki/Polinizaci%C3%B3n\">polinización</a>&nbsp;de un número de cosechas."}},{"id":"i-oieGISOi","type":"paragraph","data":{"text":"Cuando un apicultor se refiere a sus&nbsp;<a href=\"https://es.wikipedia.org/wiki/Colmena\">colmenas</a>&nbsp;en forma colectiva lo hace desde un concepto intuitivo de colectividad, al hablar de los componentes de un apiario, habla lógicamente del conocimiento de la biología de las abejas, cuya naturaleza social hace que el individuo, en sí mismo, carezca de valor en favor de la colectividad de las abejas. Por todo ello se dice que la colmena es un&nbsp;<a href=\"https://es.wikipedia.org/wiki/Superorganismo\">superorganismo</a>. Este superorganismo se comporta con&nbsp;<a href=\"https://es.wiktionary.org/wiki/es:sinergia\">sinergia</a>&nbsp;que es el efecto producido por la interacción entre los componentes de un&nbsp;<a href=\"https://es.wikipedia.org/wiki/Sistema\">sistema</a>&nbsp;que hace que el todo sea más que la suma de las partes individuales. A esta sinergia de conjunto demostrada por Farrar matemáticamente se le denomina&nbsp;<a href=\"https://es.wikipedia.org/wiki/Regla_de_Farrar\">regla de Farrar</a>.&nbsp;&nbsp;"}}],"version":"2.31.0-rc.7"}',
                'n_spotted' => 100000000,
                'maxSize' => 6.35,
                'protectedSpecies' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'registered_by' => 1,
                'name' => 'Hormiga Carpintera',
                'scientificName' => 'Camponotus Mus',
                'family' => 'Formicidae',
                'diet' => 'Omnívoro',
                'description' => '{"time":1748100317092,"blocks":[{"id":"tvEqfiqbrA","type":"header","data":{"text":"<b>Hormiga Carpintera</b>","level":2}},{"id":"vrUO0XoldY","type":"paragraph","data":{"text":"<i><b>Camponotus mus</b></i>&nbsp;(conocida comúnmente como hormiga carpintera) es una especie de&nbsp;<a href=\"https://es.wikipedia.org/wiki/Formicidae\">hormiga</a>&nbsp;del género&nbsp;<i><a href=\"https://es.wikipedia.org/wiki/Camponotus\">Camponotus</a></i>. Es de gran tamaño; una de las más grandes de&nbsp;<a href=\"https://es.wikipedia.org/wiki/Am%C3%A9rica\">América</a>. Se pueden encontrar en&nbsp;<a href=\"https://es.wikipedia.org/wiki/Am%C3%A9rica_Central\">Centro</a>&nbsp;y&nbsp;<a href=\"https://es.wikipedia.org/wiki/Am%C3%A9rica_del_Sur\">Sudamérica</a>, incluyendo las&nbsp;<a href=\"https://es.wikipedia.org/wiki/Antillas\">Antillas</a>. Habitan en árboles leñosos aprovechando las cámaras dejadas por&nbsp;<a href=\"https://es.wikipedia.org/wiki/Insecta\">insectos</a>&nbsp;estacionales. Viven en condiciones de poca humedad, por lo que se adaptan bien a regiones áridas y con escasas lluvias.&nbsp;&nbsp;"}},{"id":"AtOQvp2v4i","type":"paragraph","data":{"text":"Su tamaño ronda desde los 6&nbsp;mm en las obreras más pequeñas hasta 10,5&nbsp;mm en las más grandes, con individuos alados de hasta 12-13&nbsp;mm. El cuerpo es de color negro mate y cuenta con unos pequeños pelos de color amarillento por todo el cuerpo, pero sobre todo en el abdomen."}},{"id":"MyBwtLe7eu","type":"paragraph","data":{"text":"Las obreras son&nbsp;<a href=\"https://es.wikipedia.org/wiki/Polimorfismo_(biolog%C3%ADa)\">polimórficas;</a>&nbsp;es decir, poseen variaciones de tamaño entre ellas, contando con soldados y obreras propiamente dichas. Al contrario que en otras especies, la mayor diferencia es el tamaño y no la forma del cuerpo. Las&nbsp;<a href=\"https://es.wikipedia.org/wiki/Antena_(artr%C3%B3podos)\">antenas</a>&nbsp;poseen 12 segmentos con&nbsp;<a href=\"https://es.wikipedia.org/wiki/Pedicelo_(insectos)\">pedicelo</a>&nbsp;muy corto y plano.&nbsp;&nbsp;"}}],"version":"2.31.0-rc.7"}',
                'n_spotted' => 200000000,
                'maxSize' => 0.8,
                'protectedSpecies' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'registered_by' => 1,
                'name' => 'Mantis Religiosa',
                'scientificName' => 'Mantis Religiosa',
                'family' => 'Mantidae',
                'diet' => 'Carnívoro',
                'description' => '{"time":1748100785172,"blocks":[{"id":"szeb96yqiY","type":"header","data":{"text":"<b>Mantis Religiosa</b>","level":2}},{"id":"I_GIvy7atZ","type":"paragraph","data":{"text":"<i><b>Mantis religiosa</b></i>&nbsp;es el nombre científico de una especie de&nbsp;<a href=\"https://es.wikipedia.org/wiki/Insecto\">insecto</a>&nbsp;<a href=\"https://es.wikipedia.org/wiki/Mantodeo\">mantodeo</a>&nbsp;de la&nbsp;<a href=\"https://es.wikipedia.org/wiki/Familia_(biolog%C3%ADa)\">familia</a>&nbsp;<a href=\"https://es.wikipedia.org/wiki/Mantidae\">Mantidae</a>&nbsp;comúnmente llamado&nbsp;<b>santateresa</b>,&nbsp;<b>silbata</b>,&nbsp;<b>mamboretá</b>,&nbsp;<b>campamocha</b>,&nbsp;<b>tatadiós, cerbatana</b>&nbsp;o simplemente&nbsp;<b>mantis</b>. Tiene una amplia distribución geográfica en todo el&nbsp;<a href=\"https://es.wikipedia.org/wiki/Viejo_Mundo\">Viejo Mundo</a>&nbsp;(<a href=\"https://es.wikipedia.org/wiki/Eurasia\">Eurasia</a>&nbsp;y&nbsp;<a href=\"https://es.wikipedia.org/wiki/%C3%81frica\">África</a>), con numerosas&nbsp;<a href=\"https://es.wikipedia.org/wiki/Subespecies\">subespecies</a>&nbsp;según las regiones. Se introdujo en&nbsp;<a href=\"https://es.wikipedia.org/wiki/Norteam%C3%A9rica\">Norteamérica</a>&nbsp;en 1899, en un barco con plantones, y a pesar de ser una&nbsp;<a href=\"https://es.wikipedia.org/wiki/Especie_introducida\">especie introducida</a>, es el insecto oficial del estado&nbsp;<a href=\"https://es.wikipedia.org/wiki/Estadounidense\">estadounidense</a>&nbsp;de&nbsp;<a href=\"https://es.wikipedia.org/wiki/Connecticut\">Connecticut</a>."}},{"id":"ESYhZy5mz7","type":"paragraph","data":{"text":"Es un insecto de tamaño mediano de aproximadamente 6 a 7&nbsp;cm, con un&nbsp;<a href=\"https://es.wikipedia.org/wiki/T%C3%B3rax_(artr%C3%B3podos)\">tórax</a>&nbsp;largo y unas antenas delgadas. Tiene dos grandes&nbsp;<a href=\"https://es.wikipedia.org/wiki/Ojo_compuesto\">ojos compuestos</a>&nbsp;y tres&nbsp;<a href=\"https://es.wikipedia.org/wiki/Ojo_simple\">ojos simples</a>&nbsp;pequeños entre ellos. La&nbsp;<a href=\"https://es.wikipedia.org/wiki/Cabeza_(artr%C3%B3podos)\">cabeza</a>&nbsp;puede girar hasta 180°. Sus&nbsp;<a href=\"https://es.wikipedia.org/wiki/Pata_(artr%C3%B3podos)\">patas</a>&nbsp;delanteras, que mantiene recogidas delante de la cabeza, están provistas de espinas para sujetar a sus presas.&nbsp;&nbsp;"}},{"id":"HJGA5SK0e1","type":"paragraph","data":{"text":"Son animales solitarios excepto en la época de reproducción, cuando macho y hembra se buscan para aparearse. Cuando hay más de un macho cerca de una hembra, éstos se pelean y solo uno se aparea. Las hembras son más grandes que los machos. En la mayoría de ocasiones, durante o tras el apareamiento, la hembra se come al macho, solo en un 80% de los casos."}},{"id":"iLNNRG1-Vw","type":"paragraph","data":{"text":"Pueden ser de color verde o pardo con distintos matices. El color del adulto lo determina el medio en el que habita durante su última muda (por ejemplo, amarillo, si se trata de paja seca, o verde, si es hierba fresca)."}},{"id":"eWPw8lKeHb","type":"paragraph","data":{"text":"Es el único insecto conocido que cuenta con un único oído que está localizado en el&nbsp;<a href=\"https://es.wikipedia.org/wiki/T%C3%B3rax_(artr%C3%B3podos)\">tórax</a>.&nbsp;&nbsp;"}}],"version":"2.31.0-rc.7"}',
                'n_spotted' => 500000,
                'maxSize' => 15,
                'protectedSpecies' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'registered_by' => 1,
                'name' => 'Cochinilla',
                'scientificName' => 'Armadillidium Vulgare',
                'family' => 'Armadillidiidae',
                'diet' => 'Detritívoro',
                'description' => '{"time":1748102538969,"blocks":[{"id":"bCvNAzwbDd","type":"header","data":{"text":"<b>Cochinilla</b>","level":2}},{"id":"SKu6pz63ro","type":"paragraph","data":{"text":"<i><b>Armadillidium vulgare</b></i>, conocido popularmente como&nbsp;<b>bicho bola</b>,&nbsp;<b>cochinilla</b>&nbsp;o&nbsp;<b>chanchito</b>, es una&nbsp;<a href=\"https://es.wikipedia.org/wiki/Especie\">especie</a>&nbsp;de&nbsp;<a href=\"https://es.wikipedia.org/wiki/Crust%C3%A1ceo\">crustáceo</a>&nbsp;terrestre de distribución global&nbsp;originaria del área del&nbsp;<a href=\"https://es.wikipedia.org/wiki/Mar_Mediterr%C3%A1neo\">Mar Mediterráneo</a>, probablemente de la parte oriental.&nbsp;"}},{"id":"pwQ_c5KHSt","type":"paragraph","data":{"text":"Se conocen muchos aspectos de su biología debido a la gran cantidad de estudios sobre la misma.​ Pueden atacar a&nbsp;<a href=\"https://es.wikipedia.org/wiki/Orquid%C3%A1ceas\">orquidáceas</a>, royendo raíces y brotes de&nbsp;<a href=\"https://es.wikipedia.org/wiki/Hortaliza\">hortalizas</a>&nbsp;y causando pérdidas importantes en la agricultura. Realizan desoves periódicos, con un incremento progresivo de la cantidad de huevos por puesta en la hembra adulta."}}],"version":"2.31.0-rc.7"}',
                'n_spotted' => 100000,
                'maxSize' => 13.6,
                'protectedSpecies' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}