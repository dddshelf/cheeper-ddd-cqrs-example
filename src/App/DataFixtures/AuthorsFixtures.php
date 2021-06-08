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
        $carlos = Author::signUp(
            authorId: AuthorId::fromString('69e08972-80d4-4769-b6db-48abfdd857cf'),
            userName: UserName::pick('buenosvinos'),
            email: new EmailAddress('carlos.buenosvinos@gmail.com'),
            name: 'Carlos Buenosvinos',
            biography: 'CQRS by Example co-author',
            location: 'Barcelona',
            website: new Website('https://carlosbuenosvinos.com'),
            birthDate: new BirthDate('1983-01-31')
        );

        $chris = Author::signUp(
            authorId: AuthorId::fromString('e1e7473f-43e7-4ade-8aa0-e90b6e690e4e'),
            userName: UserName::pick('theunic'),
            email: new EmailAddress('theunic@gmail.com'),
            name: 'Christian Soronellas',
            biography: 'CQRS by Example co-author',
            location: 'Barcelona',
            website: null,
            birthDate: null
        );

        $keyvan = Author::signUp(
            authorId: AuthorId::fromString('99edf5ba-1ef3-4602-a0f0-d2e28ff835cf'),
            userName: UserName::pick('keyvanakbary'),
            email: new EmailAddress('me@keyvanakbary.com'),
            name: 'Keyvan Akbary',
            biography: 'CQRS by Example co-author',
            location: 'Madrid',
            website: new Website('https://keyvanakbary.com'),
            birthDate: new BirthDate('1987-04-13')
        );

        $vaughn = Author::signUp(
            authorId: AuthorId::fromString('dd4bbc5a-3b0d-415c-b361-b6ceb16f5465'),
            userName: UserName::pick('vaughnvernon'),
            email: new EmailAddress('info@kalele.io'),
            name: 'Vaughn Vernon',
            biography: 'Implementing Domain-Driven Design author',
            location: null,
            website: new Website('https://vaughnvernon.com'),
            birthDate: null
        );

        $beck = Author::signUp(
            authorId: AuthorId::fromString('bf3ae172-e4c0-4c1c-815b-08a8b3c7fc6f'),
            userName: UserName::pick('kentbeck'),
            email: new EmailAddress('kentlbeck@gmail.com'),
            name: 'Kent Beck',
            biography: 'Programmer, coach coach, singer/guitarist, peripatetic',
            location: null,
            website: new Website('www.kentbeck.com'),
            birthDate: null
        );

        $carlos->follow($chris->userId());
        $carlos->follow($keyvan->userId());
        $carlos->follow($vaughn->userId());
        $carlos->follow($beck->userId());

        $chris->follow($carlos->userId());
        $chris->follow($keyvan->userId());
        $chris->follow($beck->userId());

        $keyvan->follow($carlos->userId());
        $keyvan->follow($chris->userId());
        $keyvan->follow($beck->userId());

        $vaughn->follow($carlos->userId());

        $manager->persist($carlos);
        $manager->persist($chris);
        $manager->persist($keyvan);
        $manager->persist($vaughn);
        $manager->persist($beck);
        
        $manager->flush();
    }
}
