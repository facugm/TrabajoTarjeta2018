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
    protected $anteriorColectivo = NULL;
    protected $fueTrasbordo = FALSE;

    public function __construct($id, TiempoInterface $tiempo){
      $this->id = $id;
      $this->saldo = 0.0;
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
    
    protected function pagarBoleto(){

      if($this->esTrasbordo()){  //Si es trasbordo

        $this->saldo -= round($this->valorPasaje() * 0.33,2); //Se cobra un 33% del valor del pasaje
        $this->horaPago = $this->tiempo->time();       //guarda la hora en la que se realizo el pago
        $this->fueTrasbordo = TRUE;
        return "Trasbordo";
      }

      if($this->saldo >= $this->valorPasaje()){   //se verifica si tiene saldo
        if($this->plus == 0){                     //despues se comprueba que no deba ningun plus
          $this->saldo -= $this->valorPasaje();   //si no debe ninguno, se descuenta normalmente el saldo
          $this->horaPago = $this->tiempo->time();  //guarda la hora en la que se realizo el pago
          $this->fueTrasbordo = FALSE;
          return "PagoNormal";
        }

        elseif($this->plus == 1) {//si debe uno se descuenta el valor del boleto + el valor del plus que debe
          if($this->saldo >= $this->valorPasaje() + $this->valorBoleto){//Le alcanza?
            $this->saldo -= $this->valorPasaje() + $this->abonaPlus();//se resta el valor del pasaje de la tarjeta, la cantidad de plus que deba y se reinicia el contador de plus
            $this->horaPago = $this->tiempo->time();
            $this->fueTrasbordo = FALSE;
            return "AbonaPlus";
          }  

          else{//si no puede pagar el valor del boleto + el del plus que debe, no puede abonar el pasaje
            $this->viajePlus();
            $this->horaPago = $this->tiempo->time();
            $this->fueTrasbordo = FALSE;
            return "Plus2";
          }

        }
        elseif($this->plus == 2) {//si debe dos se descuenta el valor del boleto + el valor de los plus que debe
          if($this->saldo >= $this->valorPasaje() + $this->valorBoleto * 2){
            $this->saldo -= $this->valorPasaje() + $this->abonaPlus(); //aca se resta el valor del pasaje de la tarjeta, la cantidad de plus que deba y se reinicia el contador de plus
            $this->horaPago = $this->tiempo->time();
            $this->fueTrasbordo = FALSE;
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
          
          case 1:
            $this->viajePlus();
            $this->horaPago = $this->tiempo->time();
            return "Plus2";

        }
      }

      else{//si no le queda saldo ni plus, no puede pagar
        return FALSE;
      } }

    public function descontarSaldo(ColectivoInterface $colectivo){
      if($this->anteriorColectivo == NULL){ 
        $this->anteriorColectivo = $colectivo;
      }
      else{
        $this->anteriorColectivo = $this->actualColectivo;
      }
        $this->actualColectivo = $colectivo;

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

    protected function esTrasbordo(){
      $tiempoActual= $this->tiempo->time();
      $hora = date("G", $tiempoActual);
      $dia = date("w", $tiempoActual);

      if($this->colectivosDiferentes()){ //Si el colectivo en el que se esta usando la tarjeta ahora es diferente al anterior

        if($hora >= 22 || $hora < 6 ) { //Todos los dias de 22 a 6
          if($tiempoActual - $this->obtenerFecha() <= 5400){ //Si pasaron 90 minutos o menos
            $this->fueTrasbordo = TRUE;
            return TRUE; //Paga trasbordo
          }
        }

        elseif($dia == 6 ){ //Si es sábado
          if($hora >= 6 && $hora < 14 ) { //De 6 a 14
            if($tiempoActual - $this->obtenerFecha() <= 3600){ //Si pasaron 60 minutos o menos
              $this->fueTrasbordo = TRUE;
              return TRUE; //Paga trasbordo
            }
          }
          else{
            if($tiempoActual - $this->obtenerFecha() <= 5400){ //Si pasaron 90 minutos o menos
              $this->fueTrasbordo = TRUE;
              return TRUE; //Paga trasbordo
            }
          }
        }

        elseif($dia == 0 || $this->esFeriado()){ //Si es domingo o feriado
          if($hora >= 6 && $hora < 22 ) { //De 6 a 22
            if($tiempoActual - $this->obtenerFecha() <= 5400){ //Si pasaron 90 minutos o menos
              $this->fueTrasbordo = TRUE;
              return TRUE; //Paga trasbordo
            }
          } 
        }

        else{ //De lunes a viernes de 6 a 22
            if($tiempoActual - $this->obtenerFecha() <= 3600){ //Si pasó una hora o menos
              $this->fueTrasbordo = TRUE;
              return TRUE; //Paga trasbordo
            }
          }
      }

      return FALSE;
    }


    protected function colectivosDiferentes(){
     
      $linea1 = $this->anteriorColectivo->linea();
      $linea2 = $this->actualColectivo->linea();

      $bandera1 = $this->anteriorColectivo->bandera();
      $bandera2 = $this->actualColectivo->bandera();
    
      if($linea1 != $linea2 or $bandera1 != $bandera2){
        return TRUE;        
      }
    

      return FALSE;                  
    }

    protected function esFeriado(){

      $fecha = date('d-m',$this->tiempo->time());

      $feriados = array( 
            '01-01',  //  Año Nuevo
            '24-03',  //  Día Nacional de la Memoria por la Verdad y la Justicia.
            '02-04',  //  Día del Veterano y de los Caídos en la Guerra de Malvinas.
            '01-05',  //  Día del trabajador.
            '25-05',  //  Día de la Revolución de Mayo. 
            '17-06',  //  Día Paso a la Inmortalidad del General Martín Miguel de Güemes.
            '20-06',  //  Día Paso a la Inmortalidad del General Manuel Belgrano. F
            '09-07',  //  Día de la Independencia.
            '17-08',  //  Paso a la Inmortalidad del Gral. José de San Martín
            '12-10',  //  Día del Respeto a la Diversidad Cultural 
            '20-11',  //  Día de la Soberanía Nacional
            '08-12',  //  Inmaculada Concepción de María
            '25-12',  //  Navidad
            );

      return in_array($fecha,$feriados); //Si la fecha está en el array, es feriado
    }
  }