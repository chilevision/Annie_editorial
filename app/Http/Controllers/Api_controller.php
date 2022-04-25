<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use App\Models\Rundown_rows;

class Api_controller extends Controller
{
    public function prompter($key, $id)
    {
        if ($key){
            if ($key == env('API_KEY')){
                $rundownrows = [];
                if ($id != null){
                    $rows = Rundown_rows::where('rundown_id', $id)->get();
                    if ($rows){
                        $output         = [];
                        $rundownrows    = sort_rows($rows)[0];
                        $counter = 0;
                        foreach ($rundownrows as $row){
                            $is_only_html = preg_match("#^(<[^>]*>)+$#", $row->script);
                
                            if ($row->script != null && !$is_only_html){
                                $output[$counter] = [
                                    'source'    => $row->source,
                                    'talent'    => $row->talent,
                                    'story'     => $row->story,
                                    'script'    => font_size_replace($row->script)
                                ];
                                $counter++;
                            }
                        }
                        echo json_encode($output);
                    }
                }
            }
            else{
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
        }
        else{
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
    }

    public function settings($key){
        if ($key){
            if ($key == env('API_KEY')){
                $settings = Settings::where('id', 1)->first()->getAttributes();
                $settings['PUSHER_APP_ID']         = env('PUSHER_APP_ID');
                $settings['PUSHER_APP_KEY']        = env('PUSHER_APP_KEY');
                $settings['PUSHER_APP_SECRET']     = env('PUSHER_APP_SECRET');
                $settings['PUSHER_APP_CLUSTER']    = env('PUSHER_APP_CLUSTER');
                echo json_encode($settings);
            }
            else{
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
        }
        else{
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
    }
}
