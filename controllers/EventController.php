<?php
class EventControllers{
    private $model;
    private $helper;

    public function __construct($model){
        $this->model=$model;
    }
    //create events along with given particpant in table
    public function createEvent($data){
        $this->helper = new EventHelper($this->model);
    
        // Validate the post data
        $validationResult = $this->helper->validate($data);
        if ($validationResult !== true) {
            return $validationResult; // Return validation error response
        }
    
        // Timezone handling
        [$startUTC, $endUTC] = $this->helper->timeZoneHandling($data["start_time"], $data["end_time"], $data["time_zone"]);
    
        // Overlap Detection and Conflict Resolution
        $overlapValidation=$this->helper->validateOverlap($data["id"],$data["start_time"],$data["end_time"],$data["time_zone"]);
        if ($overlapValidation !== true) {
            return $overlapValidation; // Return overlap validation error
        }
    
        // Recurring Event Support
        if (isset($data["recurrence"])) {
            $recurringSupport = $this->helper->recurring($data["id"], $startUTC, $endUTC, $data["time_zone"], $data["recurrence"]);
            if ($recurringSupport !== true) {
                return $recurringSupport; // Return recurrence validation error
            }
        }

        //rsvp Management for participants 
        $rsvpManagement=$this->helper->rsvpManagement($data["rsvp"],$data["email"]);
        if ($rsvpManagement !== true) {
            return $rsvpManagement; // Return overlap validation error
        }
        
        // Finally, insert the event into the database
        $result = $this->model->insertEvent($data);
        return $result ;
    }
    
}