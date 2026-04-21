export const Chat = {
  root: null,
  container: null,
  templates: {
    user: null,
    copilot: null,
    loading: null,
    error: null,
    success: null,
  },
  input: null,
  button: null,

  attach(root) {
    this.root = root;
    this.root.addEventListener("full-fragments/processed", () => {
      this.root.classList.remove("chating");
      this._setup();
    });
  },

  _setup() {
    const btn = this.root.querySelector("#btnEnviarCopilot");

    this.input = this.root.querySelector("#copilotTexto");

    if (!btn || !this.input) return;

    this.container = this.root.querySelector("#fs-copilot-chat");
    this.templates.user = this.root.querySelector("#chat-user");
    this.templates.copilot = this.root.querySelector("#chat-copilot");
    this.templates.loading = this.root.querySelector("#chat-loading");
    this.templates.error = this.root.querySelector("#chat-error");
    this.templates.success = this.root.querySelector("#chat-success");

    this.button = btn.cloneNode(true);
    btn.parentNode.replaceChild(this.button, btn);

    this.button.addEventListener("click", (e) => {
      e.preventDefault();
      this._handleSend();
    });

    this.input.addEventListener("keyup", (e) => {
      if (e.key === "Enter") {
        this._handleSend();
      }
    });
  },

  _handleSend() {
    const msg = this.input.value.trim();
    if (!msg) return;

    this.sendUserMessage(msg);
    this.root.classList.add("chating");
    this.input.value = "";

    this.root.dispatchEvent(
      new CustomEvent("chat/messageSent", { detail: { message: msg } }),
    );
  },

  changePlaceholder(msg) {
    this.input.placeholder = msg;
  },

  sendUserMessage(msg) {
    this._append(this.templates.user, msg);
  },

  sendCopilotMessage(msg, status = "normal", isTerminator = false) {
    this.root.classList.add("chating");

    let template = this.templates.copilot;

    if (status === "error") {
      template = this.templates.error;
    }

    if (status === "success") {
      template = this.templates.success;
    }

    this._append(template, msg);

    if (isTerminator) {
      this.root.querySelector(".fs-cartao-acao").remove();
    }
  },

  sendLoadingMessage() {
    this.container.insertAdjacentHTML(
      "beforeend",
      this.templates.loading.innerHTML,
    );
    this.container.scrollTop = this.container.scrollHeight;
    return this.container.lastElementChild;
  },

  _append(template, content) {
    if (!template || !this.container) return;
    const clone = template.content.cloneNode(true);
    clone.querySelector(".fs-chat__content").innerHTML = content;
    this.container.appendChild(clone);
    this.container.scrollTop = this.container.scrollHeight;
  },
};
