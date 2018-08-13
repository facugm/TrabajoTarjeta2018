<?php

namespace TrabajoTarjeta;

class Tarjeta implements TarjetaInterface {
    protected $saldo;
    protected $cargas = array("10", "20", "30", "50", "100", "510.15", "962.59");
    protected $plus = 0;

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

      
      if ($cargavalida) {
        if ($plus > 0 ) {
          $monto -= ($plus * 14.8);    //si la carga es valida, le descuenta al monto a cargar la cantidad de viajes plus usados
          $plus = 0;                   //aqui se resetea la cantidad de viajes plus, para que pueda seguir usandolos
        }
        $this->saldo += $monto;
      }
    
      return $cargavalida;
    }

    //esta funcion devuelve la cantidad de viajes plus que uso la tarjeta
    public function tienePlus(){
      return $plus;
    }

    //Suma 1 a la cantidad de viajes plus hechos
    public function viajePlus() {
      $plus += 1;
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
