import "shepherd.js/dist/css/shepherd.css";
import Shepherd from "shepherd.js";

export const Tutorial = {
  _tourInstance: null,

  attach(root) {
    if (!root) return;

    // Dispara o tour quando o chat estiver pronto
    root.addEventListener("fc/chat/ready", () => {
      const isCompleted = localStorage.getItem("fc_tour_completed") === "true";
      const isResuming = localStorage.getItem("fc_tour_resume") === "true";

      if (!isCompleted || isResuming) {
        this._startTour();
      }
    });

    const manualStartButton = root.querySelector("#btnTutorial");
    if (manualStartButton) {
      manualStartButton.addEventListener("click", () => {
        localStorage.removeItem("fc_tour_resume");
        localStorage.removeItem("fc_tour_completed");
        this._startTour(true);
      });
    }
  },

  _markAsCompleted() {
    const isResuming = localStorage.getItem("fc_tour_resume") === "true";
    if (!isResuming) {
      localStorage.setItem("fc_tour_completed", "true");
    }
  },

  _initTour(forceRestart = false) {
    if (this._tourInstance) {
      if (forceRestart) {
        this._tourInstance.cancel();
        this._tourInstance = null;
      } else {
        return;
      }
    }

    this._tourInstance = new Shepherd.Tour({
      defaultStepOptions: {
        cancelIcon: {
          enabled: true,
        },
        classes: "fc-tutorial-theme",
        scrollTo: { behavior: "smooth", block: "center" },
      },
      useModalOverlay: true,
    });

    this._tourInstance.on("cancel", () => this._markAsCompleted());
    this._tourInstance.on("complete", () => this._markAsCompleted());

    this._setupSteps();
  },

  _setupSteps() {
    const IS_USER_CONNECTED = Boolean(window.fcData?.connected);
    const isResuming = localStorage.getItem("fc_tour_resume") === "true";

    if (isResuming && IS_USER_CONNECTED) {
      localStorage.removeItem("fc_tour_resume");
    }

    // ==========================================
    // BLOCO 1: BOAS VINDAS (Pula se estiver resumindo)
    // ==========================================
    if (!isResuming) {
      this._tourInstance.addStep({
        id: "step-welcome",
        title: "Olá! Boas-vindas à FULL. AI 👋",
        text: "Vou te mostrar rapidamente como usar seus novos superpoderes. Aqui você poderá gerenciar licenças, ativar plugins e automatizar tarefas.",
        buttons: [
          {
            action() {
              return this.cancel();
            },
            classes: "shepherd-button-secondary",
            text: "Pular Tour",
          },
          {
            action() {
              return this.next();
            },
            text: "Vamos lá!",
          },
        ],
      });

      this._tourInstance.addStep({
        id: "step-skill-intro",
        title: "Conheça os Modelos",
        arrow: true,
        text: "Os Modelos são grupos de Inteligências Artificiais treinados para tarefas específicas. Você pode alternar entre eles dependendo do que precisa fazer no momento.",
        attachTo: {
          element: "#skillSeletor",
          on: "right",
        },
        when: {
          show: () => {
            const el = document.querySelector("#skillSeletor");
            if (el) el.style.pointerEvents = "none"; // Desabilita o clique
          },
          hide: () => {
            const el = document.querySelector("#skillSeletor");
            if (el) el.style.pointerEvents = ""; // Limpa a regra para voltar ao normal
          },
        },

        buttons: [
          {
            action() {
              return this.back();
            },
            classes: "shepherd-button-secondary",
            text: "Voltar",
          },
          {
            action() {
              return this.next();
            },
            text: "Avançar",
          },
        ],
      });
    }

    // ==========================================
    // BLOCO 2: CONEXÃO (Cenário B - Não conectado)
    // ==========================================
    if (!IS_USER_CONNECTED) {
      this._tourInstance.addStep({
        id: "step-connect-intro",
        title: "Sua primeira missão",
        text: "Para começar a mágica, precisamos vincular sua conta. Por enquanto, mantenha o modelo 'Conectar' selecionado.",
        attachTo: {
          element: "#skillSeletor",
          on: "right",
        },
        when: {
          show: () => {
            const el = document.querySelector("#skillSeletor");
            if (el) el.style.pointerEvents = "none"; // Desabilita o clique
          },
          hide: () => {
            const el = document.querySelector("#skillSeletor");
            if (el) el.style.pointerEvents = ""; // Limpa a regra para voltar ao normal
          },
        },
        buttons: [
          {
            action() {
              return this.back();
            },
            classes: "shepherd-button-secondary",
            text: "Voltar",
          },
          {
            action() {
              return this.next();
            },
            text: "Avançar",
          },
        ],
      });

      this._tourInstance.addStep({
        id: "step-connect-chat",
        title: "Onde a conversa acontece",
        text: "Clique aqui na barra de digitação para ver as ações rápidas que preparei para você.",
        arrow: true,
        attachTo: {
          element: "#copilotTexto",
          on: "bottom",
        },
        advanceOn: {
          selector: "#copilotTexto",
          event: "click",
        },
        buttons: [
          {
            action() {
              return this.back();
            },
            classes: "shepherd-button-secondary",
            text: "Voltar",
          },
        ],
      });

      this._tourInstance.addStep({
        id: "step-connect-select",
        title: "Inicie a conexão",
        text: "Excelente! Agora clique em 'Conectar Conta FULL' nesta lista.",
        arrow: true,
        attachTo: {
          element: ".fs-copilot-sugestao[data-id='connectAccount']",
          on: "bottom",
        },
        advanceOn: {
          selector: ".fs-copilot-sugestao[data-id='connectAccount']",
          event: "click",
        },
        buttons: [
          {
            action() {
              return this.back();
            },
            classes: "shepherd-button-secondary",
            text: "Voltar",
          },
        ],
      });

      this._tourInstance.addStep({
        id: "step-connect-send",
        title: "Mande o comando",
        text: "Agora é só clicar na setinha para enviar o comando e prosseguirmos.",
        arrow: true,
        attachTo: {
          element: "#btnEnviarCopilot",
          on: "bottom", // Ajuste conforme ficar melhor na UI
        },
        advanceOn: {
          selector: "#btnEnviarCopilot",
          event: "click",
        },
        buttons: [
          {
            action() {
              return this.back();
            },
            classes: "shepherd-button-secondary",
            text: "Voltar",
          },
        ],
      });

      this._tourInstance.addStep({
        id: "step-connect-email",
        title: "Confirme sua identidade",
        text: "Insira o e-mail que você usou na compra das licenças e clique em enviar. Quando a página recarregar, estarei te esperando para o próximo passo!",
        attachTo: {
          element: ".fs-cartao-acao",
          on: "top",
        },
        // 1. Remova o advanceOn. Nós vamos assumir o controle total no "show".
        when: {
          show: () => {
            // Função centralizada e síncrona para garantir o salvamento imediato
            this._saveResumeState = () => {
              localStorage.setItem("fc_tour_resume", "true");
              this._tourInstance.hide();
            };

            // Escutador 1: Tecla Enter
            this._handleEnterPress = (e) => {
              if (e.key === "Enter") {
                this._saveResumeState();
              }
            };

            // Escutador 2: Clique no Botão
            this._handleBtnClick = () => {
              this._saveResumeState();
            };

            // Anexando os eventos assim que o passo aparece
            document.addEventListener("keydown", this._handleEnterPress);

            const btn = document.querySelector("#btnEnviarCopilot");
            if (btn) {
              btn.addEventListener("click", this._handleBtnClick);
            }
          },

          hide: () => {
            // No hide, fazemos apenas a faxina (remover os listeners)
            if (this._handleEnterPress) {
              document.removeEventListener("keydown", this._handleEnterPress);
            }

            const btn = document.querySelector("#btnEnviarCopilot");
            if (btn && this._handleBtnClick) {
              btn.removeEventListener("click", this._handleBtnClick);
            }
          },
        },
        buttons: [
          {
            action() {
              return this.back();
            },
            classes: "shepherd-button-secondary",
            text: "Voltar",
          },
        ],
      });
    }

    // ==========================================
    // BLOCO 3: ATIVAÇÕES (Cenário A ou Resumo)
    // ==========================================
    else {
      // Título muda dependendo se ele acabou de conectar ou se já estava
      const firstStepTitle = isResuming
        ? "Conta conectada! 🎉"
        : "Hora de Ativar";
      const firstStepText = isResuming
        ? "Parabéns, agora você tem superpoderes! Vamos testá-los ativando um plugin. Mantenha o modelo 'Ativações' selecionado."
        : "Como sua conta já está conectada, podemos ativar plugins direto por aqui. Mantenha o modelo 'Ativações' selecionado.";

      this._tourInstance.addStep({
        id: "step-action-intro",
        title: firstStepTitle,
        text: firstStepText,
        attachTo: {
          element: "#skillSeletor",
          on: "right",
        },
        when: {
          show: () => {
            const el = document.querySelector("#skillSeletor");
            if (el) el.style.pointerEvents = "none"; // Desabilita o clique
          },
          hide: () => {
            const el = document.querySelector("#skillSeletor");
            if (el) el.style.pointerEvents = ""; // Limpa a regra para voltar ao normal
          },
        },
        buttons: [
          {
            action() {
              return this.back();
            },
            classes: "shepherd-button-secondary",
            text: "Voltar",
          },
          {
            action() {
              return this.next();
            },
            text: "Avançar",
          },
        ],
      });

      this._tourInstance.addStep({
        id: "step-action-chat",
        title: "O que vamos ativar?",
        text: "Com o modelo pronto, clique na área de texto para ver os plugins disponíveis para você.",
        arrow: true,
        attachTo: {
          element: "#copilotTexto",
          on: "bottom",
        },
        advanceOn: {
          selector: "#copilotTexto",
          event: "click",
        },
        buttons: [
          {
            action() {
              return this.back();
            },
            classes: "shepherd-button-secondary",
            text: "Voltar",
          },
        ],
      });

      this._tourInstance.addStep({
        id: "step-action-plugin",
        title: "Escolha o plugin",
        text: "Selecione na lista qual plugin você deseja instalar e ativar agora.",
        arrow: true,
        attachTo: {
          element: "#copilotSugestoes",
          on: "top",
        },
        advanceOn: {
          selector: "#copilotSugestoes",
          event: "click",
        },
        buttons: [
          {
            action() {
              return this.back();
            },
            classes: "shepherd-button-secondary",
            text: "Voltar",
          },
        ],
      });

      this._tourInstance.addStep({
        id: "step-action-send",
        title: "Tudo pronto!",
        text: "Clique em enviar e observe a mágica acontecer! Você pode usar esse Copilot sempre que precisar poupar tempo.",
        arrow: true,
        attachTo: {
          element: "#btnEnviarCopilot",
        },
        advanceOn: {
          selector: "#btnEnviarCopilot",
          event: "click",
        },
        buttons: [
          {
            action() {
              return this.back();
            },
            classes: "shepherd-button-secondary",
            text: "Voltar",
          },
        ],
      });
    }
  },

  _startTour(forceRestart = false) {
    this._initTour(forceRestart);

    if (!this._tourInstance.isActive()) {
      this._tourInstance.start();
    }
  },
};
