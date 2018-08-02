<?php

namespace TrabajoTarjeta;

class Colectivo implements ColectivoInterface {

    protected $linea;

    protected $empresa;

    protected $numero;

    public function linea(){
        return $this->linea;
    }

    public function empresa(){
        return $this->empresa;
    }

    public function numero(){
        return $this->numero;
    }

    public function pagarCon($tarjeta){
        if($tarjeta->saldo >= 14.80){
            return $boleto;
        }

        else{
            return FALSE;
        }
    }

}