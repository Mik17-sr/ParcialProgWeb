const $ = id => document.getElementById(id);

/* ── CONTADOR DE DÍAS (máx 30) ── */
function totalDias() {
  const arl = $('arlSi').checked ? (parseFloat($('diasARL').value) || 0) : 0;
  const eps = $('epsSi').checked ? (parseFloat($('diasEPS').value) || 0) : 0;

  return (parseFloat($('diasLaborados').value) || 0) +
         (parseFloat($('diasVacaciones').value) || 0) +
         arl + eps;
}

function updateDaysCounter() {
  const total = totalDias();
  $('dcUsed').textContent = total;

  const counter = $('daysCounter');
  counter.classList.toggle('warn', total > 30);
  counter.classList.toggle('full', total === 30);
}

/* Limitar para que no pase de 30 */
function clampDays(changedId) {
  const el = $(changedId);
  if (!el) return;

  let val = parseFloat(el.value) || 0;
  if (val < 0) val = 0;

  el.value = 0;
  const restantes = 30 - totalDias();

  el.value = Math.min(val, Math.max(0, restantes));

  updateDaysCounter();
}

/* Eventos de inputs de días */
['diasLaborados','diasVacaciones','diasARL','diasEPS']
.forEach(id => {
  const el = $(id);
  if (!el) return;

  el.addEventListener('input', () => clampDays(id));
});

/* ── Mostrar/Ocultar incapacidad ARL ── */
document.querySelectorAll('input[name="incapARL"]').forEach(r =>
  r.addEventListener('change', () => {
    const show = $('arlSi').checked;
    $('incapARLDays').classList.toggle('visible', show);

    if (!show) $('diasARL').value = '';

    updateDaysCounter();
  })
);

/* ── Mostrar/Ocultar incapacidad EPS ── */
document.querySelectorAll('input[name="incapEPS"]').forEach(r =>
  r.addEventListener('change', () => {
    const show = $('epsSi').checked;
    $('incapEPSDays').classList.toggle('visible', show);

    if (!show) $('diasEPS').value = '';

    updateDaysCounter();
  })
);

/* ── Validación básica ── */
function validateForm() {
  let ok = true;

  const requiredFields = [
    'identificacion','nombres','apellidos',
    'email','cargo','centroCostos',
    'fechaIngreso','nivelARL','sueldoBase'
  ];

  requiredFields.forEach(id => {
    const el = $(id);
    if (!el.value.trim()) {
      el.classList.add('error');
      ok = false;
    } else {
      el.classList.remove('error');
    }
  });

  // Validación de email
  const email = $('email').value;
  if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    $('email').classList.add('error');
    ok = false;
  }

  // Validación de días
  const total = totalDias();
  if (total === 0 || total > 30) {
    $('daysCounter').classList.add('warn');
    ok = false;
  }

  return ok;
}

/* ── Reset ── */
function resetForm() {
  $('payrollForm').reset();

  $('payrollForm').style.display = '';
  $('incapARLDays').classList.remove('visible');
  $('incapEPSDays').classList.remove('visible');

  $('dcUsed').textContent = '0';
  $('daysCounter').classList.remove('warn','full');

  document.querySelectorAll('.error').forEach(el =>
    el.classList.remove('error')
  );
}