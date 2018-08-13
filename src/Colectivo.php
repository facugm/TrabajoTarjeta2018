<?php

namespace TrabajoTarjeta;

class Colectivo implements ColectivoInterface {

    protected $linea;

    protected $empresa;

    protected $numero;

    //Devuelve linea del colectivo(string)
    public function linea(){
        return $this->linea;
    }

    //Devuelve empresa del colectivo(string)
    public function empresa(){
        return $this->empresa;
    }

    //Devuelve numero del colectivo(int)
    public function numero(){
        return $this->numero;
    }

    //Por ahora solo devuelve el boleto si el saldo es suficiente
    public function pagarCon(TarjetaInterface $tarjeta){
        if($tarjeta->obtenerSaldo() >= 14.80){
            return $boleto = new Boleto(14.80,$this,$tarjeta);
        }
        else{
            //aca se verifica si a la tarjeta le quedan viajes plus y cuantos
            //dependiendo de la cantidad de plus restantes retorna un boleto diferente
            if($tarjeta->tienePlus() == 0) {
                return $boleto = new Boleto("Viaje Plus",$this,$tarjeta);
                $tarjeta->viajePlus();
            }

            if($tarjeta->tienePlus() == 1) {
                return $boleto = new Boleto("Ultimo Plus",$this,$tarjeta);
                $tarjeta->viajePlus();
            }

            return FALSE;
        }
    }

}