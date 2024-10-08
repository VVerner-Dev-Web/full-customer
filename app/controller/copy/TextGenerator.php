<?php

namespace Full\Customer\Copy;

defined('ABSPATH') || exit;

class TextGenerator
{
  private string $model;
  private string $prompt;

  public function __construct(string $model, string $prompt)
  {
    $this->model = $model;
    $this->prompt = $prompt;
  }

  public function addToQueue(): bool
  {
    $response = $this->requestGeneration();

    wp_insert_post([
      'post_status' => 'full_queue',
      'post_content' => $this->prompt,
      'meta_input'  => [
        'queueId' => $response['userId']
      ]
    ]);

    return isset($response['success']) && $response['success'];
  }

  private function requestGeneration(): array
  {
    $url      = fullCustomer()->getFullDashboardApiUrl() . '-customer/v1/copy/generate';
    $request  = wp_remote_post($url, [
      'timeout'   => MINUTE_IN_SECONDS,
      'sslverify' => false,
      'headers'   => ['Content-type' => 'application/json'],
      'body'      => wp_json_encode([
        'site'      => site_url(),
        'model'     => $this->model,
        'prompt'    => $this->prompt,
      ]),
    ]);

    $response = json_decode(wp_remote_retrieve_body($request), true);

    return is_array($response) ? $response : [];
  }
}
