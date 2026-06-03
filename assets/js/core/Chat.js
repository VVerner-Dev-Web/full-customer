/**
 * Chat (core)
 * Ponto central da interface de chat.
 *
 * Responsabilidades:
 * - Renderizar mensagens (usuário, copilot, loading, error, success)
 * - Expor eventos de envio (`fc/chat/submit`) e ciclo de vida.
 *
 * NÃO conhece menu de skills, dropdowns, validações ou regras de negócio.
 */
export const Chat = {
  root: null,
  container: null,
  input: null,
  button: null,

  _templates: {},

  // ─── Inicialização ────────────────────────────────────────

  attach(root) {
    this.root = root;

    root.addEventListener("fc/fragments/processed", () => {
      root.classList.remove("chating");
      this._setup();
      this._emit("fc/chat/ready");
    });
  },

  // ─── API do Chat para outros módulos ──────────────────────

  setSkillContext(skill) {
    // Apenas muda visual do input para adequar ao contexto da skill
    this.input.placeholder =
      skill.inputPlaceholder ?? "Clique ou digite para ver as opções";
  },

  toggleSubmitButton(isDisabled) {
    this.button?.classList.toggle("desabilitado", isDisabled);
  },

  // ─── Mensagens ────────────────────────────────────────────

  sendUserMessage(text) {
    if (!text) return;
    this.root.classList.add("chating");
    this._appendTemplate(this._templates.user, text);
    this.input.value = "";
  },

  sendCopilotMissingToolOrSkillMessage() {
    const loading = this.sendLoadingMessage();
    setTimeout(() => {
      loading.remove();
      this.sendCopilotMessage(
        "Desculpe, não consegui entender o que você precisa ou não possuo a skill necessária para realizar a operação.",
        "error",
      );
    }, 1500);
  },

  sendCopilotMessage(
    text,
    status = "normal",
    isTerminator = false,
    actions = [],
  ) {
    this.root.classList.add("chating");

    const templateMap = {
      error: this._templates.error,
      success: this._templates.success,
      normal: this._templates.copilot,
    };

    const message = this._appendTemplate(
      templateMap[status] ?? this._templates.copilot,
      text,
    );

    if (isTerminator) {
      this.root.querySelector(".fs-cartao-acao")?.remove();
    }

    if (actions.length && message) {
      this._renderActions(message, actions);
    }
  },

  sendLoadingMessage() {
    this.container.insertAdjacentHTML(
      "beforeend",
      this._templates.loading.innerHTML,
    );
    this._scrollToBottom();
    return this.container.lastElementChild;
  },

  // ─── Privado ──────────────────────────────────────────────

  _setup() {
    const sendBtn = this.root.querySelector("#btnEnviarCopilot");
    this.input = this.root.querySelector("#copilotTexto");

    if (!sendBtn || !this.input) return;

    this.container = this.root.querySelector("#fs-copilot-chat");

    this._templates = {
      user: this.root.querySelector("#chat-user"),
      copilot: this.root.querySelector("#chat-copilot"),
      loading: this.root.querySelector("#chat-loading"),
      error: this.root.querySelector("#chat-error"),
      success: this.root.querySelector("#chat-success"),
    };

    this.button = sendBtn.cloneNode(true);
    sendBtn.replaceWith(this.button);

    this._bindInputEvents();

    this.container.addEventListener("click", (e) => {
      const actionBtn = e.target.closest(".btn-chat-action");
      if (!actionBtn) return;

      const lastMessage = this.container.lastElementChild;
      if (lastMessage && lastMessage.contains(actionBtn)) {
        e.preventDefault();
        this._emit("fc/chat/action", { action: actionBtn.dataset.action });
      }
    });
  },

  _bindInputEvents() {
    this.button.addEventListener("click", (e) => {
      e.preventDefault();
      this._emitSubmit();
    });

    this.input.addEventListener("keyup", (e) => {
      if (e.key === "Enter") this._emitSubmit();
    });
  },

  _emitSubmit() {
    const msg = this.input.value.trim();
    this._emit("fc/chat/submit", { message: msg });
  },

  _appendTemplate(template, content) {
    if (!template || !this.container) return;
    const clone = template.content.cloneNode(true);
    clone.querySelector(".fs-chat__content").innerHTML = content;
    this.container.appendChild(clone);
    this._scrollToBottom();

    return this.container.lastElementChild;
  },

  _renderActions(message, actions) {
    const oldButtons = this.container.querySelectorAll(
      ".btn-chat-action:not([disabled])",
    );
    oldButtons.forEach((btn) => (btn.disabled = true));

    const actionContainer = message.querySelector(".fs-chat-acoes");

    if (!actionContainer) return;

    actionContainer.innerHTML = "";

    for (const { action, label } of actions) {
      const html = `<button class="btn-chat-action" data-action="${action}">${label}</button>`;
      actionContainer.insertAdjacentHTML("beforeend", html);
    }
  },

  _scrollToBottom() {
    if (this.container) this.container.scrollTop = this.container.scrollHeight;
  },

  _emit(eventName, detail = {}) {
    this.root.dispatchEvent(new CustomEvent(eventName, { detail }));
  },
};
