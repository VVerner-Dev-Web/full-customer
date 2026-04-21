<?php

namespace FC;

use FC\Skills\AbstractSkill;
use FC\Skills\ActivateProPlugin;
use FC\Skills\Builder;
use FC\Skills\Connect;
use FC\Skills\ErrorFix;
use FC\Skills\Snippet;

final class SkillRepository
{
  private array $skills = [];
  private static $instance = null;

  private function __construct()
  {
    $this->skills[Connect::ID] = new Connect();
    $this->skills[ActivateProPlugin::ID] = new ActivateProPlugin();
    $this->skills[Builder::ID] = new Builder();
    $this->skills[Snippet::ID] = new Snippet();
    $this->skills[ErrorFix::ID] = new ErrorFix();
  }

  public function toArray(): array
  {
    $list = [];

    foreach ($this->all() as $key => $skill) {
      $list[$key] = [
        'id' => $skill::ID,
        'name' => $skill->getName(),
        'shortDescription' => $skill->getShortDescription(),
        'description' => $skill->getDescription(),
        'icon' => FileSystem::instance()->getUrl($skill->getIcon()),
        'inputPlaceholder' => $skill->getInputPlaceholder(),
        'isAvailable' => $skill->isAvailable(),
      ];
    }

    return $list;
  }

  public static function instance(): self
  {
    if (null === self::$instance) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function all(string $status = 'any'): array
  {
    $list = $this->skills;

    if ('available' === $status) {
      $list = array_filter($this->skills, fn(AbstractSkill $skill): bool => $skill->isAvailable());
    } elseif ('unavailable' === $status) {
      $list = array_filter($this->skills, fn(AbstractSkill $skill): bool => !$skill->isAvailable());
    }

    return $list;
  }

  public function get(string $key): ?AbstractSkill
  {
    return isset($this->skills[$key]) ? $this->skills[$key] : null;
  }
}
