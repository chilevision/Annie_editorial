<?php

if (!function_exists('sort_rows'))
{
    /**
    * Helper functuions to handle rundowns
    * Changing order in collection by property "before_in_table
    * Returns array [collection of rows, int of last row id]
    * @param object $rows collection from DB-table rows
    * @return array [0]sorted rows, [1]last row
    */
    function sort_rows($rows)
    {
        $rundownrows    = collect();
        $last_row       = NULL;
        //if collection of rundown rows isn't empty - sort collection by "row before" 
        if (!$rows->isEmpty()){
            $before_in_table = NULL;
            $i = 0;
            while ( $i < $rows->count() )
            {
                $row = $rows->where('before_in_table', $before_in_table)->first();
                $rundownrows->add($row);
                $i++;
                $before_in_table = $row->id;
                if($i == $rows->count()) $last_row = $before_in_table;
            }
        }
        
        return [$rundownrows, $last_row];
    }
}

if (!function_exists('to_seconds'))
{
    /**
    *
    * converts input time value to seconds
    * @param string $time timestring
    * @return int time as seconds
    */
    function to_seconds($time)
    {
        if (strpos($time, ':') !== false) {
        // String is timestring
            if (strlen($time) == 5){
                $time = $time.':00';
            }
            $duration = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $time);
            sscanf($duration, "%d:%d:%d", $hours, $minutes, $seconds);
            $duration = $hours * 3600 + $minutes * 60 + $seconds;
        }
        else{
        // String is miliseconds
            $duration = $time/1000;
        }
    
        return $duration;
    }
    
}

if (!function_exists('formatBytes'))
{
    /**
    *
    * Formats bytes to b/kb/mb/gb 
    * Returns formated bytes as string
    * @param int $bytes bytes to format
    * @param int $precision Optional specifys
    */
    function formatBytes($bytes, $precision = 2) { 
        $units  = array('B', 'KB', 'MB', 'GB', 'TB'); 
        $bytes  = max($bytes, 0); 
        $pow    = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow    = min($pow, count($units) - 1); 
        $bytes  /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

if (!function_exists('get_custom_logo'))
{
    /**
    *
    * Checks if site_logo folder is empty if not returns first file
    * @return string returns folder path /OR false
    */
    function get_custom_logo()
    {
        $dir = public_path('site_logo');
        $q   = (count(glob("$dir/*")) === 0) ? 'Empty' : 'Not empty';
        
        if ($q=="Empty"){
            return false;
        }
        else{
            $file = substr(glob("$dir/*.{jpg,png,jpeg}", GLOB_BRACE)[0], strrpos(glob("$dir/*.{jpg,png,jpeg}", GLOB_BRACE)[0], '/') + 1);
            return ($file);
        }
    }
}

if (!function_exists('font_size_replace'))
{
    /**
    *
    * Replaces font style unit from px to em
    * @param string $string html string
    * @param int $reference Optional reference font size
    * @return string html string with replaced size units
    */
    function font_size_replace($string, $reference = 14)
    {
        preg_match_all('/style="font-size: *(200|[1-9]?[0-9])*px;"/', $string, $fontstyles);
        foreach ($fontstyles[1] as $key => $fontsize){
            $em = 'style="font-size: '.round($fontsize/$reference, 1).'em";';
            $string = str_replace($fontstyles[0][$key], $em, $string);
        }
        return $string;
    }
}

if (!function_exists('metaDataToString'))
{
    /**
    *
    * Dekodes meta row data and returns string
    * @param string $data JSON encoded string
    * @return string decoded 
    */
    function metaDataToString($data){
        json_decode($data);
        if (json_last_error()){
            $output = strip_tags($data);
        }
        else{
            $keys = json_decode($data, true);
            $output = '';
            $i = 1;
            foreach ($keys as $key => $val){
                $output .= $key . $val;
                if ($i < count($keys)){
                    $output .= ' ';
                }
                $i++;
            }
        }
        return $output;
    }
}

if (!function_exists('getFileType'))
{
    /**
    *
    * Returns a valid file type if input is correct
    * @param string $type file type M = MOVIE, S = STILL, A = AUDIO
    * @return string full length file type
    */
    function getFileType($type){ 
        switch ($type){
            case 'M': 
                $mediatype = 'MOVIE';
                break;
            case 'S':
                $mediatype = 'STILL';
                break;
            case 'A':
                $mediatype = 'AUDIO';
                break;
            default :
                return false;
                break;
        }
        return $mediatype;
    }
}