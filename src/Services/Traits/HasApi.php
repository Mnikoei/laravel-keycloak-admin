<?php

namespace Mnikoei\Services\Traits;

trait HasApi
{

    public function getApi($apiName , $values)
    {

        return $this->initApi($apiName , $values) ;

    }


    public function initApi($apiName , $values)
    {

        $api = $this->api[$apiName]['api'];

        foreach($values as $name => $value) {
            if(is_string($value)){
                $api = str_replace('{'.$name.'}', $value, $api);
            }

        }

        if (isset($values['query'])){
            $api .= $api.'?'.http_build_query($values['query']) ;
        }


        return [$api , $this->api[$apiName]['method']];
    }


}
