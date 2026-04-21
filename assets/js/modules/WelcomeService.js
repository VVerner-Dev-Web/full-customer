import { Chat } from "./Chat.js";
import { ApiService } from "./ApiService.js";
import { ConnectionService } from "./ConnectionService.js";

export const WelcomeService = {
  root: null,

  greetings: [
    {
      title: "Olá! Como posso te ajudar?",
      subtitle:
        "Escolha uma tarefa ou descreva o que precisa ser automatizado agora.",
    },
    {
      title: "Oi! Vamos criar algo incrível hoje juntos?",
      subtitle:
        "Estou pronto para gerar código limpo e widgets poderosos para o seu projeto.",
    },
    {
      title: "Estava ansioso esperando você!",
      subtitle:
        "Que tal começarmos otimizando suas rotinas ou refatorando algumas classes?",
    },
    {
      title: "Estou aqui para ajudar!",
      subtitle:
        "Diga-me qual licença precisamos ativar e eu cuido do trabalho pesado.",
    },
  ],

  attach(root) {
    if (!root) return;

    this.root = root;
    this.root.addEventListener(
      "full-fragments/processed",
      this.sayHi.bind(this),
    );
  },

  sayHi() {
    const titleEl = this.root.querySelector(".fs-saudacao__titulo");
    const subtitleEl = this.root.querySelector(".fs-saudacao__subtitulo p");

    if (!titleEl || !subtitleEl) return;

    let hi = this.getRandomGreeting();

    const isConnected = typeof fcData !== "undefined" && fcData.connected;

    if (!isConnected) {
      hi = {
        title: "Vamos ganhar super poderes?",
        subtitle:
          "Precisamos conectar sua conta ao painel da FULL. para que você possa usar o Copilot.",
      };

      ConnectionService.init();
    }

    titleEl.textContent = hi.title;
    subtitleEl.textContent = hi.subtitle;
  },

  getRandomGreeting() {
    const index = Math.floor(Math.random() * this.greetings.length);
    return { ...this.greetings[index] };
  },
};
