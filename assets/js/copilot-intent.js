// FULL Copilot — Intent Filter & Free Text Input
// Handles plugin matching, out-of-scope detection, and send button state

(function() {

  // Plugin catalog — extend as stack grows
  var PLUGINS = [
    { nome: 'Elementor PRO', alias: ['elementor','elemntor','elementor pro','page builder'], icone: 'assets/images/icons/plugin-elementor.svg', desc: 'Page builder premium' },
    { nome: 'WP Rocket', alias: ['wp rocket','wprocket','rocket','cache','velocidade','performance'], icone: 'assets/images/icons/plugin-wprocket.svg', desc: 'Cache e performance' },
    { nome: 'Crocoblock', alias: ['crocoblock','croco','jet','jetengine','jetelements'], icone: 'assets/images/icons/plugin-crocoblock.svg', desc: 'Suite JetEngine' },
    { nome: 'Astra PRO', alias: ['astra','astra pro','tema astra','theme'], icone: 'assets/images/icons/plugin-astra.svg', desc: 'Tema premium WordPress' },
    { nome: 'ACF PRO', alias: ['acf','acf pro','advanced custom fields','campos customizados'], icone: 'assets/images/icons/plugin-acf.svg', desc: 'Custom fields avançados' },
    { nome: 'WooCommerce PRO', alias: ['woocommerce','woo','loja','ecommerce','e-commerce'], icone: 'assets/images/icons/plugin-woocommerce.svg', desc: 'Loja virtual WordPress' },
    { nome: 'Rank Math PRO', alias: ['rank math','rankmath','seo','rank math pro'], icone: 'assets/images/icons/plugin-rankmath.svg', desc: 'SEO avançado' },
    { nome: 'Full.ai', alias: ['full ai','fullai','ia','inteligencia artificial','conteudo ia'], icone: 'assets/images/icons/plugin-fullai.svg', desc: 'Conteúdo com IA' },
  ];

  // Fuzzy match: returns score 0-1 (1 = exact)
  function score(query, target) {
    query = query.toLowerCase().trim();
    target = target.toLowerCase();
    if (!query) return 0;
    if (target === query) return 1;
    if (target.startsWith(query)) return 0.9;
    if (target.includes(query)) return 0.7;
    // char-by-char match for typos
    var matched = 0;
    var ti = 0;
    for (var qi = 0; qi < query.length; qi++) {
      while (ti < target.length && target[ti] !== query[qi]) ti++;
      if (ti < target.length) { matched++; ti++; }
    }
    return matched / Math.max(query.length, target.length);
  }

  // Find matching plugins for a query
  function buscarPlugins(query) {
    if (!query || query.length < 2) return [];
    var resultados = [];
    PLUGINS.forEach(function(plugin) {
      var maxScore = 0;
      // Check nome
      maxScore = Math.max(maxScore, score(query, plugin.nome));
      // Check alias
      plugin.alias.forEach(function(a) {
        maxScore = Math.max(maxScore, score(query, a));
      });
      if (maxScore > 0.35) {
        resultados.push({ plugin: plugin, score: maxScore });
      }
    });
    resultados.sort(function(a, b) { return b.score - a.score; });
    return resultados.slice(0, 4).map(function(r) { return r.plugin; });
  }

  // Highlight query in text
  function highlight(text, query) {
    if (!query) return text;
    var idx = text.toLowerCase().indexOf(query.toLowerCase());
    if (idx === -1) return text;
    return text.slice(0, idx) + '<mark>' + text.slice(idx, idx + query.length) + '</mark>' + text.slice(idx + query.length);
  }

  // Check if query is clearly out of scope (not a plugin activation intent)
  function foraDoEscopo(query) {
    var q = query.toLowerCase();
    var escopoKeywords = ['ativar','instalar','add','adicionar','plugin','pro','premium'];
    var foraKeywords = ['audio','áudio','video','vídeo','criar','criar página','otimizar','velocidade','seo','post','artigo','como','dicas','ajuda','newsletter','backup'];
    // If matches a known plugin, it's in scope
    if (buscarPlugins(q).length > 0) return false;
    // If has out-of-scope keywords but no plugin keywords, it's out of scope
    var temFora = foraKeywords.some(function(k) { return q.includes(k); });
    var temEscopo = escopoKeywords.some(function(k) { return q.includes(k); });
    return temFora && !temEscopo;
  }

  document.addEventListener('DOMContentLoaded', function() {
    var inputWrapper = document.getElementById('copilotInput');
    var inputField = document.getElementById('copilotTexto');
    var sugestoesEl = document.getElementById('copilotSugestoes');
    var feedbackEl = document.getElementById('copilotFeedback');
    var btnEnviar = document.querySelector('.fs-btn-enviar');

    if (!inputField) return;

    var pluginSelecionado = null;
    var pluginsSelecionados = []; // multi-select array
    var sugestaoAtiva = -1;
    var tagsEl = document.getElementById('copilotTags');

    // Initially disable send button
    if (btnEnviar) btnEnviar.classList.add('desabilitado');

    // Render a tag for a plugin
    function adicionarTag(plugin) {
      // Prevent duplicates
      if (pluginsSelecionados.find(function(p) { return p.nome === plugin.nome; })) return;
      pluginsSelecionados.push(plugin);

      var tag = document.createElement('div');
      tag.className = 'fs-copilot-tag';
      tag.setAttribute('data-nome', plugin.nome);
      tag.innerHTML =
        '<img class="fs-copilot-tag__icone" src="' + plugin.icone + '" alt="">' +
        '<span class="fs-copilot-tag__nome">' + plugin.nome + '</span>' +
        '<button class="fs-copilot-tag__remover" title="Remover">&times;</button>';

      tag.querySelector('.fs-copilot-tag__remover').addEventListener('click', function(e) {
        e.stopPropagation();
        removerTag(plugin.nome);
      });

      tagsEl.appendChild(tag);
      inputWrapper.classList.add('fs-copilot-input--com-tags');

      // Enable send, clear field, keep dropdown open
      if (btnEnviar) { btnEnviar.classList.remove('desabilitado'); btnEnviar.classList.remove('ativo'); void btnEnviar.offsetWidth; btnEnviar.classList.add('ativo'); }
      inputField.value = '';
      inputField.focus();

      // Update sugestões: mark as selected
      atualizarSelecionadosNoDropdown();
    }

    function removerTag(nome) {
      pluginsSelecionados = pluginsSelecionados.filter(function(p) { return p.nome !== nome; });
      var tag = tagsEl.querySelector('[data-nome="' + nome + '"]');
      if (tag) tag.remove();
      if (pluginsSelecionados.length === 0) {
        inputWrapper.classList.remove('fs-copilot-input--com-tags');
        if (btnEnviar) { btnEnviar.classList.add('desabilitado'); btnEnviar.classList.remove('ativo'); }
        inputField.placeholder = 'Selecione uma ativação para iniciar';
      }
      atualizarSelecionadosNoDropdown();
    }

    function atualizarSelecionadosNoDropdown() {
      var nomesSelecionados = pluginsSelecionados.map(function(p) { return p.nome; });
      sugestoesEl.querySelectorAll('.fs-copilot-sugestao').forEach(function(el) {
        var nome = el.querySelector('.fs-copilot-sugestao__nome');
        if (!nome) return;
        var nomeTxt = nome.textContent.replace(' ✓','').replace('✓','').trim();
        el.classList.toggle('fs-copilot-sugestao--selecionado', nomesSelecionados.includes(nomeTxt));
      });
    }

    function renderSugestoes(plugins, query) {
      if (!plugins.length) {
        sugestoesEl.classList.remove('ativo');
        sugestoesEl.innerHTML = '';
        return;
      }
      sugestoesEl.innerHTML = plugins.map(function(p, i) {
        return '<div class="fs-copilot-sugestao" data-idx="' + i + '" data-plugin="' + encodeURIComponent(JSON.stringify(p)) + '">' +
          '<img class="fs-copilot-sugestao__icone" src="' + p.icone + '" alt="">' +
          '<div class="fs-copilot-sugestao__texto">' +
            '<span class="fs-copilot-sugestao__nome">' + highlight(p.nome, query) + '</span>' +
            '<span class="fs-copilot-sugestao__desc">' + p.desc + '</span>' +
          '</div>' +
        '</div>';
      }).join('');
      sugestoesEl.classList.add('ativo');
      sugestaoAtiva = -1;
    }

    function showFeedback(html) {
      feedbackEl.innerHTML = html;
      feedbackEl.classList.remove('fs-oculto');
      sugestoesEl.classList.remove('ativo');
    }

    function hideFeedback() {
      feedbackEl.classList.add('fs-oculto');
      feedbackEl.innerHTML = '';
    }

    function selecionarPlugin(plugin) {
      pluginSelecionado = plugin;
      adicionarTag(plugin);
      hideFeedback();
      // Re-render suggestions without closing (so user can pick more)
      var query = inputField.value.trim();
      renderSugestoes(query ? buscarPlugins(query) : PLUGINS, query);
    }

    function feedbackForaEscopo(query) {
      showFeedback(
        '<div class="fs-copilot-feedback__msg">' +
          '<div class="fs-copilot-feedback__avatar"><img src="assets/images/icons/smiley.svg" alt="" width="10" height="8"></div>' +
          '<p class="fs-copilot-feedback__texto">Ainda não sei fazer isso. No momento só consigo <strong>ativar plugins PRO</strong>. Quer ativar algum?</p>' +
        '</div>' +
        '<div class="fs-copilot-feedback__chips">' +
          '<a href="chat.html" class="fs-copilot-feedback__chip"><img src="assets/images/icons/plugin-elementor.svg">Elementor PRO</a>' +
          '<a href="chat.html" class="fs-copilot-feedback__chip"><img src="assets/images/icons/plugin-wprocket.svg">WP Rocket</a>' +
          '<a href="chat.html" class="fs-copilot-feedback__chip"><img src="assets/images/icons/plugin-rankmath.svg">Rank Math PRO</a>' +
          '<a href="skills.html" class="fs-copilot-feedback__chip"><img src="assets/images/icons/layers-01.svg">Ver todos</a>' +
        '</div>'
      );
      if (btnEnviar) btnEnviar.classList.add('desabilitado');
    }


    // Ao focar OU clicar: sempre abrir o dropdown
    function abrirDropdown() {
      var query = inputField.value.trim();
      if (query.length >= 2) {
        var matches = buscarPlugins(query);
        if (matches.length > 0) renderSugestoes(matches, query);
        else renderSugestoes(PLUGINS, '');
      } else {
        renderSugestoes(PLUGINS, '');
      }
    }

    inputField.addEventListener('focus', abrirDropdown);
    inputField.addEventListener('click', abrirDropdown);

    // Ao perder foco: fechar com delay para permitir clique na sugestão
    inputField.addEventListener('blur', function() {
      setTimeout(function() {
        sugestoesEl.classList.remove('ativo');
      }, 150);
    });

    // Clicar na área do input wrapper também abre e foca
    if (inputWrapper) {
      inputWrapper.addEventListener('click', function(e) {
        if (!e.target.closest('.fs-copilot-tag') && !e.target.closest('.fs-copilot-input__sugestoes')) {
          inputField.focus();
          abrirDropdown();
        }
      });
    }

    // Input handler
    inputField.addEventListener('input', function() {
      var query = inputField.value.trim();
      pluginSelecionado = null;
      if (btnEnviar) btnEnviar.classList.add('desabilitado');

      if (!query) {
        hideFeedback();
        renderSugestoes(PLUGINS, '');
        return;
      }

      if (query.length < 2) return;

      var matches = buscarPlugins(query);

      if (matches.length > 0) {
        hideFeedback();
        renderSugestoes(matches, query);
      } else if (query.length >= 4 && foraDoEscopo(query)) {
        sugestoesEl.classList.remove('ativo');
        feedbackForaEscopo(query);
      } else if (query.length >= 4) {
        // Unknown but could still be typing — show generic no-match after longer text
        showFeedback(
          '<div class="fs-copilot-feedback__msg">' +
            '<div class="fs-copilot-feedback__avatar"><img src="assets/images/icons/smiley.svg" alt="" width="10" height="8"></div>' +
            '<p class="fs-copilot-feedback__texto">Não encontrei um plugin com esse nome. Veja os disponíveis:</p>' +
          '</div>' +
          '<div class="fs-copilot-feedback__chips">' +
            PLUGINS.slice(0,4).map(function(p) {
              return '<a href="chat.html" class="fs-copilot-feedback__chip"><img src="' + p.icone + '">' + p.nome + '</a>';
            }).join('') +
            '<a href="skills.html" class="fs-copilot-feedback__chip"><img src="assets/images/icons/layers-01.svg">Ver todos</a>' +
          '</div>'
        );
      } else {
        hideFeedback();
        sugestoesEl.classList.remove('ativo');
      }
    });

    // Click on suggestion
    sugestoesEl.addEventListener('click', function(e) {
      var item = e.target.closest('.fs-copilot-sugestao');
      if (item) {
        var plugin = JSON.parse(decodeURIComponent(item.getAttribute('data-plugin')));
        selecionarPlugin(plugin);
      }
    });

    // Keyboard navigation
    inputField.addEventListener('keydown', function(e) {
      // Backspace on empty field removes last tag
      if (e.key === 'Backspace' && !inputField.value && pluginsSelecionados.length > 0) {
        var ultimo = pluginsSelecionados[pluginsSelecionados.length - 1];
        removerTag(ultimo.nome);
        return;
      }

      var items = sugestoesEl.querySelectorAll('.fs-copilot-sugestao');
      if (!items.length) {
        if (e.key === 'Enter' && pluginSelecionado) {
          window.location.href = 'chat.html';
        }
        return;
      }
      if (e.key === 'ArrowDown') {
        e.preventDefault();
        sugestaoAtiva = Math.min(sugestaoAtiva + 1, items.length - 1);
        items.forEach(function(i, idx) { i.classList.toggle('selecionado', idx === sugestaoAtiva); });
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        sugestaoAtiva = Math.max(sugestaoAtiva - 1, -1);
        items.forEach(function(i, idx) { i.classList.toggle('selecionado', idx === sugestaoAtiva); });
      } else if (e.key === 'Enter') {
        e.preventDefault();
        if (sugestaoAtiva >= 0 && items[sugestaoAtiva]) {
          items[sugestaoAtiva].click();
        } else if (pluginSelecionado) {
          window.location.href = 'chat.html';
        }
      } else if (e.key === 'Escape') {
        sugestoesEl.classList.remove('ativo');
        hideFeedback();
      }
    });

    // Send button
    if (btnEnviar) {
      btnEnviar.addEventListener('click', function() {
        if (!pluginSelecionado) return;
        window.location.href = 'chat.html';
      });
    }

    // Close suggestions on outside click
    document.addEventListener('click', function(e) {
      if (!inputWrapper.contains(e.target)) {
        sugestoesEl.classList.remove('ativo');
      }
    });

  });

})();

// Skill selector — troca contexto e placeholder
(function() {
  document.addEventListener('DOMContentLoaded', function() {
    var skillItems = document.querySelectorAll('.fs-skill-chip__item:not(.fs-skill-chip__item--embreve)');
    var skillNome  = document.getElementById('skillNome');
    var skillIcone = document.getElementById('skillIcone');
    var inputField = document.getElementById('copilotTexto');

    skillItems.forEach(function(item) {
      item.addEventListener('click', function() {
        // Update active state
        document.querySelectorAll('.fs-skill-chip__item').forEach(function(i) {
          i.classList.remove('fs-skill-chip__item--ativo');
          var chk = i.querySelector('.fs-skill-chip__check');
          if (chk) chk.classList.add('fs-oculto');
        });
        item.classList.add('fs-skill-chip__item--ativo');
        var check = item.querySelector('.fs-skill-chip__check');
        if (check) check.classList.remove('fs-oculto');

        // Update button label + icon
        var nome   = item.querySelector('.fs-skill-chip__item-nome').textContent;
        var icone  = item.getAttribute('data-icone');
        var ph     = item.getAttribute('data-placeholder');
        if (skillNome)  skillNome.textContent = nome;
        if (skillIcone && icone) skillIcone.src = icone;
        if (inputField && ph)  inputField.placeholder = ph;

        // Clear current input
        if (inputField) {
          inputField.value = '';
          inputField.focus();
        }

        // Close dropdown via Bootstrap
        var menu = document.querySelector('.fs-skill-chip__menu');
        if (menu) {
          var dd = bootstrap.Dropdown.getInstance(document.getElementById('skillGatilho'));
          if (dd) dd.hide();
        }
      });
    });

    // Em breve items → redirect to skills.html tab
    document.querySelectorAll('.fs-skill-chip__item--embreve').forEach(function(item) {
      item.addEventListener('click', function() {
        window.location.href = 'skills.html';
      });
    });

  });
})();
