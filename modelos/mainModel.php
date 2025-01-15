<?php

    if ($peticionAjax) {
        #si la peticion es verdadera se incluye de esta forma
        #porque esta dentro de la carpeta ajax
        require_once "../config/server.php";
    }
    else{
        #si la peticion es falsa se incluye desde el index.php
        require_once "./config/server.php";
    }

    class mainModel{

        /* ------ funcion para conectar a la bd ------ */
        #todos los modelos son protegidos y estaticos
        protected static function conectar(){
            $conexion = new PDO(SGBD, USER, PASS);
            $conexion->exec("SET CHARACTER SET utf8");#permitirnos usar Ñ y demás
            return $conexion;
        }

        /* ------ funcion ejecutar consultas simples ------ */
        protected static function consultar_consultas_simples($consulta){
            $sql = self::conectar()->prepare($consulta); #con self hacemos referencia a una funcion de la misma clase
            $sql -> execute(); #ejecutar la consulta
            return $sql;
        }

        /*-------- encriptar texto plano -------- */
        public function encryption($string){ #es publico porque se va a utilizar en algunas vistas
			$output=FALSE;
			$key=hash('sha256', SECRET_KEY);
			$iv=substr(hash('sha256', SECRET_IV), 0, 16);
			$output=openssl_encrypt($string, METHOD, $key, 0, $iv);
			$output=base64_encode($output);
			return $output;
		}

        /*-------- descencriptar lo que se encriptó -------- */
		protected static function decryption($string){
			$key=hash('sha256', SECRET_KEY);
			$iv=substr(hash('sha256', SECRET_IV), 0, 16);
			$output=openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
			return $output;
		}

        /*-------- funcion generar codigos aleatorios -------- */
        protected static function generar_codigo_aleatorios($letra,$longuitud,$numero){
			for ($i=1; $i <= $longuitud; $i++) { 
                $aleatorios = rand(0,9);
                $letra.=$aleatorios;
            }
            return $letra."-".$numero;
		}
        
        

        /*-------- funcion limpiar cadenas. Para evitar inyeccion sql etc. -------- */
        protected static function limpiar_cadenas($cadena){
			$cadena = trim($cadena); #trim() evita espacio antes o despues del texto
            $cadena = stripslashes($cadena); #stripslashes() elimina la barra invertida
            $cadena = str_ireplace("<script>", "", $cadena); #str_ireplace("texto a reemplazar", "reemplazar por este", "la cadena de texto") reemplza el texto
            $cadena = str_ireplace("</script>", "", $cadena);
            $cadena = str_ireplace("<script src>", "", $cadena);
            $cadena = str_ireplace("<script type=>", "", $cadena);
            $cadena = str_ireplace("SELECT * FROM", "", $cadena);
            $cadena = str_ireplace("DELETE FROM", "", $cadena);
            $cadena = str_ireplace("INSERT INTO", "", $cadena);
            $cadena = str_ireplace("DROP TABLE", "", $cadena);
            $cadena = str_ireplace("DROP DATABASE", "", $cadena);
            $cadena = str_ireplace("TRUNCATE TABLE", "", $cadena); #para evitar vaciar tablas
            $cadena = str_ireplace("SHOW TABLES", "", $cadena);
            $cadena = str_ireplace("SHOW DATABASES", "", $cadena);
            $cadena = str_ireplace("<?php", "", $cadena);
            $cadena = str_ireplace("?>", "", $cadena);
            $cadena = str_ireplace("--", "", $cadena);
            $cadena = str_ireplace(">", "", $cadena);
            $cadena = str_ireplace("<", "", $cadena);
            $cadena = str_ireplace("[", "", $cadena);
            $cadena = str_ireplace("]", "", $cadena);
            $cadena = str_ireplace("^", "", $cadena);
            $cadena = str_ireplace("==", "", $cadena);
            $cadena = str_ireplace(";", "", $cadena);
            $cadena = str_ireplace("::", "", $cadena);
            $cadena = stripslashes($cadena);
            $cadena = trim($cadena);

            return $cadena;
		}

        /*-------- verificar los datos -------- */
        protected static function verificar_datos($filtro, $cadena){ # $filtro caracteres permitidos
            #si la cadena coincide con el filtro, no tiene errores
            if (preg_match("/^".$filtro."$/",$cadena)) { # preg_match() compara la expresion regular
                #no tiene errores
                return false;
            }
            else{
                #tiene errores
                return true;
            }
        }

        /*-------- funcion verificar fechas -------- */
        protected static function verificar_fecha($fecha){
            $valores = explode('-', $fecha); #explode() separar cadena de caracteres mediante un caracter
            /* ej fecha en el input: 2024-09-21 (año-mes-dia)
                fecha en checkdate(mes, dia, año)
                por eso en el checkdate se pone esos indices: $valores[1], $valores[2], $valores[0]
            */
            if(count($valores) == 3 && checkdate($valores[1], $valores[2], $valores[0])) { # checkdate() comprueba la validez de una fecha
                #no tiene errores
                return false;
            }
            else{
                #tiene errores
                return true;
            }
        }

        /*-------- funcion paginador de tablas -------- */
        /* $pagina: pagina actual. $Npaginas: numeros de paginas(o ultima pagina). $url: para direccionar cada boton
        $botones: maximo de botones (1, 2, 3...) */
        protected static function paginador_tablas($pagina, $Npaginas, $url, $botones){
            $tabla = '<nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">';

                if ($pagina == 1) {
                    # si la pagina esta en 1, deshabilitamos el boton atras (disabled)
                    $tabla.= '
                        <li class="page-item disabled">
                            <a class="page-link"><i class="fa-solid fa-chevron-left"></i></a>
                        </li>
                    ';
                }
                else{
                    # si la pagina NO esta en 1, habilitamos el boton atras y colocamos la url
                    # colocamos otro boton anterior, y se le resta 1 a la pagina actual, para que vuelva atras
                    $tabla.= '
                        <li class="page-item">
                            <a class="page-link" href="'.$url.'1/"><i class="fa-solid fa-chevron-left"></i></a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="'.$url.($pagina-1).'/">Anterior</a>
                        </li>
                    ';                    
                }

                #contador de iteraciones
                $ci = 0;
                for ($i=$pagina; $i <= $Npaginas; $i++) {

                    # cuantos numeros de paginas (1, 2, 3) en el medio
                    if ($ci>=$botones) {
                        #se corta el ciclo
                        break;
                    }

                    #cuando estamos en la pagina actual, colocarle active, para que se coloree
                    if ($pagina==$i) {
                        $tabla.= '
                        <li class="page-item">
                            <a class="page-link active" href="'.$url.$i.'/">'.$i.'</a>
                        </li>
                    '; 
                    }
                    else{
                        #cuando NO estemos en la pagina actual, sacarle active
                        $tabla.= '
                        <li class="page-item">
                            <a class="page-link " href="'.$url.$i.'/">'.$i.'</a>
                        </li>
                    '; 
                    }
                    $ci++;
                }



                if ($pagina == $Npaginas) {
                    # si esta en la ultima pagina, deshabilitamos el boton siguiente (disabled)
                    $tabla.= '
                        <li class="page-item disabled">
                            <a class="page-link"><i class="fa-solid fa-chevron-right"></i></a>
                        </li>
                    ';
                }
                else{
                    # si NO esta en la ultima pagina, habilitamos el boton siguiente y colocamos la url
                    # colocamos otro boton siguiente, y se le suma 1 a la pagina actual, para que vuelva al siguiente
                    $tabla.= '
                        <li class="page-item">
                            <a class="page-link" href="'.$url.($pagina+1).'/">Siguiente</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="'.$url.$Npaginas.'/"><i class="fa-solid fa-chevron-right"></i></a>
                        </li>
                        
                    ';
                }

                
            $tabla.='</ul></nav>';
            return $tabla;
        }

    }