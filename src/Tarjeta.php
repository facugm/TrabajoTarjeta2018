<?php

namespace TrabajoTarjeta;

class Tarjeta implements TarjetaInterface {
    protected $saldo;
    protected $cargas = array("10", "20", "30", "50", "100", "510.15", "962.59");

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
        $this->saldo += $monto;
      }
      return $cargavalida;
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
