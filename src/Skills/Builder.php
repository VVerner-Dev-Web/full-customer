<?php

namespace FC\Skills;

class Builder extends AbstractSkill
{
  const ID = 'builder';

  public function getName(): string
  {
    return 'Builder AI';
  }

  public function getShortDescription(): string
  {
    return 'Converta HTML em widget Elementor';
  }

  public function getDescription(): string
  {
    return 'Converta qualquer HTML, imagem ou URL em um componente do Elementor funcional com um clique. Cole o código, aponte para uma página ou faça upload de uma imagem — o Construtor IA gera o componente pronto para instalar no seu WordPress.';
  }

  public function getIcon(): string
  {
    return 'assets/images/icons/embreve-builder.svg';
  }

  public function getInputPlaceholder(): string
  {
    return '';
  }

  public function getFeaturesList(): array
  {
    return [
      'HTML → Componente do Elementor',
      'Imagem → Componente do Elementor',
      'URL → Clonagem de seção',
      'Comando de texto → Seção gerada por IA',
    ];
  }
}
