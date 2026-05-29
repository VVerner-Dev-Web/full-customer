#!/bin/bash

echo "🚀 Iniciando o build do plugin..."

# 1. Compilar os assets do Vite
echo "📦 Compilando assets..."
npm run build

# 2. Gerar a pasta vendor otimizada para produção
echo "🐘 Instalando dependências PHP..."
composer install --no-dev --optimize-autoloader

# 3. Criar uma pasta temporária para montar o plugin
echo "📁 Criando estrutura temporária..."
rm -rf temp_build
mkdir -p temp_build/full-customer

# 4. Usar o Git para copiar os arquivos do projeto (respeitando o .gitattributes)
# O tar vai extrair o conteúdo do git archive direto para a pasta temporária
git archive HEAD | tar -x -C temp_build/full-customer

# 5. Copiar a pasta vendor (que o git ignorou) para dentro do plugin
echo "🚚 Copiando pasta vendor..."
cp -r vendor temp_build/full-customer/

# 6. Gerar o arquivo .zip final (Versão Windows/PowerShell)
echo "🗜️ Gerando full-customer.zip..."
powershell.exe -nologo -noprofile -command "Compress-Archive -Path temp_build\full-customer -DestinationPath full-customer.zip -Force"

# 7. Limpar a pasta temporária
rm -rf temp_build

echo "✅ Build concluído! Arquivo full-customer.zip gerado com sucesso."
