<?php

namespace TrabajoTarjeta;

class Colectivo implements ColectivoInterface {

    protected $linea;
    protected $empresa;
    protected $numero;
    protected $bandera;

    public function __construct($linea, $bandera, $empresa, $numero){
        $this->linea = $linea;
        $this->bandera = $bandera;
        $this->empresa = $empresa;
        $this->numero = $numero;
    }

    //Devuelve linea del colectivo(string)
    public function linea(){
        return $this->linea;
    }

    public function bandera(){
        return $this->bandera;
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

        switch($tarjeta->descontarSaldo($this)){
            case "PagoNormal":
                return $boleto = new Boleto($this, $tarjeta, "Normal");
            
            case "AbonaPlus":
                return $boleto = new Boleto($this, $tarjeta, "AbonaPlus");

            case "Trasbordo":
                return $boleto = new Boleto($this, $tarjeta, "Trasbordo");

            case "Plus1":
                return $boleto = new Boleto($this, $tarjeta, "Viaje Plus");

            case "Plus2":
                return $boleto = new Boleto($this, $tarjeta, "Ultimo Plus");

            case FALSE:
                return FALSE;
        }
    }
}
