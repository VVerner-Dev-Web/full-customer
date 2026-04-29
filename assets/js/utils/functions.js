/**
 * Gera um ID único e persistente.
 * Tenta usar o padrão UUID v4 nativo, com fallback para
 * uma combinação de timestamp e aleatoriedade.
 */
export function generateId() {
  // Verifica se a API de criptografia e o método existem
  if (typeof crypto !== "undefined" && crypto.randomUUID) {
    return crypto.randomUUID();
  }

  // Fallback: Base 36 do timestamp + parte aleatória
  // O prefixo 'f' garante que o ID não comece com número (bom para seletores CSS)
  const timestamp = Date.now().toString(36);
  const randomPart = Math.random().toString(36).substring(2, 9);

  return `f-${timestamp}-${randomPart}`;
}
