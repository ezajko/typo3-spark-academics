<?php

declare(strict_types=1);

namespace RootBa\Academics\Tests\Unit\Domain\Model;

use RootBa\Academics\Domain\Model\Person;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Unit test for Person domain model
 * 
 * Tests basic getter/setter functionality and business logic
 * Author: Ernedin Zajko <ezajko@root.ba>
 */
final class PersonTest extends UnitTestCase
{
    protected Person $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new Person();
    }

    #[Test]
    public function getFirstNameInitiallyReturnsEmptyString(): void
    {
        self::assertSame('', $this->subject->getFirstName());
    }

    #[Test]
    public function setFirstNameSetsFirstName(): void
    {
        $this->subject->setFirstName('John');
        self::assertSame('John', $this->subject->getFirstName());
    }

    #[Test]
    public function getLastNameInitiallyReturnsEmptyString(): void
    {
        self::assertSame('', $this->subject->getLastName());
    }

    #[Test]
    public function setLastNameSetsLastName(): void
    {
        $this->subject->setLastName('Doe');
        self::assertSame('Doe', $this->subject->getLastName());
    }

    #[Test]
    public function getContactEmailInitiallyReturnsEmptyString(): void
    {
        self::assertSame('', $this->subject->getContactEmail());
    }

    #[Test]
    public function setContactEmailSetsEmail(): void
    {
        $email = 'john.doe@example.com';
        $this->subject->setContactEmail($email);
        self::assertSame($email, $this->subject->getContactEmail());
    }

    #[Test]
    public function getAcademicRankInitiallyReturnsNull(): void
    {
        self::assertNull($this->subject->getAcademicRank());
    }

    #[Test]
    public function setAcademicRankSetsRank(): void
    {
        $rank = new \RootBa\Academics\Domain\Model\AcademicRank();
        $this->subject->setAcademicRank($rank);
        self::assertSame($rank, $this->subject->getAcademicRank());
    }
}
