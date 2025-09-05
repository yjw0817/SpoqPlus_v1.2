<?php
namespace App\Libraries;

use CodeIgniter\I18n\Time;


class SpoQCache_lib {

    public function __construct() {
        
    }
    
    public function setCacheObj($Obj)
    {
        foreach ($Obj as $key => $value)
        {
            $_SESSION[$key] = $value;
        }
    }
    
    public function getCacheObj()
    {
        return $_SESSION;
    }
    
    public function setCahceVar($key,$value)
    {
        $_SESSION[$key] = $value;
    }
    
    public function getCacheVar($key)
    {
        $return_value = "";
        if (isset($_SESSION[$key]))
        {
            $return_value = $_SESSION[$key];
        }
        
        return $return_value;
    }
	
}