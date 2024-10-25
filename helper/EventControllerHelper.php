<?php

class EventHelper{
    private $model;

    public function __construct($model){
        $this->model = $model;
    }

    public function validate($data){
        $TitleLengthValidation = $this->validateCharacterLength($data["title"]);
        if ($TitleLengthValidation !== true) {
            return $TitleLengthValidation; // Return the length validation error
        }

        $UTCTimestampsValidation = $this->validateUTCTimestamps($data["start_time"],$data["end_time"]);
        if ($UTCTimestampsValidation !== true) {
            return $UTCTimestampsValidation; // Return UTC timestamps validation error
        }

        $TimezoneValidation = $this->validateTimezone($data["time_zone"]);
        if ($TimezoneValidation !== true) {
            return $TimezoneValidation; // Return the length validation error
        }

        $OverlapValidation=$this->validateOverlap($data["id"],$data["start_time"],$data["end_time"],$data["time_zone"]);
        if ($OverlapValidation !== true) {
            return $OverlapValidation; // Return the Overlap Validation error
        }

        return true; 
    }

    public function validateCharacterLength($Title){
        if (empty($Title)){
            return ["status"=>false,"message"=>"Title is required"];
        }
        if(strlen($Title)<5){
            return ["status"=>false,"message"=>"Title limits the length limit it must be between 5 and 150"];
        }elseif( (strlen($Title)>150)){
            return ["status"=>false,"message"=>"Title exceeds the length limit it must be between 5 and 150"];
        }
        return true;
    }

    public function validateUTCTimestamps($start_time,$end_time){
        $start_timestamp = strtotime($start_time);
        $end_timestamp = strtotime($end_time);

        // Compare the Unix timestamps
        if ($end_timestamp > $start_timestamp) {
            return true; // End time is after start time
        } else {
             return ["status"=>false,"End time is not after the start time for the event"];
        }   
    }

    public function validateTimezone($time_zone){
        if (in_array($time_zone, DateTimeZone::listIdentifiers())) {
            return true; // Timezone is valid
        }
        else {
            return ["status"=>false,"Timezone is invalid"];
        }
    }

    public function validateOverlap($id,$start_time,$end_time,$time_zone){
        if($this->model->validateOverlap($id,$start_time,$end_time,$time_zone)){
            return ["status"=>false,"Events for the same user cannot overlap based on the converted timezone"];
        }

    }

}