<?php
$ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : [];
$names = isset($_REQUEST['name']) ? $_REQUEST['name'] : [];
$apells = isset($_REQUEST['apell']) ? $_REQUEST['apell'] : [];
$emails = isset($_REQUEST['email']) ? $_REQUEST['email'] : [];
$tels = isset($_REQUEST['tel']) ? $_REQUEST['tel'] : [];
$fecnacs = isset($_REQUEST['fecnac']) ? $_REQUEST['fecnac'] : [];
$cargos = isset($_REQUEST['cargo']) ? $_REQUEST['cargo'] : [];
$cncosts = isset($_REQUEST['cncost']) ? $_REQUEST['cncost'] : [];
$fecings = isset($_REQUEST['fecing']) ? $_REQUEST['fecing'] : [];
$nvlrgs = isset($_REQUEST['nvlrg']) ? $_REQUEST['nvlrg'] : [];
$sueldos = isset($_REQUEST['sueldb']) ? $_REQUEST['sueldb'] : [];
$diaslabs = isset($_REQUEST['diaslab']) ? $_REQUEST['diaslab'] : [];
$diasarls = isset($_REQUEST['diasarl']) ? $_REQUEST['diasarl'] : [];
$diasvacs = isset($_REQUEST['diasvac']) ? $_REQUEST['diasvac'] : [];
$diasepss = isset($_REQUEST['diaseps']) ? $_REQUEST['diaseps'] : [];
$hrdis = isset($_REQUEST['hrdi']) ? $_REQUEST['hrdi'] : [];
$hrnocs = isset($_REQUEST['hrnoc']) ? $_REQUEST['hrnoc'] : [];
$hrdomdis = isset($_REQUEST['hrdomdi']) ? $_REQUEST['hrdomdi'] : [];
$hrdomnocs = isset($_REQUEST['hrdomnoc']) ? $_REQUEST['hrdomnoc'] : [];
$i = isset($_REQUEST['i']) ? (int)$_REQUEST['i'] : null;
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cálculo de Nómina</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="card">
    <div class="logo"><span></span></div>
    <h1>NÓMINA</h1>
    <p class="subtitle">Cálculo de Nómina de empleado</p>
    <form id="payrollForm" action="resultado.php" method="post">
      <?php for ($j = 0; $j < count($ids); $j++) { ?>
        <input type="hidden" name="id[]"       value="<?= $ids[$j] ?>">
        <input type="hidden" name="name[]"     value="<?= $names[$j] ?>">
        <input type="hidden" name="apell[]"    value="<?= $apells[$j] ?>">
        <input type="hidden" name="email[]"    value="<?= $emails[$j] ?>">
        <input type="hidden" name="tel[]"      value="<?= $tels[$j] ?>">
        <input type="hidden" name="fecnac[]"   value="<?= $fecnacs[$j] ?>">
        <input type="hidden" name="cargo[]"    value="<?= $cargos[$j] ?>">
        <input type="hidden" name="cncost[]"   value="<?= $cncosts[$j] ?>">
        <input type="hidden" name="fecing[]"   value="<?= $fecings[$j] ?>">
        <input type="hidden" name="nvlrg[]"    value="<?= $nvlrgs[$j] ?>">
        <input type="hidden" name="sueldb[]"   value="<?= $sueldos[$j] ?>">
        <input type="hidden" name="diaslab[]"  value="<?= $diaslabs[$j] ?>">
        <input type="hidden" name="diasarl[]"  value="<?= $diasarls[$j] ?>">
        <input type="hidden" name="diasvac[]"  value="<?= $diasvacs[$j] ?>">
        <input type="hidden" name="diaseps[]"  value="<?= $diasepss[$j] ?>">
        <input type="hidden" name="hrdi[]"     value="<?= $hrdis[$j] ?>">
        <input type="hidden" name="hrnoc[]"   value="<?= $hrnocs[$j] ?>">
        <input type="hidden" name="hrdomdi[]"  value="<?= $hrdomdis[$j] ?>">
        <input type="hidden" name="hrdomnoc[]" value="<?= $hrdomnocs[$j] ?>">
      <?php } ?>
      <input type="hidden" name="i" value="<?= count($ids) ?>">
      <div class="divider-label"><span>Información Personal</span></div>
      <div class="field">
        <label>No. Identificación</label>
        <input type="text" id="identificacion" placeholder="Ej. 1020304050" maxlength="12" name="id[]">
        <p class="error-msg" id="err-identificacion"></p>
      </div>
      <div class="row two">
        <div class="field">
          <label>Nombres</label>
          <input type="text" id="nombres" placeholder="Nombres" name="name[]">
          <p class="error-msg" id="err-nombres"></p>
        </div>
        <div class="field">
          <label>Apellidos</label>
          <input type="text" id="apellidos" placeholder="Apellidos" name="apell[]">
          <p class="error-msg" id="err-apellidos"></p>
        </div>
      </div>

      <div class="row two">
        <div class="field">
          <label>Email</label>
          <input type="email" id="email" placeholder="correo@empresa.com" name="email[]">
          <p class="error-msg" id="err-email"></p>
        </div>
        <div class="field">
          <label>Teléfono</label>
          <input type="tel" id="telefono" placeholder="Ej. 3001234567" maxlength="10" name="tel[]">
        </div>
      </div>
      <div class="field">
        <label>Fecha de Nacimiento</label>
        <input type="date" id="fechaNacimiento" name="fecnac[]">
      </div>

      <div class="divider-label"><span>Información Laboral</span></div>
      <div class="row two">
        <div class="field">
          <label>Cargo</label>
          <input type="text" id="cargo" placeholder="Ej. Analista" name="cargo[]">
          <p class="error-msg" id="err-cargo"></p>
        </div>
        <div class="field">
          <label>Centro de Costos</label>
          <input type="text" id="centroCostos" placeholder="Ej. Administración" name="cncost[]">
          <p class="error-msg" id="err-centroCostos"></p>
        </div>
      </div>
      <div class="row two">
        <div class="field">
          <label>Fecha de Ingreso</label>
          <input type="date" id="fechaIngreso" name="fecing[]">
          <p class="error-msg" id="err-fechaIngreso"></p>
        </div>
        <div class="field">
          <label>Nivel de Riesgo ARL</label>
          <div class="select-wrap">
            <select id="nivelARL" name="nvlrg[]" required>
              <option value="">— Seleccione —</option>
              <option value="0.00522">I — 0.522%</option>
              <option value="0.01044">II — 1.044%</option>
              <option value="0.02436">III — 2.436%</option>
              <option value="0.04350">IV — 4.350%</option>
              <option value="0.06960">V — 6.960%</option>
            </select>
          </div>
          <p class="error-msg" id="err-nivelARL"></p>
        </div>
      </div>

      <div class="section-devengados">
        <div class="divider-label"><span>Devengados</span></div>
        <div class="field">
          <label>Sueldo Base <span class="label-hint">(mensual pactado)</span></label>
          <div class="currency-wrap">
            <span class="currency-sym">$</span>
            <input type="number" id="sueldoBase" placeholder="0" min="1750000" step="1000" name="sueldb[]">
          </div>
          <p class="error-msg" id="err-sueldoBase"></p>
        </div>
        <div class="days-counter" id="daysCounter">
          <span>Días del mes asignados</span>
          <span class="dc-used"><span id="dcUsed">0</span> / 30</span>
        </div>
        <p class="error-msg" id="err-days" style="margin-top:-10px;margin-bottom:14px;"></p>

        <div class="field">
          <label>Días Laborados</label>
          <input type="number" id="diasLaborados" required placeholder="0" min="0" max="30" step="1" name="diaslab[]">
        </div>
        <div class="field">
          <label>Vacaciones disfrutadas <span class="label-hint">(días)</span></label>
          <input type="number" id="diasVacaciones" placeholder="0" value="0" min="0" max="30" step="1" name="diasvac[]">
        </div>
        <div class="field">
          <label>¿Incapacidad ARL?</label>
          <div class="toggle-group toggle-si">
            <input type="radio" name="incapARL" id="arlNo" value="no" checked>
            <label for="arlNo">No</label>
            <input type="radio" name="incapARL" id="arlSi" value="si">
            <label for="arlSi">Sí</label>
          </div>
          <div class="incap-days" id="incapARLDays">
            <label style="margin-top:10px;">Días de incapacidad ARL</label>
            <input type="number" id="diasARL" value="0" placeholder="0" min="0" max="30" step="1" name="diasarl[]">
          </div>
        </div>
        <div class="field">
          <label>¿Incapacidad EPS?</label>
          <div class="toggle-group toggle-si">
            <input type="radio" name="incapEPS" id="epsNo" value="no" checked>
            <label for="epsNo">No</label>
            <input type="radio" name="incapEPS" id="epsSi" value="si">
            <label for="epsSi">Sí</label>
          </div>
          <div class="incap-days" id="incapEPSDays">
            <label style="margin-top:10px;">Días de incapacidad EPS</label>
            <input type="number" id="diasEPS" value="0" placeholder="0" min="0" max="30" step="1" name="diaseps[]">
          </div>
        </div>
        <div class="section-horas">
          <div class="divider-label"><span>Horas Extra</span></div>
          <div class="row two">
            <div class="field">
              <label>Diurna <span class="label-hint">(horas)</span></label>
              <input type="number" id="heD" value="0" placeholder="0" min="0" step="0.5" name="hrdi[]">
            </div>
            <div class="field">
              <label>Nocturna <span class="label-hint">(horas)</span></label>
              <input type="number" id="heN" value="0" placeholder="0" min="0" step="0.5" name="hrnoc[]">
            </div>
          </div>
          <div class="row two">
            <div class="field">
              <label>Dom./Festiva Diurna <span class="label-hint">(horas)</span></label>
              <input type="number" id="heDom" value="0" placeholder="0" min="0" step="0.5" name="hrdomdi[]">
            </div>
            <div class="field">
              <label>Dom./Festiva Nocturna <span class="label-hint">(horas)</span></label>
              <input type="number" id="heDomN" value="0" placeholder="0" min="0" step="0.5" name="hrdomnoc[]">
            </div>
          </div>
        </div>
      </div>
      <button type="submit" class="submit-btn">CALCULAR &amp; REGISTRAR NÓMINA</button>
      <p class="reset-link" onclick="resetForm()">Limpiar formulario</p>
    </form>
    <?php if (isset($_REQUEST["id"])) { ?>
      <div class="table-wrapper">
        <div class="table-title">EMPLEADOS REGISTRADOS</div>
        <table class="nomina-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Cargo</th>
              <th>Sueldo</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $id = $_REQUEST["id"];
            $nombre = $_REQUEST["name"];
            $apell = $_REQUEST["apell"];
            $cargo = $_REQUEST["cargo"];
            $sueldb = $_REQUEST["sueldb"];
            for ($i = 0; $i < count($id); $i++) {
            ?>
              <tr>
                <td>
                  <?= $id[$i] ?>
                </td>
                <td>
                  <?= $nombre[$i] . " " . $apell[$i] ?>
                </td>
                <td>
                  <?= $cargo[$i] ?>
                </td>
                <td>$
                  <?= number_format($sueldb[$i]) ?>
                </td>
                <td>
                  <form action="resultado.php" method="post">
                    <input type="hidden" name="i" value="<?= $i ?>">
                    <?php for ($j = 0; $j < count($id); $j++) { ?>
                      <input type="hidden" name="id[]" value="<?= $ids[$j] ?>">
                      <input type="hidden" name="name[]" value="<?= $names[$j] ?>">
                      <input type="hidden" name="apell[]" value="<?= $apells[$j] ?>">
                      <input type="hidden" name="email[]" value="<?= $emails[$j] ?>">
                      <input type="hidden" name="tel[]" value="<?= $tels[$j] ?>">
                      <input type="hidden" name="fecnac[]" value="<?= $fecnacs[$j] ?>">
                      <input type="hidden" name="cargo[]" value="<?= $cargos[$j] ?>">
                      <input type="hidden" name="cncost[]" value="<?= $cncosts[$j] ?>">
                      <input type="hidden" name="fecing[]" value="<?= $fecings[$j] ?>">
                      <input type="hidden" name="nvlrg[]" value="<?= $nvlrgs[$j] ?>">
                      <input type="hidden" name="sueldb[]" value="<?= $sueldos[$j] ?>">
                      <input type="hidden" name="diaslab[]" value="<?= $diaslabs[$j] ?>">
                      <input type="hidden" name="diasarl[]" value="<?= $diasarls[$j] ?>">
                      <input type="hidden" name="diasvac[]" value="<?= $diasvacs[$j] ?>">
                      <input type="hidden" name="diaseps[]" value="<?= $diasepss[$j] ?>">
                      <input type="hidden" name="hrdi[]" value="<?= $hrdis[$j] ?>">
                      <input type="hidden" name="hrnoc[]" value="<?= $hrnocs[$j] ?>">
                      <input type="hidden" name="hrdomdi[]" value="<?= $hrdomdis[$j] ?>">
                      <input type="hidden" name="hrdomnoc[]" value="<?= $hrdomnocs[$j] ?>">
                    <?php } ?>
                    <button type="submit" class="table-btn">DETALLE</button>
                  </form>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    <?php } ?>
  </div>
  </div>
</body>
<script src="script.js"></script>

</html>