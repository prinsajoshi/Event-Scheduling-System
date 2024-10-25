<?php
class PostControllers{
    private $model;
    private $helper;

    public function __construct($model){
        $this->model=$model;
    }

    public function createPost($data){
        $this->helper= new EventHelper($this->model);

          // Validate the post data
          $validationResult = $this->helper->validate($data);
          if ($validationResult !== true) {
              return $validationResult; // Return the validation error response
          }
  
        
       
    }
}