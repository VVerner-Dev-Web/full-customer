document.addEventListener('DOMContentLoaded', () => {
  const chatArea = document.querySelector('.fs-chat__container');
  const mensagens = document.querySelectorAll('.fs-chat__animavel');
  const passoGrupos = document.querySelectorAll('.fs-chat__passo-grupo');

  // Esconder elementos para animacao
  mensagens.forEach((msg) => {
    msg.classList.add('fs-chat__animavel--oculto');
  });

  passoGrupos.forEach((grupo) => {
    grupo.classList.add('fs-chat__passo-grupo--oculto');
  });

  // Revelar mensagem com transição suave
  function revelarMensagem(elemento) {
    elemento.classList.add('fs-animar');
    elemento.classList.remove('fs-chat__animavel--oculto');
    rolarParaBaixo();
  }

  // Animar passos do progresso
  function animarPassos() {
    passoGrupos.forEach((grupo, i) => {
      setTimeout(() => {
        grupo.classList.remove('fs-chat__passo-grupo--oculto');
      }, i * 100);
    });
  }

  // Rolagem automática
  function rolarParaBaixo() {
    chatArea.scrollTop = chatArea.scrollHeight;
  }

  // Sequencia de animacao
  const intervalo = 150;

  mensagens.forEach((msg, i) => {
    setTimeout(() => {
      revelarMensagem(msg);

      // Se for o cartao de progresso (3o elemento), animar passos
      if (i === 2) {
        animarPassos();
      }
    }, i * intervalo);
  });

  // Dropdown de ativacao (filtro)
  const dropdown = document.getElementById('seletorAtivacaoChat');

  if (dropdown) {
    const searchInput = dropdown.querySelector('.fs-seletor__campo-busca');
    const items = dropdown.querySelectorAll('.fs-seletor__item');

    dropdown.addEventListener('shown.bs.dropdown', () => {
      if (searchInput) {
        searchInput.value = '';
        items.forEach((item) => item.classList.remove('fs-oculto'));
        searchInput.focus();
      }
    });

    if (searchInput) {
      searchInput.addEventListener('input', () => {
        const query = searchInput.value.toLowerCase();
        items.forEach((item) => {
          const name = item.querySelector('.fs-seletor__item-nome')?.textContent.toLowerCase() || '';
          item.classList.toggle('fs-oculto', !name.includes(query));
        });
      });
    }
  }

});

