export const ApiService = {
  async post(endpoint, data) {
    try {
      const response = await fetch(`${window.fcData.restUrl}${endpoint}`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-WP-Nonce": window.fcData.nonce,
        },
        body: JSON.stringify(data),
      });

      if (!response.ok) throw new Error(`Erro na API: ${response.status}`);

      return await response.json();
    } catch (error) {
      return { success: false, message: error.message };
    }
  },
};
