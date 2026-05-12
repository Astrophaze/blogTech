<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\User;
use App\Enum\StatusEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // --- Catégories ---
        $categoryData = [
            ['name' => 'Technologie', 'slug' => 'technologie'],
            ['name' => 'Science',     'slug' => 'science'],
        ];

        $categories = [];
        foreach ($categoryData as $data) {
            $category = (new Category())
                ->setName($data['name'])
                ->setSlug($data['slug']);
            $manager->persist($category);
            $categories[] = $category;
        }

        // --- Articles ---
        $articlesData = [
            [
                'title'      => 'L\'intelligence artificielle en 2025',
                'content'    => 'L\'IA transforme tous les secteurs d\'activité à une vitesse sans précédent.',
                'slug'       => 'intelligence-artificielle-2025',
                'status'     => StatusEnum::Published,
                'authorRef'  => 'user_admin',
                'categories' => [0],
            ],
            [
                'title'      => 'Les trous noirs expliqués simplement',
                'content'    => 'Un trou noir est une région de l\'espace où la gravité est si intense que rien ne peut s\'en échapper.',
                'slug'       => 'trous-noirs-expliques',
                'status'     => StatusEnum::Published,
                'authorRef'  => 'user_admin',
                'categories' => [1],
            ],
            [
                'title'      => 'Découverte d\'une nouvelle espèce marine',
                'content'    => 'Des chercheurs ont découvert une espèce jusqu\'alors inconnue dans les abysses du Pacifique.',
                'slug'       => 'nouvelle-espece-marine',
                'status'     => StatusEnum::Published,
                'authorRef'  => 'user_user',
                'categories' => [1],
            ],
            [
                'title'      => 'Symfony 7 : les nouveautés',
                'content'    => 'Symfony 7 apporte de nombreuses améliorations en termes de performance et de developer experience.',
                'slug'       => 'symfony-7-nouveautes',
                'status'     => StatusEnum::Draft,
                'authorRef'  => 'user_user',
                'categories' => [0],
            ],
            [
                'title'      => 'Introduction à Docker',
                'content'    => 'Docker permet de conteneuriser ses applications pour faciliter leur déploiement.',
                'slug'       => 'introduction-docker',
                'status'     => StatusEnum::Draft,
                'authorRef'  => 'user_admin',
                'categories' => [0],
            ],
        ];

        $articles = [];
        foreach ($articlesData as $data) {
            $article = (new Article())
                ->setTitle($data['title'])
                ->setContent($data['content'])
                ->setSlug($data['slug'])
                ->setStatus($data['status'])
                ->setAuthor($this->getReference($data['authorRef'], User::class))
                ->setCreatedAt(new \DateTimeImmutable());

            foreach ($data['categories'] as $catIndex) {
                $article->addCategory($categories[$catIndex]);
            }

            $manager->persist($article);
            $articles[] = $article;
        }

        // --- Commentaires ---
        $commentsData = [
            ['content' => 'Article très instructif, merci !',          'approved' => true,  'authorRef' => 'user_user',    'articleIndex' => 0],
            ['content' => 'Je ne savais pas tout ça sur l\'IA.',       'approved' => true,  'authorRef' => 'user_no_role', 'articleIndex' => 0],
            ['content' => 'Passionnant, j\'en veux plus !',            'approved' => false, 'authorRef' => 'user_no_role', 'articleIndex' => 1],
            ['content' => 'La science est vraiment incroyable.',       'approved' => true,  'authorRef' => 'user_user',    'articleIndex' => 1],
            ['content' => 'Hâte de tester Symfony 7 en production.',   'approved' => true,  'authorRef' => 'user_no_role', 'articleIndex' => 3],
            ['content' => 'Docker m\'a sauvé la mise bien des fois !', 'approved' => false, 'authorRef' => 'user_user',    'articleIndex' => 4],
        ];

        foreach ($commentsData as $data) {
            $comment = (new Comment())
                ->setContent($data['content'])
                ->setApproved($data['approved'])
                ->setAuthor($this->getReference($data['authorRef'], User::class))
                ->setArticle($articles[$data['articleIndex']])
                ->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}