<?php

namespace TrabajoTarjeta;

interface TarjetaInterface {


    /* Recarga una tarjeta con un cierto valor de dinero.
       Devuelve TRUE si el monto a cargar es válido, o FALSE en caso de que no
       sea valido.*/
    public function recargar($monto);

    //Devuelve el saldo que le queda a la tarjeta.
    public function obtenerSaldo();

    public function viajePlus();
}
