/**
 * WelcomeService (core)
 * Exibe a saudação inicial ao usuário.
 *
 * Se a conta não estiver conectada, aplica uma mensagem especial
 * e delega o fluxo de conexão ao ConnectionService (skill).
 */
const GREETINGS = [
  {
    title: "Olá! Como posso te ajudar?",
    subtitle:
      "Escolha uma tarefa ou descreva o que precisa ser automatizado agora.",
  },
  {
    title: "Estou aqui para ajudar!",
    subtitle:
      "Diga-me qual licença precisamos ativar e eu cuido do trabalho pesado.",
  },
];

const DISCONNECTED_GREETING = {
  title: "Vamos ganhar super poderes?",
  subtitle:
    "Precisamos conectar sua conta ao painel da FULL. para que você possa usar o Copilot.",
};

export const WelcomeService = {
  attach(root) {
    if (!root) return;
    this._greet(root);
  },

  _greet(root) {
    const titleEl = root.querySelector(".fs-saudacao__titulo");
    const subtitleEl = root.querySelector(".fs-saudacao__subtitulo p");

    if (!titleEl || !subtitleEl) return;

    const isConnected = Boolean(window.fcData?.connected);

    const { title, subtitle } = isConnected
      ? GREETINGS[Math.floor(Math.random() * GREETINGS.length)]
      : DISCONNECTED_GREETING;

    titleEl.textContent = title;
    subtitleEl.textContent = subtitle;
  },
};
