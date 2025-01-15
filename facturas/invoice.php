<?php

	$peticionAjax = true;
	require_once "../config/app.php";

	$id = (isset($_GET['id'])) ? $_GET['id'] : 0;

	  /* ---------- instancia al controlador ----------- */ 
	require_once "../controladores/pagosControlador.php";
	$ins_pagos = new pagosControlador(); #se instancia al pagos controlador

	$datos_pagos= $ins_pagos->datos_pagos_controlador("Reporte", $id);
	if ($datos_pagos -> rowCount()==1) {
		$datos_pagos = $datos_pagos->fetch();
	
	  /* ---------- instancia al controlador empresa (si tiene) ----------- */ 
		/*require_once "../controladores/empresasControlador.php";
		$ins_empresas = new empresasControlador(); #se instancia al pagos controlador
		
		$datos_empresas = $ins_empresas -> datos_empresas_controlador();
		$datos_empresas = $datos_empresas -> fetch();*/

		/* ---------- instancia al controlador usuarios ----------- */ 
		require_once "../controladores/usuarioControlador.php";
		$ins_usuario = new usuarioControlador(); #se instancia al pagos controlador
		
		$datos_usuario = $ins_usuario -> datos_usuario_controlador("Unico", $ins_usuario ->encryption($datos_pagos['usuario_id']));
		$datos_usuario = $datos_usuario -> fetch();


		/* ESTA MALLL */
		/* ---------- instancia al estudiantes ----------- */ 
		require_once "../controladores/estudiantesControlador.php";
		$ins_estudiantes = new estudiantesControlador(); #se instancia al pagos controlador
		
		$datos_estudiantes = $ins_estudiantes -> datos_estudiantes_controlador("Unico", $ins_estudiantes->encryption($datos_pagos['estudiantes_id']));
		$datos_estudiantes = $datos_estudiantes -> fetch();


		/* ---------- instancia a inscripciones ----------- */ 
		/*require_once "../controladores/inscripcionesControlador.php";
		$ins_inscripciones = new inscripcionesControlador(); #se instancia al pagos controlador
		
		$datos_inscripciones = $ins_inscripciones -> datos_inscripciones_controlador("Unico", $ins_inscripciones ->encryption($datos_pagos['inscripciones_codigo']));
		$datos_inscripciones = $datos_inscripciones -> fetch();*/




		require "./fpdf.php";

		$pdf = new FPDF('P','mm','Letter');
		$pdf->SetMargins(17,17,17);
		$pdf->AddPage();
		$pdf->Image('../vistas/assets/img/logo.png',10,10,30,30,'PNG');

		$pdf->SetFont('Arial','B',18);
		$pdf->SetTextColor(0,107,181);
		$pdf->Cell(0,10,utf8_decode(strtoupper("CITIC")),0,0,'C');
		$pdf->SetFont('Arial','',12);
		$pdf->SetTextColor(33,33,33);
		$pdf->Cell(-35,10,utf8_decode('N. de factura'),'',0,'C');

		$pdf->Ln(10);

		$pdf->SetFont('Arial','',15);
		$pdf->SetTextColor(0,107,181);
		$pdf->Cell(0,10,utf8_decode(""),0,0,'C');
		$pdf->SetFont('Arial','',12);
		$pdf->SetTextColor(97,97,97);
		$pdf->Cell(-35,10,utf8_decode($datos_pagos['pagos_id']),'',0,'C');

		$pdf->Ln(25);

		$pdf->SetTextColor(33,33,33);
		$pdf->Cell(36,8,utf8_decode('Fecha de emisión:'),0,0);
		$pdf->SetTextColor(97,97,97);
		$pdf->Cell(27,8,utf8_decode(date("d/m/Y", strtotime($datos_pagos['pagos_fecha']))),0,0);
		$pdf->Ln(8);
		$pdf->SetTextColor(33,33,33);
		$pdf->Cell(27,8,utf8_decode('Atendido por: '),"",0,0);
		$pdf->SetTextColor(97,97,97);
		$pdf->Cell(13,8,utf8_decode($datos_usuario['usuario_nombre']." ".$datos_usuario['usuario_apellido']),0,0);

		$pdf->Ln(15);

		$pdf->SetFont('Arial','',12);
		$pdf->SetTextColor(33,33,33);
		$pdf->Cell(15,8,utf8_decode('Cliente:'),0,0);
		$pdf->SetTextColor(97,97,97);
		$pdf->Cell(65,8,utf8_decode($datos_estudiantes['estudiantes_nombre']." ".$datos_estudiantes['estudiantes_apellido']),0,0);
		$pdf->SetTextColor(33,33,33);
		$pdf->Cell(10,8,utf8_decode('DNI:'),0,0);
		$pdf->SetTextColor(97,97,97);
		$pdf->Cell(40,8,utf8_decode($datos_estudiantes['estudiantes_dni']),0,0);
		$pdf->SetTextColor(33,33,33);
		$pdf->Cell(19,8,utf8_decode('Teléfono:'),0,0);
		$pdf->SetTextColor(97,97,97);
		$pdf->Cell(35,8,utf8_decode($datos_estudiantes['estudiantes_telefono']),0,0);
		$pdf->SetTextColor(33,33,33);

		$pdf->Ln(8);

		$pdf->Cell(20,8,utf8_decode('Dirección:'),0,0);
		$pdf->SetTextColor(97,97,97);
		$pdf->Cell(109,8,utf8_decode($datos_estudiantes['estudiantes_direccion']),0,0);

		$pdf->Ln(15);

		$pdf->SetFillColor(38,198,208);
		$pdf->SetDrawColor(38,198,208);
		$pdf->SetTextColor(33,33,33);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(15,10,utf8_decode('Cant.'),1,0,'C',true);
		$pdf->Cell(90,10,utf8_decode('Descripción'),1,0,'C',true);
		$pdf->Cell(51,10,utf8_decode('Precio'),1,0,'C',true);
		$pdf->Cell(25,10,utf8_decode('Subtotal'),1,0,'C',true);

		$pdf->Ln(10);

		$pdf->SetTextColor(97,97,97);


		
		/* ---------- instancia al inscripciones ----------- */ 
		/*require_once "../controladores/pagosControlador.php";
		$ins_pagos_2 = new pagosControlador(); #se instancia al pagos controlador

		$datos_pagos_2 = $ins_pagos_2 -> datos_pagos_controlador("Unico", $id);
		$datos_pagos_2 = $datos_pagos_2 -> fetchAll();


		$total = 0;

		foreach($datos_pagos_2 as $items){

			//$subtotal = $items['detalle_cantidad'] * ($items['detalle_costo']);
			//$subtotal= number_format($subtotal,2,'.',''); 

			$total = $items['pagos_monto'];


			$pdf->Cell(15,10,utf8_decode(1),'L',0,'C');
			$pdf->Cell(90,10,utf8_decode("Pago de inscripción al curso de ".$items['pagos_monto']),'L',0,'C');
			$pdf->Cell(51,10,utf8_decode(MONEDA.$items['pagos_monto']),'L',0,'C');
			$pdf->Cell(25,10,utf8_decode(MONEDA.$total),'LR',0,'C');
			$pdf->Ln(10);

			//$total += $subtotal;
		}*/



		/* ---------- instancia al inscripciones ----------- */ 
		require_once "../controladores/pagosControlador.php";
		$ins_pagos_2 = new pagosControlador(); #se instancia al pagos controlador

		$datos_pagos_2 = $ins_pagos_2 -> datos_pagos_controlador("Reporte", $id);
		$datos_pagos_2 = $datos_pagos_2 -> fetchAll();

		$total = 0;

		foreach($datos_pagos_2 as $items){

			/*$subtotal = $items['detalle_cantidad'] * ($items['detalle_costo']);
			$subtotal= number_format($subtotal,2,'.',''); */

			$total = $items['pagos_monto'];

			$pdf->Cell(15,10,utf8_decode(1),'L',0,'C');
			$pdf->Cell(90,10,utf8_decode("Pago de inscripción al curso de ".$items['cursos_nombre']),'L',0,'C');
			$pdf->Cell(51,10,utf8_decode(MONEDA." ".number_format($items['pagos_monto'], 0, ',', '.')),'L',0,'C');
			$pdf->Cell(25,10,utf8_decode(MONEDA." ".number_format($total, 0, ',', '.')),'LR',0,'C');
			$pdf->Ln(10);

			//$total += $subtotal;
		}



		$pdf->SetTextColor(33,33,33);
		$pdf->Cell(15,10,utf8_decode(''),'T',0,'C');
		$pdf->Cell(90,10,utf8_decode(''),'T',0,'C');
		$pdf->Cell(51,10,utf8_decode('TOTAL'),'LTB',0,'C');
		$pdf->Cell(25,10,utf8_decode(MONEDA." ".number_format($total, 0, ',', '.')),'LRTB',0,'C');

		$pdf->Ln(15);

		$pdf->MultiCell(0,9,utf8_decode("OBSERVACIÓN: ".$datos_pagos['pagos_observacion']),0,'J',false);

		$pdf->SetFont('Arial','',12);

		/*if($datos_pagos['pagos_monto'] < $datos_inscripciones['inscripciones_costo']){
			$pdf->Ln(12);

			$pdf->SetTextColor(97,97,97);
			$pdf->MultiCell(0,9,utf8_decode("NOTA IMPORTANTE: \nEsta factura presenta un saldo pendiente de pago por la cantidad de $.00"),0,'J',false);
		}*/

		$pdf->Ln(25);

		/*----------  INFO. EMPRESA  ----------*/
		/*$pdf->SetFont('Arial','B',9);
		$pdf->SetTextColor(33,33,33);
		$pdf->Cell(0,6,utf8_decode("NOMBRE DE LA EMPRESA"),0,0,'C');
		$pdf->Ln(6);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(0,6,utf8_decode("DIRECCION DE LA EMPRESA"),0,0,'C');
		$pdf->Ln(6);
		$pdf->Cell(0,6,utf8_decode("Teléfono: "),0,0,'C');
		$pdf->Ln(6);
		$pdf->Cell(0,6,utf8_decode("Correo: "),0,0,'C');*/


		$pdf->Output("I","Factura_1.pdf",true);
	}
	else{
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title> <?php echo COMPANY; ?></title>
		<!-- incluir las vistas los links para boostrap, css, etc. -->
		<?php include "../vistas/inc/link.php"; ?>
</head>
<body>

	<div class="full-box container-404">
		<div>
			<p class="text-center"><i class="fas fa-rocket fa-10x"></i></p>
			<h1 class="text-center">Ocurrió un error</h1>
			<p class="lead text-center">No se ha encontrado el pago seleccionado</p>
		</div>
	</div>



<?php	//incluir javaScript
	include "../vistas/inc/Script.php";  ?>

</body>
</html>


<?php	} ?>