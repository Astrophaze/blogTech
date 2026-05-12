<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PublicController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findPublishedOrderedByDate();

        return $this->render('public/home.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/{id}', name: 'app_article')]
    public function article(int $id, ArticleRepository $articleRepository, CommentRepository $commentRepository): Response
    {
        $article = $articleRepository->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article introuvable.');
        }

        $commentaires = $commentRepository->findApprovedByArticle($article->getId());

        return $this->render('public/article.html.twig', [
            'article' => $article,
            'commentaires' => $commentaires,
        ]);
    }

    #[Route('/category/{slug}', name: 'app_category')]
    public function category(string $slug, CategoryRepository $categoryRepository, ArticleRepository $articleRepository): Response
    {
        $category = $categoryRepository->findOneBySlug($slug);

        if (!$category) {
            throw $this->createNotFoundException('Catégorie introuvable.');
        }

        $articles = $articleRepository->findPublishedByCategory($category->getId());

        return $this->render('public/category.html.twig', [
            'category' => $category,
            'articles' => $articles,
        ]);
    }
}
