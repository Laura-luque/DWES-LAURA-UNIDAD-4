<?php
    /**
     * @author Laura Luque Bravo
    */

    //Añadimos librerias
    include "funciones.php";
    include "config.php";
    

    //Variables del formulario
    $day = $month = $month_name = $year = "";

    //Cargamos el mes y el año
    $sys_month = date("n");
    $sys_year = date("Y");

    //Obtenemos día actual
    $sys_day = date("j");

    //Variables de error
    $day_Err = $month_Err = $year_Err = "";
    

    //Empezamos con el formulario
    $procesaForm = false;
    $error = false;

    // Cargamos el mes y el año desde el formulario si se han enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validar mes
        if (array_search($_POST["mes"], $meses)) {
            $month = array_search($_POST["mes"], $meses);
        }

        // Validar año
        if (!empty($_POST["ano"]) && is_numeric($_POST["ano"]) && $_POST["ano"] >= 1900) {
            $year = $_POST["ano"];
        }else{
            $year_Err = "VALOR NO VÁLIDO, TOMANDO VALOR POR DEFECTO";
            $year = $sys_year;
        }

        //Obtenemos días totales
        //$days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $days = n_dias(intval($month), intval($year));

        // Validar día
        if (!empty($_POST["dia"]) && is_numeric($_POST["dia"]) && $_POST["dia"] >= 1 && $_POST["dia"] <= $days) {
            $day = $_POST["dia"];
        }
    } else {
        // Si no se ha enviado el formulario, establecemos las fechas predeterminadas
        $day = $sys_day;
        $month = $sys_month;
        $year = $sys_year;
    }

    //Sacamos el primer día (1 = Lunes, 2 = Martes, etc.), con mktime sacas la marca de tiempo Unix de una fecha
    $first_day = date("N", mktime(0, 0, 0, intval($month), 1, intval($year)));

    //Obtenemos semana santa
    $semanasanta = Calc_SemSant($year);

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Laura Luque Bravo">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css"></link>
        <title>Calendario - Laura Luque Bravo</title>
    </head>
    <body>
        <h1>Calendario</h1>
        <?php
        if (!$procesaForm) { ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <!-- <input type="text" name="dia" value="<?php echo $day; ?>"> -->
            <select name="mes">
                <?php
                    foreach ($meses as $key => $value) {
                        $selected = ($key == $month) ? "selected" : "";
                        echo "<option value='$value' $selected>$value</option>";
                        $month_name = $meses[$month];
                    }
                ?>
            </select>
            <input type="text" name="ano" value="<?php echo $year; ?>">
            <input type="submit" name="submit" value="Enviar">
            <br/>
            <span class="error"><?php echo $day_Err; ?></span>
            <span class="error"><?php echo $month_Err; ?></span>
            <span class="error"><?php echo $year_Err; ?></span>
            </form>
            <br/>
            <h2><?php echo "Mostrando ".$month_name.": ".$year ?></h2>
            <br/>
            <table border='1'>
            <tr><th>Lunes</th><th>Martes</th><th>Miércoles</th><th>Jueves</th><th>Viernes</th><th>Sábado</th><th>Domingo</th></tr>
            <tr>
            <?php
                // Creamos celdas vacías hasta el primer día de la semana
                for ($dia = 1; $dia < $first_day; $dia++) {
                    echo "<td class=\"white\"></td>";
                }

                $days = n_dias(intval($month), intval($year));

                //Metemos días en la tabla
                for ($dia = 1; $dia <= $days ; $dia++) { 
                    //Booleanos para indicar si un día es local, comunidad o nacional
                    $festivo_local = false;
                    $festivo_com = false;
                    $festivo_nac = false;
                    
                    if (isset($festivos["nacional"][$month])) {
                        $festivo_nac = in_array($dia, $festivos["nacional"][$month]);
                    }
                    if (isset($festivos["comunidad"][$month])) {
                        $festivo_com = in_array($dia, $festivos["comunidad"][$month]);
                    }
                    if (isset($festivos["local"][$month])) {
                        $festivo_local =  in_array($dia, $festivos["local"][$month]);
                    }
                    if ($month == $semanasanta[0]) {
                        $festivo_nac = in_array($dia, $semanasanta[1]);
                    }
                    
                    $cond = ($month == $sys_month and $year == $sys_year);
                    $esdomingo = (date("N", mktime(0, 0, 0, intval($month), $dia, intval($year))) == 7);

                    //Asignamos color
                    if ($dia == $sys_day and $cond) {
                        $color = "green";
                    } else if ($festivo_local) {
                        $color = "blue";
                    } else if ($festivo_com) {
                        $color = "yellow";
                    } else if ($festivo_nac) {
                        $color = "red";
                    } else if ($esdomingo) {
                        $color = "domingo";
                    } else {
                        $color = "white";
                    }

                    echo "<td class=\"".$color."\">$dia</td>";

                    // Si es Domingo (día 7), termina la fila y empieza una nueva
                    if ($first_day == 7) {
                        echo "</tr><tr>";
                        $first_day = 1;
                    } else {
                        $first_day++;
                    }                   
                    
                }

                //Llenamos celdas vacías
                while ($first_day > 1 and $first_day < 8) {
                    echo "</td class=\"white\"><td>";
                    $first_day++;
                }
            ?>
            </tr></table>
        <?php
        }
        ?>
        
    </body>
</html>