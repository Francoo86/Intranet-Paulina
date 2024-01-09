<?php

namespace App\Interfaces;

//Java like moment.
interface IPersonInterface {
    //RUT en formatos enteros.
    public function getRut();
    public function setRut(int $rut);

    //Digito verificador.
    public function getDv();
    public function setDv(string $dv);
}

?>