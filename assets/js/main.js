document.addEventListener('DOMContentLoaded', () => {
  // Fechar faixa de notificação
  const banner = document.getElementById('faixaNotificacao');
  const bannerClose = document.getElementById('faixaFechar');

  if (bannerClose && banner) {
    bannerClose.addEventListener('click', () => {
      banner.classList.add('fs-faixa--oculta');
    });
  }

  // Acordeão de perguntas frequentes
  const faqItems = document.querySelectorAll('.fs-perguntas__cabecalho');

  faqItems.forEach((header) => {
    header.addEventListener('click', () => {
      const item = header.closest('.fs-perguntas__item');
      const isOpen = item.classList.contains('fs-perguntas__item--aberto');

      // Fechar todos
      document.querySelectorAll('.fs-perguntas__item--aberto').forEach((openItem) => {
        openItem.classList.remove('fs-perguntas__item--aberto');
      });

      // Alternar atual
      if (!isOpen) {
        item.classList.add('fs-perguntas__item--aberto');
      }
    });
  });

  // Seletor "Nova ativação" (Bootstrap Dropdown)
  const dropdown = document.getElementById('seletorAtivacao');

  if (dropdown) {
    const searchInput = dropdown.querySelector('.fs-seletor__campo-busca');
    const items = dropdown.querySelectorAll('.fs-seletor__item');

    // Ao abrir: limpar busca e focar input
    dropdown.addEventListener('shown.bs.dropdown', () => {
      if (searchInput) {
        searchInput.value = '';
        items.forEach((item) => item.classList.remove('fs-oculto'));
        searchInput.focus();
      }
    });

    // Filtro de busca
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

  // Setas de navegacao das tabs
  const abas = document.getElementById('abasHabilidades');
  const setaEsq = document.getElementById('abaSetaEsq');
  const setaDir = document.getElementById('abaSetaDir');

  if (abas && setaEsq && setaDir) {
    function atualizarSetas() {
      const max = abas.scrollWidth - abas.clientWidth;
      const podeSobrare = max > 1;
      setaEsq.classList.toggle('fs-oculto', !(podeSobrare && abas.scrollLeft > 1));
      setaDir.classList.toggle('fs-oculto', !(podeSobrare && abas.scrollLeft < max - 1));
    }

    setaEsq.addEventListener('click', () => {
      abas.scrollTo({ left: abas.scrollLeft - 150, behavior: 'smooth' });
      setTimeout(atualizarSetas, 350);
    });

    setaDir.addEventListener('click', () => {
      abas.scrollTo({ left: abas.scrollLeft + 150, behavior: 'smooth' });
      setTimeout(atualizarSetas, 350);
    });

    abas.addEventListener('scroll', atualizarSetas);
    window.addEventListener('resize', () => requestAnimationFrame(atualizarSetas));
    requestAnimationFrame(atualizarSetas);
  }

  // Widget de onboarding — abre na primeira visita, recolhido depois
  const widget = document.getElementById('widgetOnboarding');
  const trigger = document.getElementById('progressoTrigger');

  if (widget && trigger) {
    // Primeira visita da sessão: abre expandido
    if (!sessionStorage.getItem('onboarding_visto')) {
      widget.classList.add('fs-progresso--aberto');
      sessionStorage.setItem('onboarding_visto', '1');
    }

    trigger.addEventListener('click', () => {
      widget.classList.toggle('fs-progresso--aberto');
    });
  }

  // Animação em cascata nos cartões de extensão
  document.querySelectorAll('.fs-cartao-plugin').forEach((card, i) => {
    card.classList.add('fs-animar');
    card.style.animationDelay = `${(i + 2) * 0.06}s`;
  });
});

