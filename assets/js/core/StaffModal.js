import { ActivateProPlugin } from "../middleware/ActivateProPlugin.js";
import { ApiService } from "../utils/ApiService.js";
import { generateId } from "../utils/functions.js";

export const StaffModal = {
  dialog: null,
  buffer: [],
  combo: ["f", "u", "l", "l"],

  init(root) {
    this.dialog = document.querySelector("#full-staff-modal");
    if (!this.dialog) return;

    this.bindEvents();
  },

  bindEvents() {
    document.addEventListener("keydown", (e) => this.handleShortcut(e));

    this.dialog
      .querySelector(".fsm-header button")
      ?.addEventListener("click", () => {
        this.dialog.close();
        document.dispatchEvent(new CustomEvent("fs/modal/closed"));
      });

    this.dialog.addEventListener("submit", (e) => {
      if (e.target.tagName === "FORM") {
        e.preventDefault();
        this.handleFormSubmit(e.target);
      }
    });

    this.dialog.querySelector("#fsm-search").addEventListener("input", (e) => {
      const query = e.target.value;

      this.dialog.querySelectorAll(".fsm-item").forEach((item) => {
        if (item.textContent.toLowerCase().includes(query.toLowerCase())) {
          item.style.display = "block";
        } else {
          item.style.display = "none";
        }
      });
    });

    document.addEventListener("fs/modal/opened", () => this.loadRepository());

    document.addEventListener("fs/modal/closed", () => {
      this.dialog.querySelector(".fsm-repository").innerHTML = "";
      this.dialog.querySelector(".fsm-response").innerHTML = "";
    });
  },

  handleShortcut(e) {
    // Ignore keyboard events when user is typing in inputs or contenteditable fields
    const targetTagName = e.target.tagName;
    if (
      targetTagName === "INPUT" ||
      targetTagName === "TEXTAREA" ||
      e.target.isContentEditable
    ) {
      return;
    }

    if (e.shiftKey) {
      const key = e.key.toLowerCase();
      if (/^[a-z]$/.test(key)) {
        this.buffer.push(key);
        if (this.buffer.length > this.combo.length) this.buffer.shift();

        if (this.buffer.join("") === this.combo.join("")) {
          this.dialog.showModal();
          document.dispatchEvent(new CustomEvent("fs/modal/opened"));
          this.buffer = [];
        }
      }
    } else {
      this.buffer = [];
    }
  },

  async loadRepository() {
    const container = this.dialog.querySelector(".fsm-repository");
    container.innerHTML = "Buscando plugins...";

    try {
      const response = await ApiService.post("/actions/plugins/repository");
      if (!response.success || !response.plugins.length) {
        container.innerHTML = "Nenhum plugin encontrado";
        return;
      }

      container.innerHTML = response.plugins
        .map(
          (item, i) => `
        <div class="fsm-item">
          <input type="checkbox" name="plugins[]" value="${item.plugin}" data-slug="${item.slug}" id="plugin-${i}">   
          <label for="plugin-${i}">${item.name}</label>
        </div>
      `,
        )
        .join("");
    } catch (err) {
      console.log(err);
      container.innerHTML = "Erro ao carregar repositório.";
    }
  },

  async handleFormSubmit(form) {
    const responseContainer = this.dialog.querySelector(".fsm-response");
    const checked = Array.from(
      form.querySelectorAll('input[name="plugins[]"]:checked'),
    );

    if (!checked.length) {
      responseContainer.innerHTML = "Selecione pelo menos um plugin.";
      return;
    }

    const queue = checked.map((el) => el.dataset.slug);
    this.installNext(queue);
  },

  async installNext(queue) {
    if (!queue.length) {
      alert("Todos os plugins finalizados");
      window.location.href = window.fcData?.wpPluginsUrl || "#";
      return;
    }

    const processId = generateId();

    const pluginSlug = queue.shift();
    const responseContainer = this.dialog.querySelector(".fsm-response");
    const logId = `log-${processId}`;

    responseContainer.insertAdjacentHTML(
      "beforeend",
      `<div id="${logId}" class="fsm-log-line"></div>`,
    );
    const logEl = responseContainer.querySelector(`#${logId}`);

    const incrementLog = (msg) => {
      logEl.innerHTML += msg + "<br>";
    };

    try {
      incrementLog("Iniciando instalação...");
      await ActivateProPlugin.processInstallation(processId, pluginSlug, {
        progress: incrementLog,
      });
      incrementLog("✅ Plugin instalado com sucesso!");
    } catch (err) {
      incrementLog(`❌ Erro na instalação: ${err.message}`);
    } finally {
      this.installNext(queue);
    }
  },
};
