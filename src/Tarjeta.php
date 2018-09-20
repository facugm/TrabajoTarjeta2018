<?php

namespace TrabajoTarjeta;

class Tarjeta implements TarjetaInterface {
    protected $valorBoleto = 16.8;
    protected $pasaje = 16.8;
    protected $saldo;
    protected $cargas = array("10", "20", "30", "50", "100", "510.15", "962.59");
    protected $plus = 0;
    protected $tipo = "Normal";
    protected $id;
    protected $horaPago;
    protected $actualColectivo;
    protected $anteriorColectivo;

    public function __construct($id, TiempoInterface $tiempo){
      $this->id = $id;
      $this->tiempo = $tiempo;
    }

    public function recargar($monto) {
      // Esto comprueba si la carga esta dentro de los montos permitidos
      $cargavalida = in_array($monto, $this->cargas);

      //Comprueba si la carga va a obtener un adicional y se lo suma
      if($monto==510.15){
        $monto += 81.93;
      }
      
      elseif($monto==962.59){
        $monto += 221.58;
      }
      
      if($cargavalida){
        $this->saldo += $monto;
      }
  
      return $cargavalida;
    }

    //Devuelve el valor de un pasaje
    public function valorPasaje(){
      return $this->pasaje;
    }

    //Suma 1 a la cantidad de viajes plus hechos
    public function viajePlus() {
      $this->plus += 1;
    }

    /**
     * Devuelve el saldo que le queda a la tarjeta.
     *
     * @return float
     */
    public function obtenerSaldo() {
      return $this->saldo;
    }
    
    public function pagarBoleto(){

      if($this->esTrasbordo()){  //Si es trasbordo
        $this->saldo -= ($this->valorPasaje() * 0.33); //Se cobra un 33% del valor del pasaje
        $this->horaPago = $this->tiempo->time();       //guarda la hora en la que se realizo el pago
        return "Trasbordo";
      }

      if($this->saldo >= $this->valorPasaje()){   //se verifica si tiene saldo
        if($this->plus == 0){                     //despues se comprueba que no deba ningun plus
          $this->saldo -= $this->valorPasaje();   //si no debe ninguno, se descuenta normalmente el saldo
          $this->horaPago = $this->tiempo->time();  //guarda la hora en la que se realizo el pago
          return "PagoNormal";
        }

        elseif($this->plus == 1) {//si debe uno se descuenta el valor del boleto + el valor del plus que debe
          if($this->saldo >= $this->valorPasaje() + $this->valorBoleto){//Le alcanza?
            $this->saldo -= $this->valorPasaje() + $this->abonaPlus();//se resta el valor del pasaje de la tarjeta, la cantidad de plus que deba y se reinicia el contador de plus
            $this->horaPago = $this->tiempo->time();
            return "AbonaPlus";
          }  

          else{//si no puede pagar el valor del boleto + el del plus que debe, no puede abonar el pasaje
            $this->viajePlus();
            $this->horaPago = $this->tiempo->time();
            return "Plus2";
          }

        }
        elseif($this->plus == 2) {//si debe dos se descuenta el valor del boleto + el valor de los plus que debe
          if($this->saldo >= $this->valorPasaje() + $this->valorBoleto * 2){
            $this->saldo -= $this->valorPasaje() + $this->abonaPlus(); //aca se resta el valor del pasaje de la tarjeta, la cantidad de plus que deba y se reinicia el contador de plus
            $this->horaPago = $this->tiempo->time();
            return "AbonaPlus";
          }
        
        else{//si no puede pagar el valor del boleto + el de los plus que debe, no puede abonar el pasaje
          return FALSE;
        }

        }

      }

      elseif($this->plus < 2){//si no tiene saldo suficiente se verifica si le quedan plus disponibles
        switch($this->plus){
          case 0:
            $this->viajePlus();//dependiendiendo de la cantidad de viajes plus que le queden hace 1 o 2 viajes
            $this->horaPago = $this->tiempo->time();
            return "Plus1";
            break;
          
          case 1:
            $this->viajePlus();
            $this->horaPago = $this->tiempo->time();
            return "Plus2";
            break;

        }
      }

      else{//si no le queda saldo ni plus, no puede pagar
        return FALSE;
      }

    }

    public function descontarSaldo(){
      return $this->pagarBoleto();
    }

    public function abonaPlus(){
      $pagoPlus = $this->valorBoleto * $this->plus;
      $plus = 0;
      return $pagoPlus;
    }

    public function obtenerTipo(){
      return $this->tipo;
    }

    public function obtenerFecha(){
      return $this->horaPago;
    }

    public function obtenerId(){
      return $this->id;
    }

    public function esTrasbordo(){
      if($this->anteriorColectivo != $this->actualColectivo){ // si el colectivo en el que se esta usando la tarjeta ahora es diferente al anterior
        if ((date("w",$this->obtenerFecha()) <= 5 && date("w",$this->obtenerFecha()) >= 1)){ //De lunes a viernes
          if((date("H", $this->obtenerFecha()) >= 6 && (date("H", $this->obtenerFecha()) <= 22 ))){ //De 6 a 22
            if($this->tiempo->time() - $this->obtenerFecha() <= 3600){ //Si pasó una hora o menos
              return TRUE; //Paga trasbordo
            }
          }
        }

        if(date("w",$this->obtenerFecha()) == 6 ){ //Si es sábado
          if(date("H", $this->obtenerFecha()) >= 6 && date("H", $this->obtenerFecha()) <= 14 ) { //De 6 a 14
            if($this->tiempo->time() - $this->obtenerFecha() <= 5400){ //Si pasaron 90 minutos o menos
              return TRUE; //Paga trasbordo
            }
          } 
        }

        if(date("w",$this->obtenerFecha()) == 0 ){ //Si es domingo
          if((date("H", $this->obtenerFecha()) >= 6 && (date("H", $this->obtenerFecha()) <= 22 ))) { //De 6 a 22
            if($this->tiempo->time() - $this->obtenerFecha() <= 5400){ //Si pasaron 90 minutos o menos
              return TRUE; //Paga trasbordo
            }
          } 
        }
      
        if(date("H", $this->obtenerFecha()) >= 22 && date("H", $this->obtenerFecha()) <= 6 ) { //De 22 a 6
          if($this->tiempo->time() - $this->obtenerFecha() <= 5400){ //Si pasaron 90 minutos o menos
            return TRUE; //Paga trasbordo
          }
        }

      }

      return FALSE;
        
    }

    public function obtenerColectivo(ColectivoInterface $colectivo){
      $this->anteriorColectivo = $this->actualColectivo; 
      $this->actualColectivo = $colectivo; //obtiene el colectivo en el que se esta usando la tarjeta
    }

}