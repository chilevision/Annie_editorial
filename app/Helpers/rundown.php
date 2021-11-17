<?php

/* 
| Helper functuions to handle rundowns
|
|
| Changeing order in collection by property "before_in_table
| Param: $rundownRows = collection, from DB-table rows
| Returns array [collection of rows, int of last row id]
*/
if (!function_exists('sort_rows'))
{
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

/*
|
| converts input time value to seconds
| Prams $time = string, from input type time 
| Returns int, as seconds
*/
if (!function_exists('to_seconds'))
{
    function to_seconds($time)
    {
        $duration = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $time);
        sscanf($duration, "%d:%d:%d", $hours, $minutes, $seconds);
        $duration = $hours * 3600 + $minutes * 60 + $seconds;
        return $duration;
    }
    
}
/*
|
| Formats bytes to b/kb/mb/gb 
| Prams $bytes = int, bytes to format, $precision = int, specifys 
| Returns formated bytes as string
*/
if (!function_exists('formatBytes'))
{
    function formatBytes($bytes, $precision = 2) { 
        $units  = array('B', 'KB', 'MB', 'GB', 'TB'); 
        $bytes  = max($bytes, 0); 
        $pow    = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow    = min($pow, count($units) - 1); 
        $bytes  /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
} 