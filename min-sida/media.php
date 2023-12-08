<?php

require_once "model.php";

class Media extends Model {
    protected $MediaID;
    protected $FilePath;

    function __construct($MediaID, $FilePath) {
        $this->MediaID = $MediaID;
        $this->FilePath = $FilePath;
    }

    function getMediaID() {
        return $this->MediaID;
    }

    function setMediaID($value)
    {
        $this->id = $value;
    }

    function getFilePath() {
        return $this->FilePath;
    }

    function setFilePath($value)
    {
        $this->id = $value;
    }
    

}


?>

