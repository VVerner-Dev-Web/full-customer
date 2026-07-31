#!/bin/bash

# Abortar imediatamente se algum comando falhar ou se houver variáveis não declaradas
set -euo pipefail

TEMP_DIR="temp_build"
PLUGIN_NAME="full-customer"

# 0. Função de Limpeza (Executada sempre no encerramento ou em caso de erro)
cleanup() {
  echo "🔄 Restaurando ambiente de desenvolvimento PHP e limpando temporários..."
  rm -rf "$TEMP_DIR"
  composer install --quiet
}
trap cleanup EXIT

echo "🚀 Iniciando o build do plugin..."

# 1. Verificar alterações pendentes no Git
if ! git diff-index --quiet HEAD --; then
  echo "⚠️  ATENÇÃO: Existem alterações não commitadas no repositório!"
  echo "   Como o 'git archive' utiliza o estado de HEAD, modificações não commitadas NÃO entrarão no arquivo .zip."
  if [ -t 0 ]; then
    read -p "Deseja continuar mesmo assim? (y/N) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
      echo "❌ Build cancelado pelo usuário."
      exit 1
    fi
  else
    echo "ℹ️  Executando em modo não-interativo. Continuando..."
  fi
fi

# 2. Extrair versão atual do plugin a partir do arquivo principal PHP
VERSION=$(grep -i "Version:" full-customer.php | head -n 1 | awk '{print $3}' | tr -d '\r')
OUTPUT_ZIP="${PLUGIN_NAME}@${VERSION}.zip"

echo "📌 Versão detectada: $VERSION"
echo "📦 Arquivo final: $OUTPUT_ZIP"

# 3. Garantir pacotes Node (apenas se node_modules não existir ou se forçar --fresh)
if [ ! -d "node_modules" ] || [[ "${1:-}" == "--fresh" ]]; then
  echo "📦 Instalando dependências Node..."
  npm ci
fi

# 4. Compilar assets do Vite
echo "📦 Compilando assets do Vite..."
npm run build

# 5. Gerar pasta vendor otimizada para produção
echo "🐘 Instalando dependências PHP de produção..."
composer install --no-dev --optimize-autoloader --classmap-authoritative --quiet

# 6. Montar estrutura temporária do plugin
echo "📁 Criando estrutura de arquivos para o pacote..."
rm -rf "$TEMP_DIR"
mkdir -p "$TEMP_DIR/$PLUGIN_NAME"

# Respeita o .gitattributes para exportar o projeto base limpo
git archive HEAD | tar -x -C "$TEMP_DIR/$PLUGIN_NAME"

# Copiar diretórios ignorados pelo Git (Vendor de produção e assets compilados)
echo "🚚 Copiando vendor e assets compilados..."
mkdir -p "$TEMP_DIR/$PLUGIN_NAME/assets"
cp -r vendor "$TEMP_DIR/$PLUGIN_NAME/"
cp -r assets/dist "$TEMP_DIR/$PLUGIN_NAME/assets/"

# 7. Gerar arquivo ZIP final
echo "🗜️ Gerando $OUTPUT_ZIP..."
cd "$TEMP_DIR"
npx bestzip "../$OUTPUT_ZIP" "$PLUGIN_NAME/"
cd ..

echo "✅ Build concluído com sucesso! Arquivo $OUTPUT_ZIP gerado."
