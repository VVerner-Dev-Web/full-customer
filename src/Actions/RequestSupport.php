<?php

declare(strict_types=1);

namespace FC\Actions;

use WP_REST_Request;
use WP_REST_Response;

class RequestSupport extends AbstractAction
{
  private string $subject;
  private array $pluginData;

  public function __construct(string $subject = 'suporte FC.Ai', array $pluginData = [])
  {
    $this->subject = $subject;
    $this->pluginData = $pluginData;
  }

  public function getIcon(): string
  {
    return 'assets/images/icons/customer-service.svg';
  }

  public function getName(): string
  {
    return 'Solicitar suporte';
  }

  public function getShortDescription(): string
  {
    return 'Envie uma mensagem de suporte para a equipe de técnicos da FULL.';
  }

  public function requiresInput(): bool
  {
    return true;
  }

  public function getInputPlaceholder(): string
  {
    return 'Descreva detalhadamente a sua solicitação de suporte...';
  }

  public function getPromptArgs(): array
  {
    return array_merge($this->_defaultPromptArgs(), [
      'id' => 'requestSupport',
      'requiresInput' => true,
      'inputPlaceholder' => $this->getInputPlaceholder(),
      'simpleRest' => true,
      'extraProps' => [
        'subject' => $this->subject,
        'plugin' => $this->pluginData['plugin'] ?? '',
        'pluginSlug' => $this->pluginData['slug'] ?? '',
      ],
    ]);
  }

  public function getRestMethod(): string
  {
    return 'POST';
  }

  public function getRestRoute(): string
  {
    return 'actions/support';
  }

  public function restHandler(WP_REST_Request $request): WP_REST_Response
  {
    $message = trim(sanitize_textarea_field((string) $request->get_param('message')));
    $subject = trim(sanitize_text_field((string) $request->get_param('subject')));

    if (empty($subject)) {
      $subject = $this->subject;
    }

    if (empty($message)) {
      return new WP_REST_Response([
        'success' => false,
        'message' => 'Por favor, preencha o texto de sua solicitação.',
      ]);
    }

    $payload = [
      'subject' => $subject,
      'message' => $message,
    ];

    $pluginSlug = sanitize_text_field((string) $request->get_param('pluginSlug'));
    if (!empty($pluginSlug)) {
      $payload['plugin_slug'] = $pluginSlug;
    }

    $response = fcDashboardAPI('POST', 'support/request', $payload);

    if (!$response['success']) {
      return new WP_REST_Response([
        'success' => false,
        'message' => $response['message'] ?? $response['data']['error'] ?? 'Falha ao enviar a solicitação de suporte. Tente novamente.',
      ]);
    }

    return new WP_REST_Response([
      'success' => true,
      'message' => 'Sua solicitação de suporte foi enviada com sucesso para a equipe FULL! Fique de olho em seu e-mail para acompanhar o atendimento.',
    ]);
  }
}
