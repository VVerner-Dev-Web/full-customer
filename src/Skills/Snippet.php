<?php

namespace FC\Skills;

class Snippet extends AbstractSkill
{
  const ID = 'snippet';

  public function getName(): string
  {
    return 'Snippets AI';
  }

  public function getShortDescription(): string
  {
    return 'Gere e aplique trechos de código';
  }

  public function getDescription(): string
  {
    return 'Gere e aplique trechos de código diretamente no seu WordPress sem precisar editar arquivos. Descreva o que precisa em português — o Copiloto escreve, testa e instala o trecho com segurança.';
  }

  public function getIcon(): string
  {
    return 'assets/images/icons/embreve-snippets.svg';
  }

  public function getInputPlaceholder(): string
  {
    return '';
  }

  public function getFeaturesList(): array
  {
    return [
      'Geração por prompt em português',
      'Biblioteca de trechos prontos',
      'Instalação com reversão automática',
      'Versionamento e histórico',
    ];
  }
}
