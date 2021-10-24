<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rundown_rows;
use App\Models\Rundown_meta_rows;

class RundownRowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $colors = ['930000', 'e05500', 'da8f00', '897800', '39000d', '004334', '003a48', '007792', '47295e'];
        $storys = [ 
            'How Will Falun Be In The Future.', 
            '5 Stereotypes About Falun That Aren\'t Always True.', 
            'You Will Never Believe These Bizarre Truth Of Falun.', 
            'These Local Practices In Falun Are So Bizarre That They Will Make Your Jaw Drop!',
            '10 Secrets About Falun That Nobody Will Tell You.',
            '7 Factors That Affect Falun\'s Longevity.',
            'Understanding The Background Of Falun.',
            '7 Things You Should Know About Falun.',
            'Seven Clarifications On Falun.',
        ];
        $names = ['Brayden Bowler', 'Konrad Melendez', 'Maksim Wagstaff', 'Marion Woodward', 'Nansi Lawson', 'Shahid Squires', 'Oliwier Austin', 'Tania Ayala', 'Jaspal Prosser'];
        $cues = [
            'It\'ll be a big occasion.',
            'That would be a really big surprise, wouldn\'t it?',
            'Taking chances can be risky.',
            'Your house is big.',
            'Have you opened the door?',
            'We see them every week.',
            'We have a big job ahead of us.',
            'It\'s not a big dark secret.',
            'The Earth is spherical.',
        ];
        $types = ['MIXER', 'VB'];
        $audio = ['LIVE', 'TAPE', 'TAPE+LIVE'];
        
        foreach ($colors as $key => $value){
            $type = $types[random_int(0,1)];
            ($type == 'MIXER') ? $source = 'CAM'.random_int(1,10) : $source = strtok($storys[$key], ' ');
            ($key == 0) ? $position = NULL : $position = $key ;
            $array = [
                'rundown_id'        => 36,
                'before_in_table'   => $position,
                'story'             => $storys[$key],
                'color'             => $value,
                'talent'            => $names[$key],
                'cue'               => $cues[$key],
                'type'              => $type,
                'source'            => $source,
                'audio'             => $audio[random_int(0,2)],
                'duration'          => random_int(1,180)
            ];
            Rundown_rows::create( $array );
        }


        $metaTypes = ['AUDIO', 'GFX', 'KEY', 'BG'];
        $bgTypes = ['SCREEN', 'MONITOR', 'GREEN-SCREEN'];
        $i = 0;
        while ( $i< 50 ){
            $theType = $metaTypes[random_int(0, count($metaTypes)-1)];
            switch ($theType){
                case 'AUDIO' : 
                    $theTitle = 'AUDIO SCENE SWITCH';
                    $theSource = 'scene: '.random_int(1, 10);
                break;
                case 'GFX' :
                    $theTitle = $names[random_int(0, count($names)-1)].' LOWER THIRD';
                    $theSource = 'NEWS_LOWER_THIRD04';
                break;
                case 'KEY' :
                    $theTitle = 'PIP KEY 0'.random_int(1, 8);
                    $theSource = 'KEY-'.random_int(1, 4);
                break;
                case 'BG' :
                    $theTitle = $bgTypes[random_int(0, count($bgTypes)-1)].' BACKGROUND';
                    strtok(str_shuffle($storys[random_int(0, count($storys)-1)]), ' ');  
                    $theSource = 'KEY-'.random_int(1, 4);
                break;
            }
            $metaArray = [
                'rundown_rows_id'   => random_int(1, count($colors)),
                'title'             => $theTitle,
                'type'              => $theType,
                'source'            => $theSource,
                'start'             => random_int(0, 179),
                'duration'          => random_int(1, 999999)
            ];
            Rundown_meta_rows::create($metaArray);
            
            $i++;
        }
    }
}