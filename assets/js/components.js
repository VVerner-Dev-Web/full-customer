// Carrega componentes HTML externos e reinicializa Bootstrap após injeção

function inicializarOffcanvas() {
  document.querySelectorAll('[data-bs-toggle="offcanvas"]').forEach((btn) => {
    const targetId = btn.getAttribute('data-bs-target');
    const targetEl = document.querySelector(targetId);
    if (!targetEl) return;

    // Evita duplicar listener
    if (btn._offcanvasInit) return;
    btn._offcanvasInit = true;

    btn.addEventListener('click', () => {
      const instance =
        bootstrap.Offcanvas.getInstance(targetEl) ||
        new bootstrap.Offcanvas(targetEl);
      instance.show();
    });
  });
}

// Carrega cada componente e reinicializa após todos serem injetados
const slots = document.querySelectorAll('[data-componente]');
let pendentes = slots.length;

if (pendentes === 0) {
  inicializarOffcanvas();
}

slots.forEach((slot) => {
  fetch(slot.getAttribute('data-componente'))
    .then((res) => res.text())
    .then((html) => {
      const temp = document.createElement('div');
      temp.innerHTML = html;
      slot.replaceWith(...temp.childNodes);
      pendentes--;
      if (pendentes === 0) {
        inicializarOffcanvas();
      }
    })
    .catch(() => {
      pendentes--;
      if (pendentes === 0) inicializarOffcanvas();
    });
});

// Sidebar Central: troca entre vista lista e detalhe
document.addEventListener('click', (e) => {
  if (e.target.closest('[data-ver-log]')) {
    const lista = document.getElementById('centralLista');
    const detalhe = document.getElementById('centralDetalhe');
    if (lista && detalhe) {
      lista.classList.add('fs-oculto');
      detalhe.classList.remove('fs-oculto');
    }
  }

  if (e.target.closest('[data-voltar-central]')) {
    const lista = document.getElementById('centralLista');
    const detalhe = document.getElementById('centralDetalhe');
    if (lista && detalhe) {
      detalhe.classList.add('fs-oculto');
      lista.classList.remove('fs-oculto');
    }
  }
});

// Resetar vista ao fechar a sidebar
document.addEventListener('hidden.bs.offcanvas', (e) => {
  if (e.target.id === 'sidebarCentral') {
    const lista = document.getElementById('centralLista');
    const detalhe = document.getElementById('centralDetalhe');
    if (lista && detalhe) {
      lista.classList.remove('fs-oculto');
      detalhe.classList.add('fs-oculto');
    }
  }
});
