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

        switch($tarjeta->descontarSaldo()){
            case "PagoNormal":
                return $boleto = new Boleto($tarjeta->valorPasaje(), $this, $tarjeta);
                break;

            case "Plus1":
                return $boleto = new Boleto("Viaje Plus", $this, $tarjeta);
                break;

            case "Plus2":
                return $boleto = new Boleto("Ultimo Plus", $this, $tarjeta);
                break;
        }
        

    }

}
