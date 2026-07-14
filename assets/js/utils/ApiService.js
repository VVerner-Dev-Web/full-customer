/**
 * ApiService
 * Wrapper centralizado para requisições à REST API do WordPress.
 * Não carrega lógica de negócio — apenas transporte HTTP.
 */
export const ApiService = {
  async get(endpoint, params = {}) {
    let url = endpoint;
    if (Object.keys(params).length > 0) {
      const searchParams = new URLSearchParams(params);
      url += (url.includes("?") ? "&" : "?") + searchParams.toString();
    }
    return this.fetch(url, { method: "GET" });
  },

  async post(endpoint, data = {}) {
    return this.fetch(endpoint, {
      method: "POST",
      body: JSON.stringify(data),
    });
  },

  async postFormData(endpoint, formData) {
    return this.fetch(endpoint, {
      method: "POST",
      body: formData,
    });
  },

  async fetch(endpoint, options = {}) {
    const { restUrl, nonce } = window.fcData;

    const headers = {
      "X-WP-Nonce": nonce,
      ...options.headers,
    };

    if (options.body instanceof FormData) {
      delete headers["Content-Type"];
    } else if (!headers["Content-Type"]) {
      headers["Content-Type"] = "application/json";
    }

    const response = await fetch(`${restUrl}${endpoint}`, {
      ...options,
      headers: headers,
    });

    if (!response.ok)
      throw new Error(`[ApiService] HTTP ${response.status} em ${endpoint}`);
    return response.json();
  },
};
