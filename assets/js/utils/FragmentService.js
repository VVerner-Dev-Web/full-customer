/**
 * FragmentService
 * Busca fragmentos HTML renderizados pelo servidor.
 * Não manipula DOM — apenas retorna os dados.
 */
export const FragmentService = {
  async fetch(requests) {
    const { restUrl, nonce } = window.fcData;

    const payload = {
      fragments: Object.fromEntries(
        requests.map((r) => [r.fragment, r.args ?? {}])
      ),
    };

    const response = await fetch(`${restUrl}/fragments`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-WP-Nonce": nonce,
      },
      body: JSON.stringify(payload),
    });

    if (!response.ok) {
      throw new Error(`[FragmentService] HTTP ${response.status}`);
    }

    return response.json();
  },
};
