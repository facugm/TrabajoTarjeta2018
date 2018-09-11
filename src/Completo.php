<?php

namespace TrabajoTarjeta;

class Completo extends Tarjeta implements TarjetaInterface{

    //redefinimos el valor del pasaje de la clase
    protected $pasaje = 0.0;
    protected $tipo = "Completo";
  
  }