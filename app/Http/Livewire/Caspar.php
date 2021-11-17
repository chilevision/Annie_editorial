<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Settings;
use App\Models\Mediafiles;
use App\Models\Templatefiles;
use CosmonovaRnD\CasparCG;
use Carbon\Carbon;
use Livewire\WithPagination;

class Caspar extends Component
{
    use WithPagination;

    public $title = 'rundown.mediafiles';
    public $content_type = 'media';
    public $caspar_error;
    public $selected;

    public $search;
    public $per_page        = [10,25,50,100];
    public $perPage         = 10;
    public $orderBy         = 'name';
    public $orderAsc        = true;
    public $arrow           = '<i class="bi bi-arrow-down-circle-fill"></i>';
    public $type            = 'MOVIE';
    public $hide_spinner    = false;

    public $first_load              = true;
    protected $update_frequency     = 120;
    protected $listeners            = ['mediabrowser'];
    protected $paginationTheme      = 'bootstrap';
    
    public function render()
    {
        if ($this->first_load){
            $files = [];
        } 
        else {
            if ($this->content_type == 'templates') {

            }
            else {
                $files = Mediafiles::whereIn('type', $this->type)->when($this->search, function($query, $search){
                    return $query->where('name', 'LIKE', "%$search%");
                })->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->paginate($this->perPage);
            }
        }
        return view('livewire.caspar', [
            'files' => $files
        ]);
    }
    protected function my_error_handler($errno, $errstr, $errfile, $errline) {}

    public function mediabrowser($query, $input)
    {
        $this->first_load   = false;
        $this->selected     = $input;
        $this->type         = [$query];
        $this->orderBy      = 'name';
        $this->orderAsc     = true;
        $this->reset('caspar_error');
        if ($query == 'BG') $this->type = ['MOVIE', 'STILL'];

        $current_date_time = Carbon::now()->timestamp;
        if ($query == 'TEMPLATE'){
            $this->content_type = 'templates';
            $last_updated = Settings::where('id', 1)->first()->templates_updated;
            if ($last_updated == NULL || strtotime($last_updated)+$this->update_frequency < $current_date_time){
                $templates = $this->update_templates();
                if (is_array($templates)){
                    $this->caspar_error = $templates[1];
                }
                else{
                    sleep(2);
                    Settings::where('id', 1)->update(['templates_updated' => Carbon::now()->toDateTimeString()]);
                }
            }
        }
        else {
            $this->content_type = 'media';
            $last_updated = Settings::where('id', 1)->first()->media_updated;
            if ($last_updated == NULL || strtotime($last_updated)+$this->update_frequency < $current_date_time){
                $media_files = $this->update_mediafiles();
                if (is_array($media_files)){
                    $this->caspar_error = $media_files[1];
                }
                else{
                    sleep(2);
                    Settings::where('id', 1)->update(['media_updated' => Carbon::now()->toDateTimeString()]);
                }
            }
        }
    }

    protected function update_mediafiles()
    {
        set_error_handler(array($this, 'my_error_handler'));
        $host   = Settings::where('id', 1)->first()->videoserver_ip;
        $port   = Settings::where('id', 1)->first()->videoserver_port;
        $fp     = fsockopen($host, $port, $errno, $errstr,2.0);
        
        if (!$fp) return ['error', 'rundown.caspar_error_1'];
        
        fclose($fp);    
        $client = new CasparCG\Client($host, $port, 10);
        $response = $client->send('CLS');
        if($response->success()) {
            Mediafiles::truncate();
            $rawArr = preg_split("/\r\n|\n|\r/", $response->getBody());
            foreach ($rawArr as $arr){
                $fps        = 0;
                $duration   = 0;
                $name       = $this->getStringBetween($arr);
                if(!$this->is_junkfile($name)){
                    $arr = preg_replace('/\"(.*?)+?\"/','',$arr);
                    $arr = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $arr);
                    if ($arr[0] == ' ') $arr = ltrim($arr, ' ');
                    $arr        = explode(' ', $arr);
                    $type       = $arr[0];
                    $divider    = explode('/', $arr[4]);
                    if ($type == 'MOVIE'){
                        $fps = $divider[1]/$divider[0];
                        if (floor($fps) != $fps) $fps = number_format((float)$fps, 2, '.', '');
                        $duration = $arr[3]*$divider[0]/$divider[1];
                    }
                    if ($type == 'AUDIO') $duration = $arr[3]*$divider[0]/$divider[1];
                    Mediafiles::create([
                        'name'          => $name,
                        'type'          => $type,
                        'size'          => $arr[1],
                        'modified_at'   => date('Y-m-d H:i:s', strtotime($arr[2])),
                        'duration'      => round($duration),
                        'fps'           => $fps,
                    ]);
                }
            }
            return 'success';
        }
        else {
            return ['error', 'rundown.caspar_error_2', $response->success()];
        }
    }

    protected function update_templates()
    {
        set_error_handler(array($this, 'my_error_handler'));
        $host=Settings::where('id', 1)->first()->videoserver_ip;
        $port=Settings::where('id', 1)->first()->videoserver_port;
        $fp = fsockopen($host, $port, $errno, $errstr,2.0);
        if (!$fp) {
            return ['error', 'rundown.caspar_error_1'];
        } else {
            fclose($fp);

            $client = new CasparCG\Client($host, $port, 10);
            $response = $client->send('TLS');
            
            if($response->success()) {
                $templateArr = preg_split("/\r\n|\n|\r/", $response->getBody());
                foreach ($templateArr as $template){
                    if(!$this->is_junkfile($template)){
                        Templatefiles::create([
                            'name' => $template
                        ]);
                    }
                }
                return 'success';
            } else {
                return ['error', 'rundown.caspar_error_2', $response->success()];
            }
        }
    }

    protected function is_junkfile($file){
        if ($file == '.DS_STORE')               return true;
        elseif (substr($file, 0, 2 ) == '._')   return true;
        elseif (preg_match('/(\/._)/', $file))  return true;
        else                                    return false;
    }


    public function changeOrder($newOrderBy)
    {
        if ($newOrderBy == $this->orderBy) $this->orderAsc = !$this->orderAsc;
        else $this->orderAsc = true;
        $this->orderBy  = $newOrderBy;
        $this->orderAsc ? $this->arrow = '<i class="bi bi-arrow-down-circle-fill"></i>' : $this->arrow = '<i class="bi bi-arrow-up-circle-fill"></i>';
    }

    protected function getStringBetween($str)
    {
        $sub = substr($str, strpos($str, '"')+strlen('"'),strlen($str));
        return substr($sub,0,strpos($sub, '"'));
    }
}
