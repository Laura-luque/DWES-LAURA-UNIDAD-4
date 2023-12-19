<?php
    /**
     * @author Laura Luque Bravo
     */
    //Meses de 30 días
    define('MESES_30D', [4,6,9,11]);
    

    //Meses de 31 días
    define("MESES_31D", [1,3,5,7,8,10,12]);

    /**
     * @name test_input
     * @param $data
     * @return $data
     * 
     * Función que trata la información de los input
     */
    function test_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    /**
     * @name n_dias
     * @param $mes, $ano
     * @return int
     * 
     * Función que devuelve el número de días de un mes y
     * comprueba si un año es bisiesto.
     */
    function n_dias($mes, $ano){
        if (in_array($mes, MESES_30D)) {
            return 30;
        } 
        if (in_array($mes, MESES_31D)) {
            return 31;
        } 
        if ($ano % 4 == 0 and ($ano % 100 != 0 or $ano % 400 == 0)){
            //checkdate($month, 29, $year);
            return 29;
        }
        else{
            return 28;
        }
    }

    /**
     * @name Calc_SemSant
     * @param $ano
     * @return array
     * 
     * Función que devuelve un array con el mes de la semana santa y un
     * array con sus días.
     */
    function Calc_SemSant($ano){
        $easterTimestamp = easter_date($ano);
        $eastermonth = date("n", $easterTimestamp);
        $easterDays = array(
            date("j", strtotime("-3 days", $easterTimestamp)),  // Jueves Santo
            date("j", strtotime("-2 day", $easterTimestamp)),   // Viernes Santo
        );
        return array($eastermonth, $easterDays);
    }
?>