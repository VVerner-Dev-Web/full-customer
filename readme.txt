=== FULL - Customer ===
Contributors: fullservices, vverner
Requires at least: 6.0
Tested up to: 6.3
Requires PHP: 7.4
Stable tag: 2.4.1
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

## 2.4.1

- Mudanças visuais na tela de extensões
- Correção de bug que ao desativar os templates, o cloud não funcionava dentro do construtor Elementor.

## 2.4.0

- FULL.Security: nova funcionalidade pro para clientes FULL. Um firewall robusto e eficiente para o bloqueio da maioria dos bots que atrapalham o dia a dia dos sites WordPress.
- Controle de widgets da FULL dentro do site.

## 2.3.2

- FULL.templates: adicionado filtro por "segmentos" do painel

## 2.3.1

- Foi atualizado o modelo que cria o diretório temporário de arquivos para usar o próprio site ao invés do sistema.

## 2.3

- Correções de segurança relacionadas a autenticação de usuários e instalações dos plugins
- Atualizado versão da biblioteca que manipula arquivos .zip

## 2.2.3

- Corrigido rota de login que em alguns casos não redirecionava para o painel administrativo
- Corrigido posicionamento do popup da FULL.ai que se deslocava em alguns casos

## 2.2.2

- Corrigido bug em que alguns casos não abria o popup da FULL.ai dentro do Elementor

## 2.2.1

- Atualizado para suporte a instalação de dependências relacionadas a plugins no FULL. templates 
- Corrigido bug em que o botão da AI sumia em alguns casos dentro do Elementor
- Alterado o prompt sugerido da AI para placeholder
- Atualizado compatibilidade com funções até PHP 8.2
- Novidade! FULL.clone para clonar páginas e posts do WordPress.

## 2.2

- FULL.ai lançada e disponível para uso!

## 2.1.2

- Otimizado o fluxo de ativação remota para usar o diretório temporário do servidor ao invés do FC-temp
- Compatibilidade com php 7.4

## 2.1.1

- FULL. Updates
- Corrigido algumas incompatibilidades com versões antigas do plugin
- Adicionado verificação de arquivo de notas
- Definido teste de exclusão durante a instalação de um plugin remoto
- Corrigido retorno de funções
- Ajustado regra para definir o diretório do plugin durante a instalação remota
- Paginação nos resultados dos templates e cloud
- Importação de packs

## 2.1.0

- Implementado proxy para consulta do status do site para atualizações no painel da FULL.

## 2.0.9.1

- Corrigindo incompatibilidade com PHP 7.4 no template single que poderia impedir o carregamento do Elementor.

## 2.0.9

- Ao clicar sobre um item da galeria, abrir em um popup

## 2.0.8

- Melhorado processo de download de templates para forçar o download
- Adicionado galeria nos templates
- Corrigido problema em que não era possível adicionar o template pelo construtor

## 2.0.7

- Corrigido layout do botão dentro do construtor que poderia quebrar em duas linhas em telas muito pequenas

## 2.0.6

- Melhorias na usabilidade dos templates do cloud.

## 2.0.5

- Corrigido problema em que o  cloud não estava configurado dentro do Elementor

## 2.0.4

- Nova logo

## 2.0.3

- Templates integrados diretamente ao construtor do Elementor

### 2.0.2

- Novas funcionalidades e correções gerais relacionadas aos templates

### 2.0.1

- Correção para que o plugin possa ser utilizado em versões 7.4 do PHP

### 2.0

- Nova funcionalidade adicionada! FULL. Cloud Templates chegaram para permitir salvar os modelos e usar eles entre sites!

### 1.2.2

- Readicionado o filtro que modifica o retorno do rest quando encontrava um cabeçalho full "restPreServeRequest"

### 1.2.1

- Esc de todos os echos no plugin
- Removido filtro que modificava o retorno do rest quando encontrava um cabeçalho full "restPreServeRequest"
- Alterada versão da lib SweetAlert2 para 11.4.8

### 1.2.0

- Bump nas versões mínimas do WP e PHP

### 1.1.0

- Nesta versão foi adicionado a possibilidade de restaurar backups armazenados no Google Drive

### 1.0.8

- Foi implementado um novo evento para que possamos mensurar erros que ocorram que o plugin causa em seu site
- Adicionado webhook para caso de falha da criação de um backup

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
