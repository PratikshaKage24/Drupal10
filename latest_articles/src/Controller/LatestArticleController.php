<?php

namespace Drupal\latest_articles\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\latest_articles\Service\ArticleService;

class LatestArticleController extends ControllerBase {

  protected $articleService;

  public function __construct(ArticleService $articleService) {
    $this->articleService = $articleService;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('latest_articles.article_service')
    );
  }

  public function getLatestArticles() {
    $articles = $this->articleService->getArticles();
    $output = '<h2>Latest Articles</h2>';
    $output .= '<ul>';

    foreach ($articles as $article) {
      $output .= '<li>' . $article->label() . '</li>';
    }

    $output .= '</ul>';
    return [
      '#type' => 'markup',
      '#markup' => $output,
    ];
  }
}

