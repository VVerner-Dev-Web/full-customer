/**
 * ApiService
 * Wrapper centralizado para requisições à REST API do WordPress.
 * Não carrega lógica de negócio — apenas transporte HTTP.
 */
export const ApiService = {
  async get(endpoint) {
    return this.fetch(endpoint, { method: "GET" });
  },

  async post(endpoint, data = {}) {
    return this.fetch(endpoint, {
      method: "POST",
      body: JSON.stringify(data),
    });
  },

  async fetch(endpoint, options = {}) {
    const { restUrl, nonce } = window.fcData;

    const response = await fetch(`${restUrl}${endpoint}`, {
      ...options,
      headers: {
        "Content-Type": "application/json",
        "X-WP-Nonce": nonce,
        ...options.headers,
      },
    });

    if (!response.ok)
      throw new Error(`[ApiService] HTTP ${response.status} em ${endpoint}`);
    return response.json();
  },
};
