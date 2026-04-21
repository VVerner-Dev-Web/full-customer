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
    $this->skills[] = new Connect();
    $this->skills[] = new ActivateProPlugin();
    $this->skills[] = new Builder();
    $this->skills[] = new Snippet();
    $this->skills[] = new ErrorFix();
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
