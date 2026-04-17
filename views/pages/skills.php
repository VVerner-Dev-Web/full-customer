<?php

use FC\FileSystem;

$fs = FileSystem::instance();

?>

<div class="fs-habilidades-topo">
  <div class="fs-habilidades-topo__fundo"></div>
  <div class="fs-habilidades-topo__conteudo">
    <!-- Área superior: link voltar + título + descrição -->
    <div class="fs-habilidades-titulo fs-animar">
      <a <?= fcElementDataFragments('DashboardFullPage', 'app') ?> class="fs-link-voltar">
        <img src="<?= $fs->getUrl('assets/images/icons/arrow-left.svg') ?>" alt="" width="16" height="16" />
        Copilot
      </a>
      <div class="fs-habilidades-titulo__cabecalho">
        <img src="<?= $fs->getUrl('assets/images/icons/layers-01.svg') ?>" alt="" width="24" height="24" />
        <h1>Skills</h1>
      </div>
      <p class="fs-habilidades-titulo__desc">
        Skills são as capacidades do seu Copilot. Com o modelo de ativações da FULL, cada assento dá acesso a toda a pilha — você ativa o que quiser, quando quiser, sem pagar por ativação
        individual.
      </p>
    </div>
    <!-- Abas (Bootstrap) -->
    <div class="fs-habilidades-abas-wrapper fs-animar fs-animar--d1">
      <button class="fs-habilidades-abas-wrapper__seta fs-habilidades-abas-wrapper__seta--esq fs-oculto" id="abaSetaEsq" aria-label="Rolar abas para a esquerda">
        <img src="<?= $fs->getUrl('assets/images/icons/chevron-left.svg') ?>" alt="" width="16" height="16" />
      </button>
      <ul class="fs-habilidades-abas nav" id="abasHabilidades" role="tablist">
        <li class="nav-item" role="presentation">
          <button
            class="fs-habilidades-abas__item active"
            id="tab-ativacoes"
            data-bs-toggle="tab"
            data-bs-target="#pane-ativacoes"
            type="button"
            role="tab"
            aria-controls="pane-ativacoes"
            aria-selected="true">
            <img src="<?= $fs->getUrl('assets/images/icons/tab-energy.svg') ?>" alt="" width="20" height="20" />
            Ativações
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button
            class="fs-habilidades-abas__item"
            id="tab-builder"
            data-bs-toggle="tab"
            data-bs-target="#pane-builder"
            type="button"
            role="tab"
            aria-controls="pane-builder"
            aria-selected="false">
            <img src="<?= $fs->getUrl('assets/images/icons/tab-builder.svg') ?>" alt="" width="20" height="20" />
            Construtor IA
            <span class="fs-emblema fs-emblema--aviso">Em breve</span>
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button
            class="fs-habilidades-abas__item"
            id="tab-snippets"
            data-bs-toggle="tab"
            data-bs-target="#pane-snippets"
            type="button"
            role="tab"
            aria-controls="pane-snippets"
            aria-selected="false">
            <img src="<?= $fs->getUrl('assets/images/icons/tab-snippets.svg') ?>" alt="" width="20" height="20" />
            Trechos IA
            <span class="fs-emblema fs-emblema--aviso">Em breve</span>
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button
            class="fs-habilidades-abas__item"
            id="tab-errorfix"
            data-bs-toggle="tab"
            data-bs-target="#pane-errorfix"
            type="button"
            role="tab"
            aria-controls="pane-errorfix"
            aria-selected="false">
            <img src="<?= $fs->getUrl('assets/images/icons/tab-errorfix.svg') ?>" alt="" width="20" height="20" />
            Correção Automática de Erros
            <span class="fs-emblema fs-emblema--aviso">Em breve</span>
          </button>
        </li>
      </ul>
      <button class="fs-habilidades-abas-wrapper__seta fs-habilidades-abas-wrapper__seta--dir fs-oculto" id="abaSetaDir" aria-label="Rolar abas para a direita">
        <img src="<?= $fs->getUrl('assets/images/icons/chevron-right.svg') ?>" alt="" width="16" height="16" />
      </button>
    </div>
  </div>
</div>

<!-- CONTEÚDO DAS ABAS -->
<main class="tab-content" id="conteudoAbas">
  <!-- Aba: Ativações -->
  <div class="tab-pane fade show active" id="pane-ativacoes" role="tabpanel" aria-labelledby="tab-ativacoes">
    <!-- Estado Vazio: nenhum plugin ativado ainda -->
    <!-- Para ativar: remover a classe "d-none" deste bloco e adicionar "d-none" no fs-habilidades-grade -->
    <div class="fs-estado-vazio d-none" id="estadoVazioAtivacoes">
      <div class="fs-estado-vazio__icone">
        <img src="<?= $fs->getUrl('assets/images/icons/energy-amber.svg') ?>" alt="" width="28" height="28" />
      </div>
      <div class="fs-estado-vazio__texto">
        <h3 class="fs-estado-vazio__titulo">Nenhum plugin ativado ainda</h3>
        <p class="fs-estado-vazio__desc">Ative seu primeiro plugin agora e ele estará disponível em qualquer site do seu assento — sem chave de licença, sem configuração manual.</p>
      </div>
      <a href="chat.html" class="fs-estado-vazio__cta">
        <img src="<?= $fs->getUrl('assets/images/icons/energy-dark.svg') ?>" alt="" width="18" height="18" />
        Ativar meu primeiro plugin
      </a>
      <div class="fs-estado-vazio__sugestoes">
        <span class="fs-estado-vazio__sugestoes-titulo">Mais ativados pelos clientes FULL</span>
        <div class="fs-estado-vazio__chips">
          <a href="chat.html" class="fs-estado-vazio__chip">
            <img src="<?= $fs->getUrl('assets/images/icons/plugin-elementor.svg') ?>" alt="" width="18" height="18" />
            Elementor PRO
          </a>
          <a href="chat.html" class="fs-estado-vazio__chip">
            <img src="<?= $fs->getUrl('assets/images/icons/plugin-rankmath.svg') ?>" alt="" width="18" height="18" />
            Rank Math PRO
          </a>
          <a href="chat.html" class="fs-estado-vazio__chip">
            <img src="<?= $fs->getUrl('assets/images/icons/plugin-wprocket.svg') ?>" alt="" width="18" height="18" />
            WP Rocket
          </a>
        </div>
      </div>
    </div>

    <div class="fs-habilidades-grade">
      <div class="fs-habilidades-grade__container">
        <!-- Linha 1 -->
        <div class="fs-habilidades-grade__linha">
          <!-- Crocoblock - Ativo -->
          <div class="fs-cartao-plugin">
            <div class="fs-cartao-plugin__info">
              <img class="fs-cartao-plugin__logo" src="<?= $fs->getUrl('assets/images/icons/plugin-crocoblock.svg') ?>" alt="Crocoblock" />
              <div class="fs-cartao-plugin__detalhes">
                <div class="fs-cartao-plugin__titulo-linha">
                  <h3 class="fs-cartao-plugin__titulo" data-plugin-nome="crocoblock" data-plugin-tags="crocoblock jet jetengine">Crocoblock</h3>
                  <span class="fs-cartao-plugin__titulo-sep"></span>
                  <button class="fs-cartao-plugin__addons-btn" data-bs-toggle="modal" data-bs-target="#modalAddons">
                    <img src="<?= $fs->getUrl('assets/images/icons/dashboard-square-add.svg') ?>" alt="" width="16" height="16" />
                    Addons
                  </button>
                </div>
                <p class="fs-cartao-plugin__desc">Use essa extensão para disponibilizar o Crocoblock e os seus plugins em seu site.</p>
              </div>
            </div>
            <div class="fs-cartao-plugin__progresso">
              <div class="fs-cartao-plugin__barra-progresso">
                <div class="fs-cartao-plugin__barra-preenchimento" style="width: 70%"></div>
              </div>
              <span class="fs-cartao-plugin__texto-progresso">7/10 Sites</span>
            </div>
            <div class="fs-cartao-plugin__divisor"></div>
            <div class="fs-cartao-plugin__rodape">
              <button class="fs-acao-secundaria" title="Mais opções">
                <img src="<?= $fs->getUrl('assets/images/icons/square-lock.svg') ?>" alt="" width="14" height="14" />
                <span>Opções</span>
              </button>
              <span class="fs-emblema fs-emblema--sucesso">
                <span class="fs-emblema__ponto"></span>
                Ativo
              </span>
            </div>
          </div>

          <!-- Full.ai - Ativo -->
          <div class="fs-cartao-plugin">
            <div class="fs-cartao-plugin__info">
              <img class="fs-cartao-plugin__logo fs-cartao-plugin__logo--circular" src="<?= $fs->getUrl('assets/images/icons/plugin-fullai.svg') ?>" alt="Full.ai" />
              <div class="fs-cartao-plugin__detalhes">
                <h3 class="fs-cartao-plugin__titulo" data-plugin-nome="full.ai" data-plugin-tags="full ai inteligencia artificial conteudo">Full.ai</h3>
                <p class="fs-cartao-plugin__desc">Com essa extensão você cria conteúdos para blog, Elementor e SEO com a nossa IA.</p>
              </div>
            </div>
            <div class="fs-cartao-plugin__progresso">
              <div class="fs-cartao-plugin__barra-progresso">
                <div class="fs-cartao-plugin__barra-preenchimento" style="width: 30%"></div>
              </div>
              <span class="fs-cartao-plugin__texto-progresso">3/10 Sites</span>
            </div>
            <div class="fs-cartao-plugin__divisor"></div>
            <div class="fs-cartao-plugin__rodape">
              <button class="fs-acao-secundaria" title="Mais opções">
                <img src="<?= $fs->getUrl('assets/images/icons/square-lock.svg') ?>" alt="" width="14" height="14" />
                <span>Opções</span>
              </button>
              <span class="fs-emblema fs-emblema--sucesso">
                <span class="fs-emblema__ponto"></span>
                Ativo
              </span>
            </div>
          </div>

          <!-- Elementor PRO - Ativar -->
          <div class="fs-cartao-plugin">
            <div class="fs-cartao-plugin__info">
              <img class="fs-cartao-plugin__logo" src="<?= $fs->getUrl('assets/images/icons/plugin-elementor-circle.svg') ?>" alt="Elementor PRO" />
              <div class="fs-cartao-plugin__detalhes">
                <h3 class="fs-cartao-plugin__titulo" data-plugin-nome="elementor pro" data-plugin-tags="elementor pro page builder">Elementor PRO</h3>
                <p class="fs-cartao-plugin__desc">Use essa extensão para disponibilizar o Elementor PRO em seu site.</p>
              </div>
            </div>
            <div class="fs-cartao-plugin__progresso">
              <div class="fs-cartao-plugin__barra-progresso">
                <div class="fs-cartao-plugin__barra-preenchimento" style="width: 0%"></div>
              </div>
              <span class="fs-cartao-plugin__texto-progresso">0/10 Sites</span>
            </div>
            <div class="fs-cartao-plugin__divisor"></div>
            <div class="fs-cartao-plugin__rodape">
              <button class="fs-acao-bloqueio">
                <img src="<?= $fs->getUrl('assets/images/icons/square-lock.svg') ?>" alt="" width="14" height="14" />
                Bloquear
              </button>
              <a href="chat.html" class="fs-btn-ativar">
                <img src="<?= $fs->getUrl('assets/images/icons/energy-dark.svg') ?>" alt="" width="18" height="18" />
                Ativar
              </a>
            </div>
          </div>
        </div>

        <!-- Linha 2 -->
        <div class="fs-habilidades-grade__linha">
          <!-- Astra PRO - Ativar -->
          <div class="fs-cartao-plugin">
            <div class="fs-cartao-plugin__info">
              <img class="fs-cartao-plugin__logo" src="<?= $fs->getUrl('assets/images/icons/plugin-astra.svg') ?>" alt="Astra PRO" />
              <div class="fs-cartao-plugin__detalhes">
                <h3 class="fs-cartao-plugin__titulo" data-plugin-nome="astra pro" data-plugin-tags="astra tema theme pro">Astra PRO</h3>
                <p class="fs-cartao-plugin__desc">Use essa extensão para disponibilizar o Astra PRO em seu site.</p>
              </div>
            </div>
            <div class="fs-cartao-plugin__progresso">
              <div class="fs-cartao-plugin__barra-progresso">
                <div class="fs-cartao-plugin__barra-preenchimento" style="width: 0%"></div>
              </div>
              <span class="fs-cartao-plugin__texto-progresso">0/10 Sites</span>
            </div>
            <div class="fs-cartao-plugin__divisor"></div>
            <div class="fs-cartao-plugin__rodape">
              <button class="fs-acao-bloqueio">
                <img src="<?= $fs->getUrl('assets/images/icons/square-lock.svg') ?>" alt="" width="14" height="14" />
                Bloquear
              </button>
              <a href="chat.html" class="fs-btn-ativar">
                <img src="<?= $fs->getUrl('assets/images/icons/energy-dark.svg') ?>" alt="" width="18" height="18" />
                Ativar
              </a>
            </div>
          </div>

          <!-- Full.update - Ativar -->
          <div class="fs-cartao-plugin">
            <div class="fs-cartao-plugin__info">
              <img class="fs-cartao-plugin__logo fs-cartao-plugin__logo--circular" src="<?= $fs->getUrl('assets/images/icons/plugin-fullai.svg') ?>" alt="Full.update" />
              <div class="fs-cartao-plugin__detalhes">
                <h3 class="fs-cartao-plugin__titulo" data-plugin-nome="full.update" data-plugin-tags="full update atualizacao">Full.update</h3>
                <p class="fs-cartao-plugin__desc">Use essa extensão para disponibilizar o Full.update em seu site.</p>
              </div>
            </div>
            <div class="fs-cartao-plugin__progresso">
              <div class="fs-cartao-plugin__barra-progresso">
                <div class="fs-cartao-plugin__barra-preenchimento" style="width: 0%"></div>
              </div>
              <span class="fs-cartao-plugin__texto-progresso">0/10 Sites</span>
            </div>
            <div class="fs-cartao-plugin__divisor"></div>
            <div class="fs-cartao-plugin__rodape">
              <button class="fs-acao-bloqueio">
                <img src="<?= $fs->getUrl('assets/images/icons/square-lock.svg') ?>" alt="" width="14" height="14" />
                Bloquear
              </button>
              <a href="chat.html" class="fs-btn-ativar">
                <img src="<?= $fs->getUrl('assets/images/icons/energy-dark.svg') ?>" alt="" width="18" height="18" />
                Ativar
              </a>
            </div>
          </div>

          <!-- WP Rocket - Bloqueado -->
          <div class="fs-cartao-plugin fs-cartao-plugin--bloqueado">
            <div class="fs-cartao-plugin__info">
              <img class="fs-cartao-plugin__logo" src="<?= $fs->getUrl('assets/images/icons/plugin-wprocket.svg') ?>" alt="WP Rocket" />
              <div class="fs-cartao-plugin__detalhes">
                <h3 class="fs-cartao-plugin__titulo" data-plugin-nome="wp rocket" data-plugin-tags="wp rocket cache velocidade performance">WP Rocket</h3>
                <p class="fs-cartao-plugin__desc">Use essa extensão para disponibilizar o WP Rocket em seu site.</p>
              </div>
            </div>
            <div class="fs-cartao-plugin__progresso">
              <div class="fs-cartao-plugin__barra-progresso">
                <div class="fs-cartao-plugin__barra-preenchimento" style="width: 0%"></div>
              </div>
              <span class="fs-cartao-plugin__texto-progresso">0/10 Sites</span>
            </div>
            <div class="fs-cartao-plugin__divisor"></div>
            <div class="fs-cartao-plugin__rodape">
              <button class="fs-acao-bloqueio fs-acao-bloqueio--desbloquear">
                <img src="<?= $fs->getUrl('assets/images/icons/square-unlock.svg') ?>" alt="" width="14" height="14" />
                Desbloquear
              </button>
              <span class="fs-emblema fs-emblema--perigo">
                <span class="fs-emblema__ponto"></span>
                Bloqueado
              </span>
            </div>
          </div>
        </div>

        <!-- Linha 3 -->
        <div class="fs-habilidades-grade__linha">
          <!-- ACF PRO - Ativar -->
          <div class="fs-cartao-plugin">
            <div class="fs-cartao-plugin__info">
              <img class="fs-cartao-plugin__logo" src="<?= $fs->getUrl('assets/images/icons/plugin-acf.svg') ?>" alt="ACF PRO" />
              <div class="fs-cartao-plugin__detalhes">
                <h3 class="fs-cartao-plugin__titulo" data-plugin-nome="acf pro" data-plugin-tags="acf advanced custom fields campos">ACF PRO</h3>
                <p class="fs-cartao-plugin__desc">Use essa extensão para disponibilizar o ACF PRO em seu site.</p>
              </div>
            </div>
            <div class="fs-cartao-plugin__progresso">
              <div class="fs-cartao-plugin__barra-progresso">
                <div class="fs-cartao-plugin__barra-preenchimento" style="width: 0%"></div>
              </div>
              <span class="fs-cartao-plugin__texto-progresso">0/10 Sites</span>
            </div>
            <div class="fs-cartao-plugin__divisor"></div>
            <div class="fs-cartao-plugin__rodape">
              <button class="fs-acao-bloqueio">
                <img src="<?= $fs->getUrl('assets/images/icons/square-lock.svg') ?>" alt="" width="14" height="14" />
                Bloquear
              </button>
              <a href="chat.html" class="fs-btn-ativar">
                <img src="<?= $fs->getUrl('assets/images/icons/energy-dark.svg') ?>" alt="" width="18" height="18" />
                Ativar
              </a>
            </div>
          </div>

          <!-- WooCommerce PRO - Ativar -->
          <div class="fs-cartao-plugin">
            <div class="fs-cartao-plugin__info">
              <img class="fs-cartao-plugin__logo" src="<?= $fs->getUrl('assets/images/icons/plugin-woocommerce.svg') ?>" alt="WooCommerce PRO" />
              <div class="fs-cartao-plugin__detalhes">
                <h3 class="fs-cartao-plugin__titulo" data-plugin-nome="woocommerce pro" data-plugin-tags="woocommerce loja ecommerce">WooCommerce PRO</h3>
                <p class="fs-cartao-plugin__desc">Use essa extensão para disponibilizar o WooCommerce PRO em seu site.</p>
              </div>
            </div>
            <div class="fs-cartao-plugin__progresso">
              <div class="fs-cartao-plugin__barra-progresso">
                <div class="fs-cartao-plugin__barra-preenchimento" style="width: 0%"></div>
              </div>
              <span class="fs-cartao-plugin__texto-progresso">0/10 Sites</span>
            </div>
            <div class="fs-cartao-plugin__divisor"></div>
            <div class="fs-cartao-plugin__rodape">
              <button class="fs-acao-bloqueio">
                <img src="<?= $fs->getUrl('assets/images/icons/square-lock.svg') ?>" alt="" width="14" height="14" />
                Bloquear
              </button>
              <a href="chat.html" class="fs-btn-ativar">
                <img src="<?= $fs->getUrl('assets/images/icons/energy-dark.svg') ?>" alt="" width="18" height="18" />
                Ativar
              </a>
            </div>
          </div>

          <!-- Rank Math PRO - Ativar -->
          <div class="fs-cartao-plugin">
            <div class="fs-cartao-plugin__info">
              <img class="fs-cartao-plugin__logo" src="<?= $fs->getUrl('assets/images/icons/plugin-rankmath.svg') ?>" alt="Rank Math PRO" />
              <div class="fs-cartao-plugin__detalhes">
                <h3 class="fs-cartao-plugin__titulo" data-plugin-nome="rank math pro" data-plugin-tags="rank math seo rankmath">Rank Math PRO</h3>
                <p class="fs-cartao-plugin__desc">Use essa extensão para disponibilizar o Rank Math PRO em seu site.</p>
              </div>
            </div>
            <div class="fs-cartao-plugin__progresso">
              <div class="fs-cartao-plugin__barra-progresso">
                <div class="fs-cartao-plugin__barra-preenchimento" style="width: 10%"></div>
              </div>
              <span class="fs-cartao-plugin__texto-progresso">1/10 Sites</span>
            </div>
            <div class="fs-cartao-plugin__divisor"></div>
            <div class="fs-cartao-plugin__rodape">
              <button class="fs-acao-bloqueio">
                <img src="<?= $fs->getUrl('assets/images/icons/square-lock.svg') ?>" alt="" width="14" height="14" />
                Bloquear
              </button>
              <a href="chat.html" class="fs-btn-ativar">
                <img src="<?= $fs->getUrl('assets/images/icons/energy-dark.svg') ?>" alt="" width="18" height="18" />
                Ativar
              </a>
            </div>
          </div>
        </div>

        <!-- Sem resultados de busca -->
        <div class="fs-skills-sem-resultado" id="skillsSemResultado">
          <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
            <circle cx="18" cy="18" r="13" stroke="#E9ECF0" stroke-width="2" />
            <path d="M28 28L36 36" stroke="#E9ECF0" stroke-width="2.5" stroke-linecap="round" />
          </svg>
          <p class="fs-skills-sem-resultado__titulo">Nenhum plugin encontrado</p>
          <p class="fs-skills-sem-resultado__desc" id="skillsSemResultadoDesc"></p>
        </div>

        <!-- Paginação -->
        <nav class="fs-paginacao fs-animar fs-animar--surgir">
          <div class="fs-paginacao__numeros">
            <a href="#" class="fs-paginacao__num fs-paginacao__num--ativo">1</a>
            <a href="#" class="fs-paginacao__num">2</a>
            <a href="#" class="fs-paginacao__num">3</a>
            <a href="#" class="fs-paginacao__num">4</a>
            <span class="fs-paginacao__num">...</span>
            <a href="#" class="fs-paginacao__num">10</a>
            <button class="fs-paginacao__proximo">
              <img src="<?= $fs->getUrl('assets/images/icons/chevron-right.svg') ?>" alt="" width="20" height="20" />
            </button>
          </div>
          <span class="fs-paginacao__texto">Página 1 de 10</span>
        </nav>

        <!-- Banner CTA -->
        <div class="fs-cta-destaque fs-animar">
          <div class="fs-cta-destaque__conteudo">
            <div class="fs-cta-destaque__icone">
              <img src="<?= $fs->getUrl('assets/images/icons/crown-white.svg') ?>" alt="" width="32" height="32" />
            </div>
            <div class="fs-cta-destaque__texto">
              <p class="fs-cta-destaque__titulo">Ative todas as extensões em mais sites com o plano PRO</p>
              <p class="fs-cta-destaque__desc">Todos os planos dão acesso às mesmas ativações da pilha. O que muda é quantos assentos (sites) você gerencia.</p>
            </div>
          </div>
          <a href="#" class="fs-cta-destaque__btn" data-bs-toggle="modal" data-bs-target="#modalUpgrade">Ver planos</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Aba: Construtor IA -->
  <div class="tab-pane fade" id="pane-builder" role="tabpanel" aria-labelledby="tab-builder">
    <div class="fs-habilidades-grade">
      <div class="fs-habilidades-grade__container">
        <div class="fs-aba-embreve fs-animar">
          <div class="fs-aba-embreve__cabecalho">
            <div class="fs-aba-embreve__icone">
              <img src="<?= $fs->getUrl('assets/images/icons/embreve-builder.svg') ?>" alt="" width="24" height="24" />
            </div>
            <h3 class="fs-aba-embreve__titulo">Construtor IA</h3>
          </div>
          <p class="fs-aba-embreve__desc">
            Converta qualquer HTML, imagem ou URL em um componente do Elementor funcional com um clique. Cole o código, aponte para uma página ou faça upload de uma imagem — o Construtor IA gera o
            componente pronto para instalar no seu WordPress.
          </p>
          <div class="fs-aba-embreve__cartao">
            <ul class="fs-aba-embreve__lista">
              <li class="fs-aba-embreve__item">
                <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
                <span>HTML → Componente do Elementor</span>
              </li>
              <li class="fs-aba-embreve__item">
                <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
                <span>Imagem → Componente do Elementor</span>
              </li>
              <li class="fs-aba-embreve__item">
                <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
                <span>URL → Clonagem de seção</span>
              </li>
              <li class="fs-aba-embreve__item">
                <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
                <span>Comando de texto → Seção gerada por IA</span>
              </li>
            </ul>
            <a href="#" class="fs-aba-embreve__cta">Garantir meu lugar na fila &nbsp;&rarr;</a>
            <span class="fs-emblema fs-emblema--aviso">🔥 247 pessoas na fila de acesso antecipado</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Aba: Trechos IA -->
  <div class="tab-pane fade" id="pane-snippets" role="tabpanel" aria-labelledby="tab-snippets">
    <div class="fs-habilidades-grade">
      <div class="fs-habilidades-grade__container">
        <div class="fs-aba-embreve fs-animar">
          <div class="fs-aba-embreve__cabecalho">
            <div class="fs-aba-embreve__icone">
              <img src="<?= $fs->getUrl('assets/images/icons/embreve-snippets.svg') ?>" alt="" width="24" height="24" />
            </div>
            <h3 class="fs-aba-embreve__titulo">Trechos IA</h3>
          </div>
          <p class="fs-aba-embreve__desc">
            Gere e aplique trechos de código diretamente no seu WordPress sem precisar editar arquivos. Descreva o que precisa em português — o Copiloto escreve, testa e instala o trecho com
            segurança.
          </p>
          <div class="fs-aba-embreve__cartao">
            <ul class="fs-aba-embreve__lista">
              <li class="fs-aba-embreve__item">
                <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
                <span>Geração por prompt em português</span>
              </li>
              <li class="fs-aba-embreve__item">
                <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
                <span>Biblioteca de trechos prontos</span>
              </li>
              <li class="fs-aba-embreve__item">
                <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
                <span>Instalação com reversão automática</span>
              </li>
              <li class="fs-aba-embreve__item">
                <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
                <span>Versionamento e histórico</span>
              </li>
            </ul>
            <a href="#" class="fs-aba-embreve__cta">Garantir meu lugar na fila &nbsp;&rarr;</a>
            <span class="fs-emblema fs-emblema--aviso">🔥 247 pessoas na fila de acesso antecipado</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Aba: Correção Automática de Erros -->
  <div class="tab-pane fade" id="pane-errorfix" role="tabpanel" aria-labelledby="tab-errorfix">
    <div class="fs-habilidades-grade">
      <div class="fs-habilidades-grade__container">
        <div class="fs-aba-embreve fs-animar">
          <div class="fs-aba-embreve__cabecalho">
            <div class="fs-aba-embreve__icone">
              <img src="<?= $fs->getUrl('assets/images/icons/embreve-errorfix.svg') ?>" alt="" width="24" height="24" />
            </div>
            <h3 class="fs-aba-embreve__titulo">Correção Automática de Erros</h3>
          </div>
          <p class="fs-aba-embreve__desc">
            Detecta e corrige automaticamente os erros mais comuns do WordPress — tela branca, conflitos de extensões, erros 500, falhas de banco de dados — sem precisar abrir o terminal ou
            contatar o suporte.
          </p>
          <div class="fs-aba-embreve__cartao">
            <ul class="fs-aba-embreve__lista">
              <li class="fs-aba-embreve__item">
                <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
                <span>Diagnóstico automático em tempo real</span>
              </li>
              <li class="fs-aba-embreve__item">
                <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
                <span>Correção com um clique</span>
              </li>
              <li class="fs-aba-embreve__item">
                <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
                <span>Biblioteca de 200+ erros conhecidos</span>
              </li>
              <li class="fs-aba-embreve__item">
                <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
                <span>Reversão segura se a correção falhar</span>
              </li>
            </ul>
            <a href="#" class="fs-aba-embreve__cta">Garantir meu lugar na fila &nbsp;&rarr;</a>
            <span class="fs-emblema fs-emblema--aviso">🔥 247 pessoas na fila de acesso antecipado</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>