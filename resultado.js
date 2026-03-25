document.addEventListener('DOMContentLoaded', () => {
  const rows = document.querySelectorAll('.nomina-table tbody tr:not(.empty-row)');
  rows.forEach((row, i) => {
    row.style.opacity = '0';
    row.style.transform = 'translateX(-8px)';
    row.style.transition = `opacity 0.3s ease ${i * 0.04}s, transform 0.3s ease ${i * 0.04}s`;

    setTimeout(() => {
      row.style.opacity = '1';
      row.style.transform = 'translateX(0)';
    }, 80);
  });


  const allRows = document.querySelectorAll('.nomina-table tbody tr:not(.empty-row)');
  allRows.forEach(row => {
    row.style.cursor = 'pointer';
    row.addEventListener('click', (e) => {
      e.stopPropagation(); 
      const wasSelected = row.classList.contains('selected');
      allRows.forEach(r => r.classList.remove('selected'));

      if (!wasSelected) {
        row.classList.add('selected');
      }
    });
  });

  document.addEventListener('click', (e) => {
    if (!e.target.closest('.nomina-table')) {
      allRows.forEach(r => r.classList.remove('selected'));
    }
  });

  const footerCells = document.querySelectorAll(
    '.nomina-table tfoot .highlight, .nomina-table tfoot .highlight-red'
  );

  footerCells.forEach(cell => {
    cell.setAttribute('title', 'Haga clic para copiar');
    cell.style.cursor = 'copy';

    cell.addEventListener('click', (e) => {
      e.stopPropagation();

      const currentText = cell.textContent.trim();

      navigator.clipboard.writeText(currentText).then(() => {
        cell.classList.add('copied');

        setTimeout(() => {
          cell.classList.remove('copied');
        }, 1200);
      });
    });
  });

});