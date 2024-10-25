<?php

class Event{
    private $conn;

    public function __construct($db){
        $this->conn=$db;
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

    public function validateOverlap($id, $startUTC, $endUTC){
        // Query for the user’s events and check overlap
        $query = "SELECT * FROM participant WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iss", $id, $endUTC, $startUTC);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? ["status" => true, "message" => "Overlap detected"] : false;
    }
    
    public function insertEvent($data){
        $query = "INSERT INTO participant (event_id, name,email, rsvp_status) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isss", $data["event_id"], $data["name"], $data["email"], $data["rsvp_status"]);
        $stmt->execute();
    }   

    public function uniqueEmail($email){
        $query = "SELECT * FROM participant WHERE $email = ? ";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? true : false;
    }
       
    }
    