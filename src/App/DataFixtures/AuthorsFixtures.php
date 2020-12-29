<?php

namespace App\DataFixtures;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\BirthDate;
use Cheeper\DomainModel\Author\EmailAddress;
use Cheeper\DomainModel\Author\UserName;
use Cheeper\DomainModel\Author\Website;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AuthorsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $author = Author::signUp(
            authorId: AuthorId::fromString('69e08972-80d4-4769-b6db-48abfdd857cf'),
            userName: UserName::pick('buenosvinos'),
            email: new EmailAddress('carlos.buenosvinos@gmail.com'),
            name: 'Carlos Buenosvinos',
            biography: 'CQRS by Example co-author',
            location: 'Barcelona',
            website: new Website('https://carlosbuenosvinos.com'),
            birthDate: new BirthDate('1983-01-31')
        );

        $manager->persist($author);
        $manager->flush();
    }
}
