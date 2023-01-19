=== FULL - Customer ===
Contributors: fullservices, vverner
Requires at least: 5.6
Tested up to: 6.0
Requires PHP: 7.2
Stable tag: 0.0.7
License: GPL v3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

This plugin allows automatic installation and activation of plugins purchased from FULL.

== Description ==
Once your site is integrated into the FULL dashboard, this plugin will be automatically installed on the connected WordPress site.

The FULL - Customers plugin is intended to automate the process of installing and activating purchased plugins and licenses.

Subsequently, the plugin will also allow the FULL support team to make necessary corrections to use the purchased plugins and also extract automatic reports from your website for use in your FULL dashboard.

[FULL customer support](https://full.services/)

== Installation ==
Once your site is integrated into the FULL dashboard, this plugin will be automatically installed on the connected WordPress site.

== Frequently Asked Questions ==

### Why was this plugin installed?

After connecting a site to the FULL dashboard, this plugin is automatically installed and activated to manage your licenses

### Can I delete this plugin?

He can! Your already activated licenses will not be affected, however the panel will lose connection with your website and you will need to install it again to activate new licenses or take full advantage of the FULL control panel

### How are my licenses if I uninstall the plugin?

They remain active for the acquired time.

== Screenshots ==

1. FULL website homepage

== Changelog ==

### 1.0.7

- Backups: Adicionada possibilidade de definir quantos backups manter salvos
- Backups: Modificado retorno da busca de backups para ordem cronologica

### 1.0.6

- Foi adicionado um timeout de 60 segundos para a criação de backups solicitados pelo painel para corrigir o conflito com o plugin WP Rocket

### 1.0.5

- A biblioteca que gera o zip para backup foi revertida para a versão 3.3.3 para maior compatibilidade com o php
- Aberto endpoint para consumo das informações de health
- FS melhorada para limpeza de diretórios

### 1.0.4

**VERSÃO BETA**

- Fluxo de criação, restauração e exclusão de backups internos do site
- Integração do fluxo de backup com o painel FULL.

### 1.0.3

- Criada classe FileSystem para auxiliar na manipulação de arquivos
- Corrigido problema em que alguns casos o FC não conseguia copiar ou mover os arquivos de instalação
- Atualizada formato de download de arquivo remoto

### 1.0.2

- Atualizado namespaces e integração com PHPMD

### 1.0.1

- Atualizado forma de conexão no "Acessa fácil" para condizer com usuário conectado ao painel

### 1.0.0

- Atualizado a URL dos serviços da FULL. para full.services, essa atualização causará incompatibilidade com as versões anteriores do plugin

### 0.2.4

- Inserido link de conexão na listagem de plugins do WordPress

### 0.2.3

- Nesta atualização movemos a página de configuração do plugin para dentro do menu "Configurações" do WordPress
- A tela consentimento de backlink foi removida e agora esta opção deve ser configurada dentro do painel da FULL.

### 0.2.2

- Corrigido o fluxo de conexão onde em alguns casos o navegador autocompletava a senha incorretamente
- Inserida validação da conexão atual do site ao painel sempre que acessar a página de conexão do plugin

### 0.2.1

- Removido o link de "ver detalhes" quando configurado o nome do autor nas definições de whitelabel do painel

### 0.2.0

- Whitelabel

### Versões anteriores a 0.2.0

- Login remoto
- Instalação de plugin adquirido na FULL.
- Configurações de privacidade e backlink
- Assets para repositório do WordPress
- Confirmação de chaves de aplicação disponível (resolver conflito com plugins de segurança)
- Conexão ao painel via plugin
- Criação de conta no painel via plugin
