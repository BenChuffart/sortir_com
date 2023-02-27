<?php

namespace App\Data;
use App\Entity\Campus;
use DateTime;

class Filters
{

    public ?Campus $campus = null;

    public ?string $nameTrip = null;

  
    public ?DateTime $startDateTime=null;

  
    public?DateTime $deadline =null;

    
    public ?bool $tripsOrganized = false;

   
    public ?bool $tripsRegisted = false;

   
    public ?bool $tripsNotRegisted = false;

    
    public ?bool $tripsPassed = false;


}
