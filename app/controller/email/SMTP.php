<?php

namespace Full\Customer\Email;

defined('ABSPATH') || exit;

class SMTP
{
  public Settings $env;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();
    $cls = new self($env);
    add_filter('phpmailer_init', [$cls, 'updateSettings'], PHP_INT_MAX);
  }

  public function updateSettings($phpmailer): void
  {
    $phpmailer->XMailer     = 'FULL.services';

    if ($this->env->get('senderName')) :
      $phpmailer->FromName = $this->env->get('senderName');
    endif;

    if ($this->env->get('senderEmail')) :
      $phpmailer->From = $this->env->get('senderEmail');
    endif;

    if (!$this->env->get('enableSmtp') || !$this->env->settingsComplete()) :
      return;
    endif;

    $phpmailer->isSMTP();

    $phpmailer->SMTPAuth    = true;
    $phpmailer->Host        = $this->env->get('smtpHost');
    $phpmailer->Port        = $this->env->get('smtpPort');
    $phpmailer->SMTPSecure  = $this->env->get('smtpSecurity');
    $phpmailer->Username    = $this->env->get('smtpUser');
    $phpmailer->Password    = $this->env->get('smtpPassword');

    if ($this->env->get('smtpDebug')) :
      $phpmailer->SMTPDebug   = 3;
      $phpmailer->Debugoutput = 'error_log';
    endif;
  }
}

SMTP::attach();
