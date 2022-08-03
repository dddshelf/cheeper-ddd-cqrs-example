<?php
declare(strict_types=1);

namespace App\DataFixtures;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\BirthDate;
use Cheeper\DomainModel\Author\EmailAddress;
use Cheeper\DomainModel\Author\UserName;
use Cheeper\DomainModel\Author\Website;
use Cheeper\DomainModel\Cheep\Cheep;
use Cheeper\DomainModel\Cheep\CheepId;
use Cheeper\DomainModel\Cheep\CheepMessage;
use Cheeper\DomainModel\Follow\Follow;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

final class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        [$carlos, $chris, $keyvan, $vaughn, $beck] = $this->makeAuthorFixtures($manager);
        $this->makeFollowFixtures($carlos, $chris, $keyvan, $vaughn, $beck, $manager);
        $this->makeCheepFixtures($manager, $carlos, $chris, $keyvan, $vaughn, $beck);

        $manager->flush();
    }

    private function makeAuthorFixtures(ObjectManager $manager): array
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
            website: new Website('https://theunic.com'),
            birthDate: new BirthDate('1983-01-05')
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
            location: 'Texas',
            website: new Website('https://vaughnvernon.com'),
            birthDate: new BirthDate('1987-04-13')
        );

        $beck = Author::signUp(
            authorId: AuthorId::fromString('bf3ae172-e4c0-4c1c-815b-08a8b3c7fc6f'),
            userName: UserName::pick('kentbeck'),
            email: new EmailAddress('kentlbeck@gmail.com'),
            name: 'Kent Beck',
            biography: 'Programmer, coach coach, singer/guitarist, peripatetic',
            location: 'Silicon Valley',
            website: new Website('https://www.kentbeck.com'),
            birthDate: new BirthDate('1987-04-13')
        );

        $manager->persist($carlos);
        $manager->persist($chris);
        $manager->persist($keyvan);
        $manager->persist($vaughn);
        $manager->persist($beck);

        return [$carlos, $chris, $keyvan, $vaughn, $beck];
    }

    private function makeFollowFixtures(Author $carlos, Author $chris, Author $keyvan, Author $vaughn, Author $beck, ObjectManager $manager): void
    {
        $follows = [];

        $follows[] = $carlos->followAuthorId($chris->authorId());
        $follows[] = $carlos->followAuthorId($keyvan->authorId());
        $follows[] = $carlos->followAuthorId($vaughn->authorId());
        $follows[] = $carlos->followAuthorId($beck->authorId());

        $follows[] = $chris->followAuthorId($carlos->authorId());
        $follows[] = $chris->followAuthorId($keyvan->authorId());
        $follows[] = $chris->followAuthorId($beck->authorId());

        $follows[] = $keyvan->followAuthorId($carlos->authorId());
        $follows[] = $keyvan->followAuthorId($chris->authorId());
        $follows[] = $keyvan->followAuthorId($beck->authorId());

        $follows[] = $vaughn->followAuthorId($carlos->authorId());

        \Functional\each($follows, fn(Follow $follow) => $manager->persist($follow));
    }

    private function makeCheepFixtures(ObjectManager $manager, Author...$authors): void
    {
        $faker = Faker\Factory::create();

        \Functional\each($authors, static function(Author $a) use ($manager, $faker) {
            \Functional\each(range(1, random_int(10, 20)), static function() use ($manager, $faker, $a) {
                $cheep = Cheep::compose($a->authorId(), CheepId::nextIdentity(), CheepMessage::write($faker->text(260)));
                $manager->persist($cheep);
            });
        });
    }
}
