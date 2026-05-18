(function () {
  document.querySelectorAll('[data-cms-repeater]').forEach((repeater) => {
    const rowsContainer = repeater.querySelector('[data-repeater-rows]');
    const addBtn = repeater.querySelector('[data-repeater-add]');
    const name = repeater.dataset.repeaterName || '';

    if (!rowsContainer || !addBtn) return;

    const reindex = () => {
      // For every row, rewrite name="content[<name>.<INDEX>.<field>]"
      const rows = rowsContainer.querySelectorAll('[data-repeater-row]');
      rows.forEach((row, idx) => {
        row.querySelectorAll('[name]').forEach((el) => {
          const nameAttr = el.getAttribute('name');
          // Match content[<name>.<digits>.<rest>]  OR image[<name>.<digits>.<rest>]
          const re = new RegExp(`^(content|image)\\[${escapeRegex(name)}\\.\\d+\\.(.+)\\]$`);
          const match = nameAttr.match(re);
          if (match) {
            el.setAttribute('name', `${match[1]}[${name}.${idx}.${match[2]}]`);
          }
        });
        row.querySelectorAll('[id]').forEach((el) => {
          const idAttr = el.getAttribute('id');
          const re = new RegExp(`^field-${escapeRegex(name)}-\\d+-(.+)$`);
          const match = idAttr.match(re);
          if (match) {
            el.setAttribute('id', `field-${name}-${idx}-${match[1]}`);
          }
        });
        row.querySelectorAll('label[for]').forEach((label) => {
          const forAttr = label.getAttribute('for');
          const re = new RegExp(`^field-${escapeRegex(name)}-\\d+-(.+)$`);
          const match = forAttr.match(re);
          if (match) {
            label.setAttribute('for', `field-${name}-${idx}-${match[1]}`);
          }
        });
      });
    };

    addBtn.addEventListener('click', () => {
      const rows = rowsContainer.querySelectorAll('[data-repeater-row]');
      const lastRow = rows[rows.length - 1];
      const clone = (lastRow || rowsContainer.querySelector('[data-repeater-row]')).cloneNode(true);
      // Clear values inside the clone
      clone.querySelectorAll('input[type="text"], textarea').forEach((el) => { el.value = ''; });
      clone.querySelectorAll('input[type="checkbox"]').forEach((el) => { el.checked = false; });
      rowsContainer.appendChild(clone);
      reindex();
      bindRemove(clone);
    });

    const bindRemove = (row) => {
      const btn = row.querySelector('[data-repeater-remove]');
      if (!btn) return;
      btn.addEventListener('click', () => {
        const remaining = rowsContainer.querySelectorAll('[data-repeater-row]').length;
        if (remaining <= 1) {
          // keep one row visible — just clear it
          row.querySelectorAll('input[type="text"], textarea').forEach((el) => { el.value = ''; });
          row.querySelectorAll('input[type="checkbox"]').forEach((el) => { el.checked = false; });
          return;
        }
        row.remove();
        reindex();
      });
    };

    rowsContainer.querySelectorAll('[data-repeater-row]').forEach(bindRemove);
  });

  function escapeRegex(s) {
    return String(s).replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  }
})();
