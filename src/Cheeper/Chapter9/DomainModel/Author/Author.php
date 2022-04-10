<?php

declare(strict_types=1);

namespace Cheeper\Chapter9\DomainModel\Author;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Author\BirthDate;
use Cheeper\AllChapters\DomainModel\Author\EmailAddress;
use Cheeper\AllChapters\DomainModel\Author\UserName;
use Cheeper\AllChapters\DomainModel\Author\Website;
use Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned;
use Cheeper\Chapter7\DomainModel\DomainEvent;
use Cheeper\Chapter9\DomainModel\EventSourcedTrait;
use Cheeper\Chapter9\DomainModel\EventStream;
use DateTimeImmutable;
use ReflectionClass;

// snippet code
final class Author
{
    use EventSourcedTrait;

    private string             $authorId;
    private string             $userName;
    private string             $email;
    private ?string            $name = null;
    private ?string            $biography = null;
    private ?string            $location = null;
    private ?string            $website = null;
    private ?DateTimeImmutable $birthDate = null;

    private function __construct()
    {
        // We need an empty constructor
        // or an alternative way of instantiating
        // an empty instance of this object
        // so we can change the internal fields
        // when replaying events
    }

    // The public interface of an Event Sourced
    // Entity should still be the same as shown
    // in the other chapters
    public static function signUp(
        AuthorId $authorId,
        UserName $userName,
        EmailAddress $email,
        ?string $name = null,
        ?string $biography = null,
        ?string $location = null,
        ?Website $website = null,
        ?BirthDate $birthDate = null
    ): self {
        // Regular semantic constructors
        // still apply as a proper design
        $obj = new self();

        $obj->authorId = $authorId->toString();
        $obj->userName = $userName->userName();
        $obj->email = $email->value();
        $obj->name = $name;
        $obj->biography = $biography;
        $obj->location = $location;
        $obj->website = $website?->toString();
        $obj->birthDate = $birthDate?->date();

        $obj->notifyDomainEvent(
            $obj->buildNewAuthorSignedDomainEvent()
        );

        return $obj;
    }

    protected function buildNewAuthorSignedDomainEvent(): NewAuthorSigned
    {
        return NewAuthorSigned::fromAuthor($this);
    }

    // The public interface of an Event Sourced
    // Entity should still be the same as shown
    // in the other chapters
    public function changeEmail(EmailAddress $newEmail)
    {
        // If an Entity method invokes an
        // external service, it goes here
        // outside before the record and apply.
        // This way, when replaying the events
        // the external services are not called
        // multiple times (for example, a payment
        // method).
        $this->recordApplyAndPublishThat(
            AuthorEmailChanged::ofAuthorIdAndNewEmail(
                $this->authorId(),
                $newEmail
            )
        );
    }

    public static function reconstitute(EventStream $history): self
    {
        $aggregate = new Author();
        $aggregate->replay($history);

        return $aggregate;
    }

    public function replay(EventStream $history): void
    {
        foreach ($history as $event) {
            $this->applyThat($event);
        }
    }

    protected function applyThat(DomainEvent $event): void
    {
        $className = (new ReflectionClass($event))->getShortName();
        $modifier = 'apply' . $className;
        $this->$modifier($event);
    }

    protected function applyNewAuthorSigned(NewAuthorSigned $event)
    {
        $this->authorId = $event->authorId();
        $this->userName = $event->authorUsername();
        $this->email = $event->authorEmail();
        $this->name = $event->authorName();
        $this->biography = $event->authorBiography();
        $this->location = $event->authorLocation();
        $this->website = $event->authorWebsite();
        $this->birthDate = $event->authorBirthDate();
    }

    protected function applyAuthorEmailChangedSigned(AuthorEmailChanged $event)
    {
        $this->email = $event->authorEmail();
    }

    public function authorId(): AuthorId
    {
        return AuthorId::fromString($this->authorId);
    }

    public function userName(): UserName
    {
        return UserName::pick($this->userName);
    }

    public function email(): EmailAddress
    {
        return EmailAddress::from($this->email);
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function biography(): ?string
    {
        return $this->biography;
    }

    public function location(): ?string
    {
        return $this->location;
    }

    public function website(): ?Website
    {
        return $this->website !== null ? Website::fromString($this->website) : null;
    }

    public function birthDate(): ?BirthDate
    {
        return $this->birthDate !== null ? BirthDate::fromString($this->birthDate->format('Y-m-d')) : null;
    }

    //...
}
// end-snippet