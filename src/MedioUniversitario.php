<?php

namespace TrabajoTarjeta;

class MedioUniversitario extends Tarjeta implements TarjetaInterface{

    protected $tipo = "Medio Universitario";

    public function valorPasaje(){
        return ($this->pasaje)/2.0;
      }


}