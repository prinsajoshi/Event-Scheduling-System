<?php

class Post{
    private $conn;

    public function __construct($db){
        $this->conn=$db;
    }

    public function validateOverlap($id,$start_time,$end_time,$time_zone){
        $userExist=$this->verifyUserExists($id);
        if (!empty($userExist)){             
            //Convert provided times to UTC
            $startUTC = new DateTime($start_time, new DateTimeZone($time_zone));
            $endUTC = new DateTime($end_time, new DateTimeZone($time_zone));
            $startUTC->setTimezone(new DateTimeZone("UTC"));
            $endUTC->setTimezone(new DateTimeZone("UTC"));

            foreach ($userExist as $event) {
                // Convert each event's start and end times from its time zone to UTC of the user
                $dbStart = new DateTime($event['start_time'], new DateTimeZone($event['time_zone']));
                $dbEnd = new DateTime($event['end_time'], new DateTimeZone($event['time_zone']));
                $dbStart->setTimezone(new DateTimeZone("UTC"));
                $dbEnd->setTimezone(new DateTimeZone("UTC"));
        
                // Check if the time intervals overlap
                if ($startUTC < $dbEnd && $endUTC > $dbStart) {
                    return ["status" => true, "message" => "Events for the same user cannot overlap based on the converted timezone"];
                }
            }
        
            return false;

            }
    }

    public function verifyUserExists($id){
        
        // First, verify if the user exists
        $userExistsQuery = "SELECT start_time,end_time,time_zone FROM participants WHERE id = ?";
        $stmt = $this->conn->prepare($userExistsQuery);
        $stmt->bind_param("i",$id);
        $stmt->execute();
        $participant=$stmt->get_result();    
        if ($participant->num_rows<0) {
            return false; // User does not exist
        }
        $eventsOfParticipants=$participant->fetch_assoc();
        return $eventsOfParticipants;
    }
           
    }
    