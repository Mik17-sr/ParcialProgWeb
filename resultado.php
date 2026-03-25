<?php
$salmin = 1750905;
$i = (int)$_REQUEST["i"];
$id = $_REQUEST["id"];
$nombre = $_REQUEST["name"];
$apell = $_REQUEST["apell"];
$email = $_REQUEST["email"];
$tel = $_REQUEST["tel"];
$fecnac = $_REQUEST["fecnac"];

$cargo = $_REQUEST["cargo"];
$cncost = $_REQUEST["cncost"];
$fecing = $_REQUEST["fecing"];
$nvlrg = $_REQUEST["nvlrg"];
$nvlrg_i = (float)$nvlrg[$i];
$nvl = 0;

if($nvlrg_i == 0.00522){
    $nvl = "I";
}else if($nvlrg_i == 0.01044){
    $nvl = "II";
}else if($nvlrg_i == 0.02436){
    $nvl = "III";
}else if($nvlrg_i == 0.04350){
    $nvl = "IV";
}else if($nvlrg_i == 0.06960){
    $nvl = "V";
}

$sueldb = $_REQUEST["sueldb"];
$sueldb_i = (float)$sueldb[$i];
$diaslab = $_REQUEST["diaslab"];
$diaslab_i = (int)$diaslab[$i];
$diasarl = $_REQUEST["diasarl"];
$diasarl_i = (int)$diasarl[$i];
$diasvac = $_REQUEST["diasvac"];
$diasvac_i = (int)$diasvac[$i];
$diaseps = $_REQUEST["diaseps"];
$diaseps_i = (int)$diaseps[$i];
$valdia = $sueldb_i / 30;

$auxtr = 0;
if($sueldb_i <= ($salmin * 2)){
  $auxtr = (249095/30) * $diaslab_i;
}

$salariosd = ($sueldb_i/30) * $diaslab_i;
$salariovac = $valdia * $diasvac_i;
$salarioarl = $valdia * $diasarl_i;
$salarioeps = $valdia * $diaseps_i;

function calcularHoras($horas, $tasa, $valhora){
  $valhoraextra = $valhora * $tasa;
  $horaextra = $valhoraextra * (float)$horas;
  return $horaextra;
}
$valhora = ($sueldb_i / 240);
$hrdi = $_REQUEST["hrdi"];
$hrediu = calcularHoras((float)$hrdi[$i], 1.25, $valhora);

$hrnoc = $_REQUEST["hrnoc"];
$hrenoc = calcularHoras((float)$hrnoc[$i], 1.75, $valhora);

$hrdomdi = $_REQUEST["hrdomdi"];
$hredomdi = calcularHoras((float)$hrdomdi[$i], 2, $valhora);

$hrdomnoc = $_REQUEST["hrdomnoc"];
$hredomnoc = calcularHoras((float)$hrdomnoc[$i], 2.5, $valhora);

$devengado = $salariosd + $auxtr + $salariovac + $salarioarl + $salarioeps + $hrediu + $hrenoc + $hredomdi + $hredomnoc;

$salud = $salariosd * 0.04;
$pension = $salariosd * 0.04;

$fondsol = 0;
if($sueldb_i > ($salmin * 2)){
  $fondsol = $salariosd * 0.01;
}

$deducciones = $salud + $pension + $fondsol;

$salude = $sueldb_i * 0.085;
$pensione = $sueldb_i * 0.12;
$arl = $sueldb_i * $nvlrg_i;
$caja = $sueldb_i * 0.04;
$sena = $sueldb_i * 0.02;
$icbf = $sueldb_i * 0.03;

$aportes = $salude + $pensione + $arl + $caja + $sena + $icbf;

$prima = $sueldb_i * 0.0833;
$cesantias = $sueldb_i * 0.0833;
$interesesces = $cesantias * $diaslab_i * 0.12 / 360;
$vacaciones = $sueldb_i * 0.0417;

$prestaciones = $prima + $cesantias + $interesesces + $vacaciones;

$neto = $devengado - $deducciones;
$total = $devengado + $aportes + $prestaciones;

require_once 'dompdf/vendor/autoload.php';

use Dompdf\Dompdf;


if (isset($_REQUEST["exportar"])) {
    error_reporting(E_ERROR);
    $options = new \Dompdf\Options();
    $options->set('isRemoteEnabled', false);
    $dompdf = new Dompdf($options);
    ob_start();
    ?>
    <style>
      body { font-family: Arial, sans-serif; font-size: 11px; color: #111111; }
      h1 { font-size: 20px; text-align: center; margin-bottom: 4px; }
      .subtitle { text-align: center; color: #888888; margin-bottom: 16px; font-size: 10px; }
      
      .employee-info { width: 100%; margin-bottom: 16px; border: 1px solid #d0d0d0; }
      .info-item { display: inline-block; width: 48%; padding: 8px 10px; vertical-align: top; }
      .info-label { font-size: 9px; color: #888888; text-transform: uppercase; display: block; }
      .info-value { font-weight: bold; font-size: 11px; }

      .section-title { background: #111111; color: #ffffff; padding: 6px 10px; font-size: 11px; font-weight: bold; margin-top: 14px; margin-bottom: 0; }

      table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
      th { background: #f2f2f2; padding: 6px 10px; text-align: left; font-size: 10px; border-bottom: 2px solid #d0d0d0; }
      td { padding: 6px 10px; border-bottom: 1px solid #eeeeee; font-size: 11px; }
      .num { text-align: right; }

      .total-row td { font-weight: bold; background: #111111; color: #ffffff; padding: 7px 10px; }
      .highlight { color: #ffffff; }
      .highlight-red { color: #ff6b6b; }

      .neto-box { width: 100%; border-left: 6px solid #111111; padding: 12px 16px; margin-top: 14px; }
      .neto-label { font-size: 16px; font-weight: bold; }
      .neto-sub { font-size: 9px; color: #888888; text-transform: uppercase; }
      .neto-amount { font-size: 22px; font-weight: bold; }
      .total-amount { font-size: 11px; color: #888888; }
    </style>
    <div class="card header-card">
      <div class="logo"><span></span></div>
      <h1>RESULTADO DE NÓMINA</h1>
      <p class="subtitle">Período: <strong>Marzo 2026</strong></p>

      <div class="employee-info">
        <div class="info-item">
          <span class="info-label">Empleado</span>
          <span class="info-value"><?php echo $nombre[$i]. " " . $apell[$i]?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Identificación</span>
          <span class="info-value"><?php echo $id[$i]?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Email</span>
          <span class="info-value"><?php echo $email[$i]?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Telefono</span>
          <span class="info-value"><?php echo $tel[$i]?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Fecha de Nacimiento|</span>
          <span class="info-value"><?php echo $fecnac[$i]?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Cargo</span>
          <span class="info-value"><?php echo $cargo[$i]?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Centro de Costos</span>
          <span class="info-value"><?php echo $cncost[$i]?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Fecha de Ingreso</span>
          <span class="info-value"><?php echo $fecing[$i]?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Nivel ARL</span>
          <span class="info-value badge"><?php echo $nvl. " — " .((float)($nvlrg[$i])*100)."%"?></span>
        </div>
      </div>
    </div>
    <div class="tables-grid">
      <div class="card">
        <div class="card-header devengados">
          <span class="card-tag">01</span>
          <h2>DEVENGADOS</h2>
        </div>
        <table class="nomina-table">
          <thead>
            <tr>
              <th>Concepto</th>
              <th class="num">Días / Horas</th>
              <th class="num">Valor</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Salario Base</td>
              <td class="num">30</td>
              <td class="num">$<?php echo number_format($sueldb[$i])?></td>
            </tr>
            <tr>
              <td>Salario Según Días Laborados</td>
              <td class="num"><?php echo $diaslab[$i]?></td>
              <td class="num">$<?php echo number_format($salariosd)?></td>
            </tr>
            <tr>
              <td>Auxilio de Transporte</td>
              <td class="num"><?php if($auxtr > 0){echo $diaslab[$i];}else{ echo '—';}?></td>
              <td class="num"><?php if($auxtr > 0){echo '$'.number_format($auxtr);}else{ echo 'NO APLICA';}?></td>
            </tr>
            <?php if($hrdi[$i] > 0 ){?>
            <tr>
              <td>Horas Extra Diurnas</td>
              <td class="num"><?php echo $hrdi[$i]?></td>
              <td class="num">$<?php echo number_format($hrediu)?></td>
            </tr>
            <?php
            }
            ?>
            <?php if($hrnoc[$i] > 0){?>
            <tr>
              <td>Horas Extra Nocturnas</td>
              <td class="num"><?php echo $hrnoc[$i]?></td>
              <td class="num">$<?php echo number_format($hrenoc)?></td>
            </tr>
            <?php
            }
            ?>
            <?php if($hrdomdi[$i] > 0){?>
            <tr>
              <td>Horas Extra Diurnas Dominical/Festiva</td>
              <td class="num"><?php echo $hrdomdi[$i]?></td>
              <td class="num">$<?php echo number_format($hredomdi)?></td>
            </tr>
            <?php
            }
            ?>
            <?php if($hrdomnoc[$i] > 0) {?>
            <tr>
              <td>Horas Extra Nocturnas Dominical/Festiva</td>
              <td class="num"><?php echo $hrdomnoc[$i]?></td>
              <td class="num">$<?php echo number_format($hredomnoc)?></td>
            </tr>
            <?php
            }
            ?>
            <?php if($diasarl[$i] > 0){
            ?>
            <tr>
              <td>Pago incapacidad ARL</td>
              <td class="num"><?php echo $diasarl[$i]?></td>
              <td class="num">$<?php echo number_format($salarioarl) ?></td>
            </tr>
            <?php
            }
            ?>
            <?php if($diaseps[$i] > 0){
            ?>
            <tr>
              <td>Pago incapacidad EPS</td>
              <td class="num"><?php echo $diaseps[$i]?></td>
              <td class="num">$<?php echo number_format($salarioeps) ?></td>
            </tr>
            <?php
            }
            ?>
            <?php if($diasvac[$i] > 0){
            ?>
            <tr>
              <td>Vacaciones Disfrutadas</td>
              <td class="num"><?php echo $diasvac[$i]?></td>
              <td class="num">$<?php echo number_format($salariovac)?></td>
            </tr>
            <?php
            }
            ?>
          </tbody>
          <tfoot>
            <tr class="total-row">
              <td colspan="2">TOTAL DEVENGADO</td>
              <td class="num highlight">$<?php echo number_format($devengado)?></td>
            </tr>
          </tfoot>
        </table>
      </div>
      <div class="card">
        <div class="card-header deducciones">
          <span class="card-tag">02</span>
          <h2>DEDUCCIONES</h2>
        </div>
        <table class="nomina-table">
          <thead>
            <tr>
              <th>Concepto</th>
              <th class="num">%</th>
              <th class="num">Valor</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Salud (Empleado)</td>
              <td class="num">4%</td>
              <td class="num">$<?php echo number_format($salud)?></td>
            </tr>
            <tr>
              <td>Pensión (Empleado)</td>
              <td class="num">4%</td>
              <td class="num">$<?php echo number_format($pension)?></td>
            </tr>
            <?php if($fondsol > 0 ){
            ?>
            <tr>
              <td>Fondo solidaridad Pensional</td>
              <td class="num">1%</td>
              <td class="num">$<?php echo number_format($fondsol)?></td>
            </tr>
            <?php
            }
            ?>
            <tr class="empty-row"><td colspan="3"></td></tr>
            <tr class="empty-row"><td colspan="3"></td></tr>
          </tbody>
          <tfoot>
            <tr class="total-row deduct">
              <td colspan="2">TOTAL DEDUCCIONES</td>
              <td class="num highlight-red">$<?php echo number_format($deducciones)?></td>
            </tr>
          </tfoot>
        </table>
      </div>
      <div class="card">
        <div class="card-header aportes">
          <span class="card-tag">03</span>
          <h2>APORTES EMPRESA</h2>
        </div>
        <table class="nomina-table">
          <thead>
            <tr>
              <th>Concepto</th>
              <th class="num">%</th>
              <th class="num">Valor</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Salud (Empresa)</td>
              <td class="num">8.5%</td>
              <td class="num">$<?php echo number_format($salude)?></td>
            </tr>
            <tr>
              <td>Pensión (Empresa)</td>
              <td class="num">12%</td>
              <td class="num">$<?php echo number_format($pensione)?></td>
            </tr>
            <tr>
              <td>ARL — <?php echo $nvl ?></td>
              <td class="num"><?php echo ((float)($nvlrg[$i]) * 100)?>%</td>
              <td class="num"><?php echo number_format($arl)?></td>
            </tr>
            <tr>
              <td>Caja de Compensación</td>
              <td class="num">4%</td>
              <td class="num">$<?php echo number_format($caja)?></td>
            </tr>
            <tr>
              <td>SENA</td>
              <td class="num">2%</td>
              <td class="num">$<?php echo number_format($sena)?></td>
            </tr>
            <tr>
              <td>ICBF</td>
              <td class="num">3%</td>
              <td class="num">$<?php echo number_format($icbf)?></td>
            </tr>
          </tbody>
          <tfoot>
            <tr class="total-row">
              <td colspan="2">TOTAL APORTES</td>
              <td class="num highlight">$<?php echo number_format($aportes) ?></td>
            </tr>
          </tfoot>
        </table>
      </div>
      <div class="card">
        <div class="card-header provisiones">
          <span class="card-tag">04</span>
          <h2>PRESTACIONES SOCIALES</h2>
        </div>
        <table class="nomina-table">
          <thead>
            <tr>
              <th>Concepto</th>
              <th class="num">%</th>
              <th class="num">Valor</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Prima</td>
              <td class="num">8.33%</td>
              <td class="num">$<?php echo number_format($prima) ?></td>
            </tr>
            <tr>
              <td>Cesantías</td>
              <td class="num">8.33%</td>
              <td class="num">$<?php echo number_format($cesantias)?></td>
            </tr>
            <tr>
              <td>Int. sobre Cesantías</td>
              <td class="num">12%</td>
              <td class="num">$<?php echo number_format($interesesces)?></td>
            </tr>
            <tr>
              <td>Vacaciones</td>
              <td class="num">4.17%</td>
              <td class="num">$<?php echo number_format($vacaciones)?></td>
            </tr>
            <tr class="empty-row"><td colspan="3"></td></tr>
            <tr class="empty-row"><td colspan="3"></td></tr>
          </tbody>
          <tfoot>
            <tr class="total-row">
              <td colspan="2">TOTAL PRESTACIONES SOCIALES</td>
              <td class="num highlight">$<?php echo number_format($prestaciones) ?></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
    <div class="neto-box">
      <p class="neto-label">NETO A PAGAR</p>
      <p class="neto-sub">Total devengado — Total deducciones</p>
      <p class="neto-amount">$<?php echo number_format($neto) ?></p>
      <p class="total-amount">Costo empresa: $<?php echo number_format($total) ?></p>
    </div>
    <?php
    $html = ob_get_clean();

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream('nomina_'. $nombre[$i]. '_'.$apell[$i].'.pdf' , ['Attachment' => true]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Resultado Nómina</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="resultado.css">
</head>
<body>
  <div class="page-wrapper">
    <div class="card header-card">
      <div class="logo"><span></span></div>
      <h1>RESULTADO DE NÓMINA</h1>
      <p class="subtitle">Período: <strong>Marzo 2026</strong></p>

      <div class="employee-info">
        <div class="info-item">
          <span class="info-label">Empleado</span>
          <span class="info-value"><?php echo $nombre[$i]. " " . $apell[$i]?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Identificación</span>
          <span class="info-value"><?php echo $id[$i]?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Email</span>
          <span class="info-value"><?php echo $email[$i]?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Telefono</span>
          <span class="info-value"><?php echo $tel[$i]?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Fecha de Nacimiento|</span>
          <span class="info-value"><?php echo $fecnac[$i]?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Cargo</span>
          <span class="info-value"><?php echo $cargo[$i]?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Centro de Costos</span>
          <span class="info-value"><?php echo $cncost[$i]?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Fecha de Ingreso</span>
          <span class="info-value"><?php echo $fecing[$i]?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Nivel ARL</span>
          <span class="info-value badge"><?php echo $nvl. " — " .((float)($nvlrg[$i])*100)."%"?></span>
        </div>
      </div>
    </div>
    <div class="tables-grid">
      <div class="card">
        <div class="card-header devengados">
          <span class="card-tag">01</span>
          <h2>DEVENGADOS</h2>
        </div>
        <table class="nomina-table">
          <thead>
            <tr>
              <th>Concepto</th>
              <th class="num">Días / Horas</th>
              <th class="num">Valor</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Salario Base</td>
              <td class="num">30</td>
              <td class="num">$<?php echo number_format($sueldb[$i])?></td>
            </tr>
            <tr>
              <td>Salario Según Días Laborados</td>
              <td class="num"><?php echo $diaslab[$i]?></td>
              <td class="num">$<?php echo number_format($salariosd)?></td>
            </tr>
            <tr>
              <td>Auxilio de Transporte</td>
              <td class="num"><?php if($auxtr > 0){echo $diaslab[$i];}else{ echo '—';}?></td>
              <td class="num"><?php if($auxtr > 0){echo '$'.number_format($auxtr);}else{ echo 'NO APLICA';}?></td>
            </tr>
            <?php if($hrdi[$i] > 0 ){?>
            <tr>
              <td>Horas Extra Diurnas</td>
              <td class="num"><?php echo $hrdi[$i]?></td>
              <td class="num">$<?php echo number_format($hrediu)?></td>
            </tr>
            <?php
            }
            ?>
            <?php if($hrnoc[$i] > 0){?>
            <tr>
              <td>Horas Extra Nocturnas</td>
              <td class="num"><?php echo $hrnoc[$i]?></td>
              <td class="num">$<?php echo number_format($hrenoc)?></td>
            </tr>
            <?php
            }
            ?>
            <?php if($hrdomdi[$i] > 0){?>
            <tr>
              <td>Horas Extra Diurnas Dominical/Festiva</td>
              <td class="num"><?php echo $hrdomdi[$i]?></td>
              <td class="num">$<?php echo number_format($hredomdi)?></td>
            </tr>
            <?php
            }
            ?>
            <?php if($hrdomnoc[$i] > 0) {?>
            <tr>
              <td>Horas Extra Nocturnas Dominical/Festiva</td>
              <td class="num"><?php echo $hrdomnoc[$i]?></td>
              <td class="num">$<?php echo number_format($hredomnoc)?></td>
            </tr>
            <?php
            }
            ?>
            <?php if($diasarl[$i] > 0){
            ?>
            <tr>
              <td>Pago incapacidad ARL</td>
              <td class="num"><?php echo $diasarl[$i]?></td>
              <td class="num">$<?php echo number_format($salarioarl) ?></td>
            </tr>
            <?php
            }
            ?>
            <?php if($diaseps[$i] > 0){
            ?>
            <tr>
              <td>Pago incapacidad EPS</td>
              <td class="num"><?php echo $diaseps[$i]?></td>
              <td class="num">$<?php echo number_format($salarioeps) ?></td>
            </tr>
            <?php
            }
            ?>
            <?php if($diasvac[$i] > 0){
            ?>
            <tr>
              <td>Vacaciones Disfrutadas</td>
              <td class="num"><?php echo $diasvac[$i]?></td>
              <td class="num">$<?php echo number_format($salariovac)?></td>
            </tr>
            <?php
            }
            ?>
          </tbody>
          <tfoot>
            <tr class="total-row">
              <td colspan="2">TOTAL DEVENGADO</td>
              <td class="num highlight">$<?php echo number_format($devengado)?></td>
            </tr>
          </tfoot>
        </table>
      </div>
      <div class="card">
        <div class="card-header deducciones">
          <span class="card-tag">02</span>
          <h2>DEDUCCIONES</h2>
        </div>
        <table class="nomina-table">
          <thead>
            <tr>
              <th>Concepto</th>
              <th class="num">%</th>
              <th class="num">Valor</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Salud (Empleado)</td>
              <td class="num">4%</td>
              <td class="num">$<?php echo number_format($salud)?></td>
            </tr>
            <tr>
              <td>Pensión (Empleado)</td>
              <td class="num">4%</td>
              <td class="num">$<?php echo number_format($pension)?></td>
            </tr>
            <?php if($fondsol > 0 ){
            ?>
            <tr>
              <td>Fondo solidaridad Pensional</td>
              <td class="num">1%</td>
              <td class="num">$<?php echo number_format($fondsol)?></td>
            </tr>
            <?php
            }
            ?>
            <tr class="empty-row"><td colspan="3"></td></tr>
            <tr class="empty-row"><td colspan="3"></td></tr>
          </tbody>
          <tfoot>
            <tr class="total-row deduct">
              <td colspan="2">TOTAL DEDUCCIONES</td>
              <td class="num highlight-red">$<?php echo number_format($deducciones)?></td>
            </tr>
          </tfoot>
        </table>
      </div>
      <div class="card">
        <div class="card-header aportes">
          <span class="card-tag">03</span>
          <h2>APORTES EMPRESA</h2>
        </div>
        <table class="nomina-table">
          <thead>
            <tr>
              <th>Concepto</th>
              <th class="num">%</th>
              <th class="num">Valor</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Salud (Empresa)</td>
              <td class="num">8.5%</td>
              <td class="num">$<?php echo number_format($salude)?></td>
            </tr>
            <tr>
              <td>Pensión (Empresa)</td>
              <td class="num">12%</td>
              <td class="num">$<?php echo number_format($pensione)?></td>
            </tr>
            <tr>
              <td>ARL — <?php echo $nvl ?></td>
              <td class="num"><?php echo ((float)($nvlrg[$i]) * 100)?>%</td>
              <td class="num"><?php echo number_format($arl)?></td>
            </tr>
            <tr>
              <td>Caja de Compensación</td>
              <td class="num">4%</td>
              <td class="num">$<?php echo number_format($caja)?></td>
            </tr>
            <tr>
              <td>SENA</td>
              <td class="num">2%</td>
              <td class="num">$<?php echo number_format($sena)?></td>
            </tr>
            <tr>
              <td>ICBF</td>
              <td class="num">3%</td>
              <td class="num">$<?php echo number_format($icbf)?></td>
            </tr>
          </tbody>
          <tfoot>
            <tr class="total-row">
              <td colspan="2">TOTAL APORTES</td>
              <td class="num highlight">$<?php echo number_format($aportes) ?></td>
            </tr>
          </tfoot>
        </table>
      </div>
      <div class="card">
        <div class="card-header provisiones">
          <span class="card-tag">04</span>
          <h2>PRESTACIONES SOCIALES</h2>
        </div>
        <table class="nomina-table">
          <thead>
            <tr>
              <th>Concepto</th>
              <th class="num">%</th>
              <th class="num">Valor</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Prima</td>
              <td class="num">8.33%</td>
              <td class="num">$<?php echo number_format($prima) ?></td>
            </tr>
            <tr>
              <td>Cesantías</td>
              <td class="num">8.33%</td>
              <td class="num">$<?php echo number_format($cesantias)?></td>
            </tr>
            <tr>
              <td>Int. sobre Cesantías</td>
              <td class="num">12%</td>
              <td class="num">$<?php echo number_format($interesesces)?></td>
            </tr>
            <tr>
              <td>Vacaciones</td>
              <td class="num">4.17%</td>
              <td class="num">$<?php echo number_format($vacaciones)?></td>
            </tr>
            <tr class="empty-row"><td colspan="3"></td></tr>
            <tr class="empty-row"><td colspan="3"></td></tr>
          </tbody>
          <tfoot>
            <tr class="total-row">
              <td colspan="2">TOTAL PRESTACIONES SOCIALES</td>
              <td class="num highlight">$<?php echo number_format($prestaciones) ?></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
    <div class="card neto-card">
      <div class="neto-left">
        <p class="neto-label">NETO A PAGAR</p>
        <p class="neto-sub">Total devengado — Total deducciones</p>
      </div>
      <div class="neto-right">
        <p class="neto-amount" id="netoAmount">$<?php echo number_format($neto) ?></p>
        <p class="total-amount" id="totalAmount">$<?php echo number_format($total)?></p>
      </div>
    </div>
    <div class="actions">
      <form action="form.php" method="post" style="display:inline">
        <input type="hidden" name="i" value="<?= $i ?>">
        <?php for ($j = 0; $j < count($id); $j++) { ?>
          <input type="hidden" name="id[]"       value="<?= $id[$j] ?>">
          <input type="hidden" name="name[]"     value="<?= $nombre[$j] ?>">
          <input type="hidden" name="apell[]"    value="<?= $apell[$j] ?>">
          <input type="hidden" name="email[]"    value="<?= $email[$j] ?>">
          <input type="hidden" name="tel[]"      value="<?= $tel[$j] ?>">
          <input type="hidden" name="fecnac[]"   value="<?= $fecnac[$j] ?>">
          <input type="hidden" name="cargo[]"    value="<?= $cargo[$j] ?>">
          <input type="hidden" name="cncost[]"   value="<?= $cncost[$j] ?>">
          <input type="hidden" name="fecing[]"   value="<?= $fecing[$j] ?>">
          <input type="hidden" name="nvlrg[]"    value="<?= $nvlrg[$j] ?>">
          <input type="hidden" name="sueldb[]"   value="<?= $sueldb[$j] ?>">
          <input type="hidden" name="diaslab[]"  value="<?= $diaslab[$j] ?>">
          <input type="hidden" name="diasarl[]"  value="<?= $diasarl[$j] ?>">
          <input type="hidden" name="diasvac[]"  value="<?= $diasvac[$j] ?>">
          <input type="hidden" name="diaseps[]"  value="<?= $diaseps[$j] ?>">
          <input type="hidden" name="hrdi[]"     value="<?= $hrdi[$j] ?>">
          <input type="hidden" name="hrnoc[]"    value="<?= $hrnoc[$j] ?>">
          <input type="hidden" name="hrdomdi[]"  value="<?= $hrdomdi[$j] ?>">
          <input type="hidden" name="hrdomnoc[]" value="<?= $hrdomnoc[$j] ?>">
        <?php } ?>
        <button type="submit" class="btn btn-outline">REGISTRAR OTRO EMPLEADO</button>
      </form>
      <form action="resultado.php" method="post" style="display:inline">
        <input type="hidden" name="exportar" value="1">
        <input type="hidden" name="i" value="0">
        <input type="hidden" name="id[]"       value="<?= $id[$i] ?>">
        <input type="hidden" name="name[]"     value="<?= $nombre[$i] ?>">
        <input type="hidden" name="apell[]"    value="<?= $apell[$i] ?>">
        <input type="hidden" name="email[]"    value="<?= $email[$i] ?>">
        <input type="hidden" name="tel[]"      value="<?= $tel[$i] ?>">
        <input type="hidden" name="fecnac[]"   value="<?= $fecnac[$i] ?>">
        <input type="hidden" name="cargo[]"    value="<?= $cargo[$i] ?>">
        <input type="hidden" name="cncost[]"   value="<?= $cncost[$i] ?>">
        <input type="hidden" name="fecing[]"   value="<?= $fecing[$i] ?>">
        <input type="hidden" name="nvlrg[]"    value="<?= $nvlrg[$i] ?>">
        <input type="hidden" name="sueldb[]"   value="<?= $sueldb[$i] ?>">
        <input type="hidden" name="diaslab[]"  value="<?= $diaslab[$i] ?>">
        <input type="hidden" name="diasarl[]"  value="<?= $diasarl[$i] ?>">
        <input type="hidden" name="diasvac[]"  value="<?= $diasvac[$i] ?>">
        <input type="hidden" name="diaseps[]"  value="<?= $diaseps[$i] ?>">
        <input type="hidden" name="hrdi[]"     value="<?= $hrdi[$i] ?>">
        <input type="hidden" name="hrnoc[]"    value="<?= $hrnoc[$i] ?>">
        <input type="hidden" name="hrdomdi[]"  value="<?= $hrdomdi[$i] ?>">
        <input type="hidden" name="hrdomnoc[]" value="<?= $hrdomnoc[$i] ?>">
        <button type="submit" class="btn btn-solid">EXPORTAR PDF</button>
      </form>
    </div>
  </div>
</body>
<script src="resultado.js"></script>
</html>