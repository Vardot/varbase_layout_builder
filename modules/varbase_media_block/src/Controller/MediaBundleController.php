<?php

namespace Drupal\varbase_media_block\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\media\Entity\Media;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller for getting media bundle information.
 */
class MediaBundleController extends ControllerBase {

  /**
   * Returns the media bundle type for a given media ID.
   *
   * @param int $media_id
   *   The media ID.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response with bundle information.
   */
  public function getMediaBundle($media_id) {
    $response = [
      'bundle' => NULL,
      'label' => NULL,
    ];

    $media = Media::load($media_id);
    if ($media) {
      $response['bundle'] = $media->bundle();
      $response['label'] = $media->label();
    }

    return new JsonResponse($response);
  }

}
