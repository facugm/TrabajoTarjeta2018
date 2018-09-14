<?php

namespace TrabajoTarjeta;

class MedioUniversitario extends Medio implements TarjetaInterface{

    protected $tipo = "Medio Universitario";
    private $mediosUsados = 0;

    public function valorPasaje(){
        if($this->mediosUsados<=2){
            return ($this->pasaje)/2.0;
        }
        else{
            return $this->pasaje;
        }
      }

    public function descontarSaldo(){
        $hoy = date("d/m/Y", $this->tiempo->time());
        $diaPago = date("d/m/Y", $this->horaPago);
        if($hoy>$diaPago){
          $this->mediosUsados = 0;
        }
        if($this->mediosUsados <= 2){
            $this->mediosUsados+= 1;
            return $this->pagarBoleto();
        }
        else{
            return $this->pagarBoleto();
        }
        
    }


}