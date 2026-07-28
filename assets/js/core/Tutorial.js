import Shepherd from "shepherd.js";

export const Tutorial = {
  _tourInstance: null,

  attach(root) {
    if (!root) return;

    root.addEventListener("fc/chat/ready", () => {
      const isCompleted = localStorage.getItem("fc_tour_completed") === "true";
      const isResuming = localStorage.getItem("fc_tour_resume") === "true";

      if (!isCompleted || isResuming) {
        this._startTour();
      }
    });

    root.addEventListener("fc/chat/reset", () => {
      const isCompleted = localStorage.getItem("fc_tour_completed") === "true";
      const isResuming = localStorage.getItem("fc_tour_resume") === "true";

      if (!isCompleted || isResuming) {
        this._startTour(true);
      }
    });

    root.addEventListener("click", (e) => {
      const manualStartButton = e.target.closest("#btnTutorial");
      if (manualStartButton) {
        localStorage.removeItem("fc_tour_resume");
        localStorage.removeItem("fc_tour_completed");
        this._startTour(true);
      }
    });
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
        id: "step-models-intro",
        title: "Conheça os Modelos",
        arrow: true,
        text: "Os Modelos são grupos de agentes de IA treinados para tarefas específicas. Você pode alternar entre eles dependendo do que precisa fazer no momento.",
        attachTo: {
          element: "#modeloGatilho",
          on: "top",
        },
        when: {
          show: () => {
            const el = document.querySelector("#modeloGatilho");
            if (el) el.style.pointerEvents = "none"; // Desabilita o clique
          },
          hide: () => {
            const el = document.querySelector("#modeloGatilho");
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
        id: "step-agents-intro",
        title: "Conheça os Agentes",
        arrow: true,
        text: "Cada agente de IA foi especialmente treinado para uma tarefa ou contexto específico. Para conectar a conta, vamos manter este aqui.",
        attachTo: {
          element: "#agenteGatilho",
          on: "top",
        },
        when: {
          show: () => {
            const el = document.querySelector("#agenteGatilho");
            if (el) el.style.pointerEvents = "none"; // Desabilita o clique
          },
          hide: () => {
            const el = document.querySelector("#agenteGatilho");
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
        text: "Para começar a mágica, precisamos vincular sua conta. Já deixei o Modelo e Agente pré-configurado para você, vamos lá?",
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
          element: ".fs-copilot-input__sugestoes",
          on: "top",
        },
        when: {
          show: () => {
            this._preventDropdownClose = (e) => {
              e.preventDefault(); // Impede o Bootstrap de fechar a lista
            };

            document.addEventListener(
              "hide.bs.dropdown",
              this._preventDropdownClose,
            );

            this._handleConnectClick = (e) => {
              const clickedTarget = e.target.closest(
                ".fs-copilot-sugestao[data-id='connectAccount']",
              );

              if (clickedTarget) {
                setTimeout(() => {
                  this._tourInstance.next();
                }, 100);
              }
            };

            document.addEventListener("click", this._handleConnectClick);
          },
          hide: () => {
            if (this._preventDropdownClose) {
              document.removeEventListener(
                "hide.bs.dropdown",
                this._preventDropdownClose,
              );
            }

            if (this._handleConnectClick) {
              document.removeEventListener(
                "click",
                this._handleConnectClick,
              );
            }

            const inputEl = document.querySelector("#copilotTexto");
            if (inputEl && typeof bootstrap !== "undefined" && bootstrap.Dropdown) {
              const dropdown = bootstrap.Dropdown.getOrCreateInstance(inputEl);
              if (dropdown) {
                dropdown.hide();
              }
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

      this._tourInstance.addStep({
        id: "step-connect-email",
        title: "Confirme sua identidade",
        text: "Insira o e-mail que você usou na compra das licenças e clique em enviar. Quando a página recarregar, estarei te esperando para o próximo passo!",
        attachTo: {
          element: ".fs-cartao-acao",
          on: "top",
        },
        when: {
          show: () => {
            this._saveResumeState = () => {
              localStorage.setItem("fc_tour_resume", "true");
              this._tourInstance.hide();
            };

            this._handleEnterPress = (e) => {
              if (e.key === "Enter") {
                this._saveResumeState();
              }
            };

            this._handleBtnClick = () => {
              this._saveResumeState();
            };

            document.addEventListener("keydown", this._handleEnterPress);

            const btn = document.querySelector("#btnEnviarCopilot");
            if (btn) {
              btn.addEventListener("click", this._handleBtnClick);
            }
          },

          hide: () => {
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
        ? "Parabéns, agora você tem superpoderes! Vamos testá-los ativando um plugin. Mantenha o modelo 'Plugins' selecionado."
        : "Como sua conta já está conectada, podemos ativar plugins direto por aqui. Mantenha o modelo 'Plugins' selecionado.";

      this._tourInstance.addStep({
        id: "step-action-intro",
        title: firstStepTitle,
        text: firstStepText,
        attachTo: {
          element: "#modeloGatilho",
          on: "top",
        },
        when: {
          show: () => {
            const el = document.querySelector("#modeloGatilho");
            if (el) el.style.pointerEvents = "none"; // Desabilita o clique
          },
          hide: () => {
            const el = document.querySelector("#modeloGatilho");
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
        text: "Com o modelo pronto, vamos escolher nosso agente especializado no plugin que você deseja ativar.",
        arrow: true,
        attachTo: {
          element: "#agenteGatilho",
          on: "top",
        },
        advanceOn: {
          selector: "#agenteGatilho",
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
        text: "Selecione na lista o agente adequado",
        arrow: true,
        attachTo: {
          element: "#agenteMenuContainer",
          on: "right",
        },
        when: {
          show: () => {
            this._preventDropdownClose = (e) => {
              e.preventDefault(); // Impede o Bootstrap de fechar a lista
            };

            document.addEventListener(
              "hide.bs.dropdown",
              this._preventDropdownClose,
            );

            this._handleConnectClick = (e) => {
              const clickedTarget = e.target.closest("[data-agent]");

              if (clickedTarget) {
                setTimeout(() => {
                  this._tourInstance.next();
                }, 100);
              }
            };

            document.addEventListener("click", this._handleConnectClick);
          },
          hide: () => {
            if (this._preventDropdownClose) {
              document.removeEventListener(
                "hide.bs.dropdown",
                this._preventDropdownClose,
              );
            }

            if (this._handleConnectClick) {
              document.removeEventListener(
                "click",
                this._handleConnectClick,
              );
            }

            const gatilhoEl = document.querySelector("#agenteGatilho");
            if (gatilhoEl && typeof bootstrap !== "undefined" && bootstrap.Dropdown) {
              const dropdown = bootstrap.Dropdown.getOrCreateInstance(gatilhoEl);
              if (dropdown) {
                dropdown.hide();
              }
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

      this._tourInstance.addStep({
        id: "step-action-select",
        title: "Escolha de ação",
        text: "Por fim, você só precisa agora definir qual ação o agente especializado deve fazer. Neste caso, vamos usar a opção de Ativar",
        arrow: true,
        attachTo: {
          element: ".fs-copilot-input__sugestoes",
          on: "top",
        },
        when: {
          show: () => {
            const inputEl = document.querySelector("#copilotTexto");
            if (inputEl) {
              inputEl.focus();
            }

            this._preventDropdownClose = (e) => {
              e.preventDefault(); // Impede o Bootstrap de fechar a lista
            };

            document.addEventListener(
              "hide.bs.dropdown",
              this._preventDropdownClose,
            );

            this._handleConnectClick = (e) => {
              const clickedTarget = e.target.closest(".fs-copilot-sugestao");

              if (clickedTarget) {
                this._tourInstance.next();
              }
            };

            document.addEventListener("click", this._handleConnectClick, true);
          },
          hide: () => {
            if (this._preventDropdownClose) {
              document.removeEventListener(
                "hide.bs.dropdown",
                this._preventDropdownClose,
              );
            }

            if (this._handleConnectClick) {
              document.removeEventListener(
                "click",
                this._handleConnectClick,
                true,
              );
            }

            const inputEl = document.querySelector("#copilotTexto");
            if (inputEl && typeof bootstrap !== "undefined" && bootstrap.Dropdown) {
              const dropdown = bootstrap.Dropdown.getOrCreateInstance(inputEl);
              if (dropdown) {
                dropdown.hide();
              }
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

      this._tourInstance.addStep({
        id: "step-action-send",
        title: "Tudo pronto!",
        text: "Clique em enviar e observe a mágica acontecer! Você pode usar esse Copilot sempre que precisar ativa um plugin PRO em seu site.",
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
