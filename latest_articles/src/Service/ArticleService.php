<?php

namespace Drupal\latest_articles\Service;

use Drupal\node\Entity\Node;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class ArticleService {

  protected $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  public function getArticles() {
    $query = $this->entityTypeManager->getStorage('node')->getQuery()
      ->condition('type', 'article')
      ->sort('created', 'DESC')
      ->range(0, 5)
      ->accessCheck(TRUE);

    $nids = $query->execute();
    return Node::loadMultiple($nids);
  }
}

