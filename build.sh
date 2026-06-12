#!/bin/bash

echo "🚀 Iniciando o build do plugin..."

# (Opcional, mas recomendado) Garantir que pacotes node existam
echo "📦 Instalando dependências Node..."
npm install

# 1. Compilar os assets do Vite
echo "📦 Compilando assets..."
npm run build

# 2. Gerar a pasta vendor otimizada para produção
echo "🐘 Instalando dependências PHP (Sem pacotes de dev)..."
composer install --no-dev --optimize-autoloader

# 3. Criar uma pasta temporária para montar o plugin
echo "📁 Criando estrutura temporária..."
rm -rf temp_build
mkdir -p temp_build/full-customer
mkdir -p temp_build/full-customer/assets

# 4. Usar o Git para copiar os arquivos base (respeitando o .gitattributes)
git archive HEAD | tar -x -C temp_build/full-customer

# 5. Copiar os diretórios ignorados pelo Git, mas necessários em produção
echo "🚚 Copiando vendor e assets compilados..."
cp -r vendor temp_build/full-customer/
cp -r assets/dist temp_build/full-customer/assets/ 

# 6. Gerar o arquivo .zip final 
echo "🗜️ Gerando full-customer.zip..."
cd temp_build
npx bestzip ../full-customer.zip full-customer/
cd ..

# 7. Limpar a pasta temporária
rm -rf temp_build

# 8. Restaurar estado de dev do Composer
echo "🔄 Restaurando ambiente de desenvolvimento PHP..."
composer install --quiet

echo "✅ Build concluído! Arquivo full-customer.zip gerado com sucesso."
