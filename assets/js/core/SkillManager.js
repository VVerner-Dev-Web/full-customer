/**
 * SkillManager
 * Gerenciador central de Skills/Actions.
 */
import { Chat } from "../core/Chat.js";
import { ApiService } from "../utils/ApiService.js";
import { generateId } from "../utils/functions.js";

export const SkillManager = {
  waitingUserPersonalAnswer: false,

  _working: false,
  _root: null,

  _suggestionsEl: null,
  _tagsEl: null,
  _triggerIconEl: null,
  _triggerNameEl: null,
  _menuContainerEl: null,

  _skills: [],
  _activeSkill: null,
  _repository: [],
  _selectedItems: [],

  _middlewares: [],

  init(root) {
    this._root = root;
    this._suggestionsEl = root.querySelector("#copilotSugestoes");
    this._tagsEl = root.querySelector("#copilotTags");
    this._triggerIconEl = root.querySelector("#skillIcone");
    this._triggerNameEl = root.querySelector("#skillNome");
    this._menuContainerEl = root.querySelector("#skillMenuContainer");

    this._bindSkillChange();
    this._bindMenuEvents();
    this._bindInputEvents();
    this._bindChatSubmit(); // Novo listener para execução!
    this._bindSuggestionClick();
    this._bindOutsideClick();

    this._loadAndRenderSkills();
  },

  // ─── Renderização do Menu de Skills ───────────────────────

  async _loadAndRenderSkills() {
    try {
      const response = await ApiService.get("/skills");
      if (!response.success || !response.skills)
        throw new Error("Formato inválido.");

      this._skills = response.skills;
      this._skills.sort((a, b) =>
        a.isAvailable === b.isAvailable ? 0 : a.isAvailable ? -1 : 1,
      );

      const firstAvailable = this._skills.find((s) => s.isAvailable);

      this._menuContainerEl.innerHTML = this._skills
        .map((skill) => {
          const isAtivoClass =
            skill.id === firstAvailable?.id ? "fs-skill-chip__item--ativo" : "";
          const stateClass = skill.isAvailable
            ? "fs-skill-chip__item--available"
            : "fs-skill-chip__item--embreve";
          const badgeHtml = skill.isSoon
            ? `<span class="fs-emblema fs-emblema--aviso fs-emblema--pequeno">Em breve</span>`
            : "";

          return `
          <div data-skill="${skill.id}" class="fs-skill-chip__item ${stateClass} ${isAtivoClass}">
            <div class="fs-skill-chip__item-esq">
              <img src="${skill.imageUrl}" alt="${skill.name}" width="16" height="16" />
              <div>
                <span class="fs-skill-chip__item-nome">${skill.name}</span>
                <span class="fs-skill-chip__item-desc">${skill.shortDescription}</span>
              </div>
            </div>
            ${badgeHtml}
          </div>`;
        })
        .join("");

      if (firstAvailable) {
        this._updateTriggerVisuals(firstAvailable);
        this._dispatchSkillChange(firstAvailable);
      }
    } catch (error) {
      console.error("[SkillManager] Erro ao carregar skills:", error);
    }
  },

  _updateTriggerVisuals(skill) {
    if (this._triggerIconEl) {
      this._triggerIconEl.src = skill.imageUrl;
      this._triggerIconEl.alt = skill.name;
    }
    if (this._triggerNameEl) {
      this._triggerNameEl.textContent = skill.name;
    }
  },

  _dispatchSkillChange(skillData) {
    this._root.dispatchEvent(
      new CustomEvent("fc/chat/skill-changed", {
        detail: { skill: skillData },
      }),
    );
  },

  // ─── Bindings de Eventos ──────────────────────────────────

  _bindMenuEvents() {
    this._menuContainerEl?.addEventListener("click", (e) => {
      const itemEl = e.target.closest(".fs-skill-chip__item--available");
      if (!itemEl) return;

      const skillId = itemEl.dataset.skill;
      const selectedSkill = this._skills.find((s) => s.id === skillId);

      if (!selectedSkill) return;

      this._menuContainerEl
        .querySelectorAll(".fs-skill-chip__item")
        .forEach((el) => {
          el.classList.remove("fs-skill-chip__item--ativo");
        });
      itemEl.classList.add("fs-skill-chip__item--ativo");

      bootstrap.Dropdown.getInstance("#skillGatilho").hide();

      this._updateTriggerVisuals(selectedSkill);
      this._dispatchSkillChange(selectedSkill);
    });
  },

  _bindSkillChange() {
    this._root.addEventListener("fc/chat/skill-changed", async ({ detail }) => {
      const { skill } = detail;

      this._clearUI();

      this._activeSkill = skill;

      Chat.setSkillContext(skill);
    });
  },

  _bindChatSubmit() {
    this._root.addEventListener("fc/chat/submit", async ({ detail }) => {
      if (this._selectedItems.length > 0) {
        Chat.sendUserMessage(
          `Executar ação: ${this._selectedItems.map((p) => p.name).join(", ")}`,
        );
      }
      await this._execute(detail.message);
    });
  },

  _bindInputEvents() {
    const handleInput = () => {
      if (this._activeSkill?.actions) {
        this._filterSuggestions();
      }
    };

    Chat.input.addEventListener("input", handleInput);
    Chat.input.addEventListener("focus", handleInput);
  },

  _bindSuggestionClick() {
    this._suggestionsEl?.addEventListener("click", (e) => {
      const itemEl = e.target.closest(".fs-copilot-sugestao");
      if (!itemEl) return;

      const dataItem = this._activeSkill?.actions.find(
        (p) => p.id == itemEl.dataset.id,
      );
      if (dataItem) this._addTag(dataItem);
    });
  },

  _bindOutsideClick() {
    document.addEventListener("click", (e) => {
      if (!this._suggestionsEl?.classList.contains("ativo")) return;
      const isInside =
        this._suggestionsEl.contains(e.target) || Chat.input.contains(e.target);
      if (!isInside) this._suggestionsEl.classList.remove("ativo");
    });
  },

  // ─── Lógica de UI (Tags e Sugestões Genéricas) ────────────

  _filterSuggestions() {
    const query = Chat.input.value.trim().toLowerCase();
    const matches = query
      ? this._activeSkill.actions.filter((item) =>
          item.name?.toLowerCase().includes(query),
        )
      : this._activeSkill?.actions;

    this._renderSuggestions(matches, query);
  },

  _renderSuggestions(items, query = "") {
    if (!items.length || this.waitingUserPersonalAnswer) {
      this._suggestionsEl.classList.remove("ativo");
      return;
    }

    this._suggestionsEl.innerHTML = items
      .map(
        (item) => `
        <div class="fs-copilot-sugestao" data-id="${item.id}">
          ${item.imageUrl ? `<img class="fs-copilot-sugestao__icone" src="${item.imageUrl}" alt="">` : ""}
          <div class="fs-copilot-sugestao__texto">
            <span class="fs-copilot-sugestao__nome">${this._highlight(item.name, query)}</span>
            ${item.desc ? `<span class="fs-copilot-sugestao__desc">${item.desc}</span>` : ""}
          </div>
        </div>
      `,
      )
      .join("");

    this._suggestionsEl.classList.add("ativo");
  },

  _addTag(item) {
    if (this.waitingUserPersonalAnswer) {
      return;
    }

    if (this._selectedItems.some((i) => i.id === item.id)) return;

    this._selectedItems.push(item);

    const tag = document.createElement("div");
    tag.className = "fs-copilot-tag";
    tag.innerHTML = `
      ${item.imageUrl ? `<img class="fs-copilot-tag__icone" src="${item.imageUrl}" alt="">` : ""}
      <span class="fs-copilot-tag__nome">${item.name}</span>
      <button class="fs-copilot-tag__remover" aria-label="Remover ${item.name}">&times;</button>
    `;

    tag
      .querySelector(".fs-copilot-tag__remover")
      .addEventListener("click", () => {
        this._selectedItems = this._selectedItems.filter(
          (i) => i.id !== item.id,
        );
        tag.remove();
        this._syncButtonState();
      });

    this._tagsEl.appendChild(tag);
    Chat.input.value = "";
    this._suggestionsEl.classList.remove("ativo");
    this._syncButtonState();
  },

  _syncButtonState() {
    if (this.waitingUserPersonalAnswer) {
      Chat.toggleSubmitButton(false);
      return;
    }

    const hasRequirements = this._activeSkill?.actions
      ? this._selectedItems.length > 0
      : Chat.input.value.trim().length > 0;

    this._root
      .querySelector("#copilotInput")
      ?.classList.toggle(
        "fs-copilot-input--com-tags",
        this._selectedItems.length > 0,
      );

    Chat.toggleSubmitButton(!hasRequirements);
  },

  // ─── Execução Genérica ────────────────────────────────────

  addMiddleware(middleware) {
    if (typeof middleware === "function") {
      this._middlewares.push(middleware);
    }
  },

  async _execute(msg) {
    if (this._working) {
      Chat.sendCopilotMessage(
        "Só um pouquinho, ainda estou trabalhando na sua solicitação anterior.",
      );
      return;
    }

    if (
      !this.waitingUserPersonalAnswer &&
      this._activeSkill.actions &&
      this._selectedItems.length === 0
    ) {
      Chat.sendCopilotMissingToolOrSkillMessage();
      return;
    }

    try {
      this._working = true;
      this._clearUI(false);

      const actions =
        this._selectedItems.length > 0 ? this._selectedItems : null;

      if (msg) Chat.sendUserMessage(msg);

      for (const mw of this._middlewares) {
        await mw(this, this._activeSkill, actions, msg);
      }
    } catch (error) {
      console.error("Falha na execução do middleware:", error);
      Chat.sendCopilotMessage("Ocorreu um erro ao processar sua solicitação.");
    } finally {
      this._working = false;
      this._selectedItems = [];
      this._syncButtonState();
    }
  },

  _highlight(text, query) {
    if (!query || !text) return text;
    const safe = query.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
    const regex = new RegExp(`(${safe})`, "gi");
    return text.replace(regex, "<mark>$1</mark>");
  },

  _clearUI(clearSelected = true) {
    if (clearSelected) {
      this._selectedItems = [];
      this._syncButtonState();
    }
    if (this._tagsEl) this._tagsEl.innerHTML = "";
    if (this._suggestionsEl) {
      this._suggestionsEl.innerHTML = "";
      this._suggestionsEl.classList.remove("ativo");
    }
  },
};
