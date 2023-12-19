<?php
    /**
     * @author Laura Luque Bravo
     * Arrays de meses y festivos
     */

    //Array de meses
    $meses = array(
        1 => "Enero",
        2 => "Febrero",
        3 => "Marzo",
        4 => "Abril",
        5 => "Mayo",
        6 => "Junio",
        7 => "Julio",
        8 => "Agosto",
        9 => "Septiembre",
        10 => "Octubre",
        11 => "Noviembre",
        12 => "Diciembre"
    );

    //Añadiendo festivos, array multidimensional asociativo
    $festivos = array(
        "local" => array(
            9 => array(8),
            10 => array(24),
        ),
        "nacional" => array(
            1 => array(1, 6),
            //3 => array(28, 29),
            5 => array(1),
            8 => array(15),
            10 => array(12),
            11 => array(1),
            12 => array(6, 8, 25, 31)
        ),
        "comunidad" => array(
            2 => array(28)
        )
    );
?>