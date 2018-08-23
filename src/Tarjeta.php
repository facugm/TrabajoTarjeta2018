<?php

namespace TrabajoTarjeta;

class Tarjeta implements TarjetaInterface {
    protected $saldo;
    protected $cargas = array("10", "20", "30", "50", "100", "510.15", "962.59");
    protected $plus = 0;
    protected $pasaje = 14.80;

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

      
      if ($cargavalida and $this->plus == 0) {   //si la carga es válida y no debe ningún plus
        $this->saldo += $monto;                 //carga el monto elegido
      }

      //si la carga no es válida o debe algún plus entrará acá
      elseif($cargavalida and $this->plus > 0){ //si la carga es válida y debe algún plus

        if($monto >=($this->plus * 14.8)){     //si el monto a cargar es mayor o igual a los viajes que debe (en pesos)
          $monto -= ($this->plus * 14.8);       //resta del monto a cargar los plus que debe
          $this->plus=0;                        //los plus vuelven a 0
        }

        else{                   //si llega a este caso significa que la carga no es suficiente para pagar los plus
          return FALSE;         //cargaválida = falso
        }

      }
  
      return $cargavalida;
    }

    //devuelve el valor de un pasaje
    public function valorPasaje(){
      return $this->$pasaje;
    }

    //esta funcion devuelve la cantidad de viajes plus que uso la tarjeta
    public function tienePlus(){
      return $this->plus;
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
}

class Medio extends Tarjeta{

}

class Completo extends Tarjeta{

}
