<?php 
namespace App\Exceptions; 
 
class ApiException extends \Exception 
{ 
    function __construct($msg='') 
    { 
        parent::__construct($msg); 
    } 
} 