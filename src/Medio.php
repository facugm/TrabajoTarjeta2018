<?php

namespace TrabajoTarjeta;

class Medio extends Tarjeta implements TarjetaInterface{

  protected $tipo = "Medio";

    //redefinimos el valor del pasaje de la clase
    public function valorPasaje(){
      return ($this->pasaje)/2.0;
  }

  public function descontarSaldo(){

    if($this->tiempo->time() == $this->horaPago){ //si la hora actual = la hora del ultimopago significa que es el primer pago que hace la tarjeta
      return $this->pagarBoleto(); //por lo tanto se cobra normalmente el boleto
    }

    elseif($this->tiempo->time() - $this->horaPago >= 300){ //si pasaron 5 minutos o mas desde la ultima compra
      return $this->pagarBoleto(); //se cobra el boleto normalmente
    }

    else{ //si no pasaron al menos 5 minutos
      return FALSE; //no puede pagar
    }
  }
}