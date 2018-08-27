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

        if($tarjeta instanceof Completo) return $boleto = new Boleto($tarjeta->valorPasaje(),$this,$tarjeta);

        if($tarjeta->obtenerSaldo() >= $tarjeta->valorPasaje()){

            if($tarjeta instanceof Medio) return $boleto = new Boleto($tarjeta->valorPasaje(),$this,$tarjeta);

            return $boleto = new Boleto($tarjeta->valorPasaje(),$this,$tarjeta);
        }
        else{
            //aca se verifica si a la tarjeta le quedan viajes plus y cuantos
            //dependiendo de la cantidad de plus restantes retorna un boleto diferente
            if($tarjeta->tienePlus() == 0) {
                $tarjeta->viajePlus();      //aqui se verifica si la tarjeta utilizo algun plus, se lo acredita y emite un boleto acorde al mismo
                return $boleto = new Boleto("Viaje Plus",$this,$tarjeta);
            }

            if($tarjeta->tienePlus() == 1) {
                $tarjeta->viajePlus();      //si la tarjeta ya utilizo un plus, se lo acredita y emite un boleto indicando que es el ultimo que puede utilizar
                return $boleto = new Boleto("Ultimo Plus",$this,$tarjeta);
            }
            
            //si la tarjeta no tiene saldo y tampoco le quedan viajes plus, devuelve FALSE
            return FALSE;
        }
    }
}
