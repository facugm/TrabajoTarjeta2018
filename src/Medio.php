<?php

namespace TrabajoTarjeta;

class Medio extends Tarjeta implements TarjetaInterface{

    //redefinimos el valor del pasaje de la clase
    public function valorPasaje(){
      return ($this->pasaje)/2.0;
    }
  
  }