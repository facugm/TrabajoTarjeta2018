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
    public function pagarCon($tarjeta){
        if($tarjeta->saldo >= 14.80){
            return $boleto;
        }

        else{
            return FALSE;
        }
    }

}