export const FragmentService = {
  async fetchFragments(requests) {
    const payload = { fragments: {} };
    requests.forEach(
      (req) => (payload.fragments[req.fragment] = req.args || {}),
    );

    const response = await fetch(`${fcData.restUrl}/fragments`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-WP-Nonce": fcData.nonce,
      },
      body: JSON.stringify(payload),
    });

    return await response.json();
  },
};
