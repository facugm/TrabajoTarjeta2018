<?php

namespace TrabajoTarjeta;

class Colectivo implements ColectivoInterface {

    protected $linea;

    protected $empresa;

    protected $numero;

    public function __construct($linea, $empresa, $numero){
        $this->linea = $linea;
        $this->empresa = $empresa;
        $this->numero = $numero;
    }

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
                $tarjeta->obtenerColectivo($this);
                return $boleto = new Boleto($this, $tarjeta, "Normal");
            
            case "AbonaPlus":
                $tarjeta->obtenerColectivo($this);
                return $boleto = new Boleto($this, $tarjeta, "AbonaPlus");

            case "Plus1":
                return $boleto = new Boleto($this, $tarjeta, "Viaje Plus");

            case "Plus2":
                return $boleto = new Boleto($this, $tarjeta, "Ultimo Plus");

            case FALSE:
                return FALSE;
        }        
    }
}
