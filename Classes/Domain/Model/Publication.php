<?php

/*
 * This file is part of the "Spark Academics" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) Ernedin Zajko <ezajko@root.ba>
 */

declare(strict_types=1);

namespace RootBa\Academics\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Publication Domain Model
 * 
 * Represents an academic publication (article, book, conference paper, etc.)
 * Synced from ORCID or manually added.
 *
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class Publication extends AbstractEntity
{
    /**
     * Publication Types (mapped to ORCID work-type)
     */
    public const TYPE_JOURNAL_ARTICLE = 'journal-article';
    public const TYPE_CONFERENCE_PAPER = 'conference-paper';
    public const TYPE_BOOK = 'book';
    public const TYPE_BOOK_CHAPTER = 'book-chapter';
    public const TYPE_DISSERTATION = 'dissertation';
    public const TYPE_REPORT = 'report';
    public const TYPE_OTHER = 'other';

    /** @var string Title of the publication */
    protected string $title = '';

    /** @var string Type of publication (enum value) */
    protected string $publicationType = self::TYPE_JOURNAL_ARTICLE;

    /** @var int Publication year */
    protected int $publicationYear = 0;

    /** @var string Journal or Conference name */
    protected string $journalTitle = '';

    /** @var string Volume */
    protected string $volume = '';

    /** @var string Issue */
    protected string $issue = '';

    /** @var string Page range (e.g., "10-25") */
    protected string $pages = '';

    /** @var string Digital Object Identifier */
    protected string $doi = '';

    /** @var string URL to the publication */
    protected string $accessUrl = '';

    /** @var string Full citation text (APA/BibTeX style) */
    protected string $citationText = '';

    /** @var string Textual list of all authors (from ORCID contributors) */
    protected string $authorList = '';

    /** @var string ORCID Put-Code (Unique ID from ORCID) */
    protected string $orcidPutCode = '';

    /** @var bool Is highlighted/featured on profile */
    protected bool $isFeatured = false;

    /**
     * Authors (Persons linked to this publication)
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RootBa\Academics\Domain\Model\Person>
     */
    protected ?ObjectStorage $authors = null;

    public function __construct()
    {
        $this->authors = new ObjectStorage();
    }

    // =========================================================================
    // Getters / Setters
    // =========================================================================

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getPublicationType(): string
    {
        return $this->publicationType;
    }

    public function setPublicationType(string $publicationType): void
    {
        $this->publicationType = $publicationType;
    }

    public function getPublicationYear(): int
    {
        return $this->publicationYear;
    }

    public function setPublicationYear(int $publicationYear): void
    {
        $this->publicationYear = $publicationYear;
    }

    public function getJournalTitle(): string
    {
        return $this->journalTitle;
    }

    public function setJournalTitle(string $journalTitle): void
    {
        $this->journalTitle = $journalTitle;
    }

    public function getVolume(): string
    {
        return $this->volume;
    }

    public function setVolume(string $volume): void
    {
        $this->volume = $volume;
    }

    public function getIssue(): string
    {
        return $this->issue;
    }

    public function setIssue(string $issue): void
    {
        $this->issue = $issue;
    }

    public function getPages(): string
    {
        return $this->pages;
    }

    public function setPages(string $pages): void
    {
        $this->pages = $pages;
    }

    public function getDoi(): string
    {
        return $this->doi;
    }

    public function setDoi(string $doi): void
    {
        $this->doi = $doi;
    }

    public function getAccessUrl(): string
    {
        return $this->accessUrl;
    }

    public function setAccessUrl(string $accessUrl): void
    {
        $this->accessUrl = $accessUrl;
    }

    public function getCitationText(): string
    {
        return $this->citationText;
    }

    public function setCitationText(string $citationText): void
    {
        $this->citationText = $citationText;
    }

    public function getAuthorList(): string
    {
        return $this->authorList;
    }

    public function setAuthorList(string $authorList): void
    {
        $this->authorList = $authorList;
    }

    public function getOrcidPutCode(): string
    {
        return $this->orcidPutCode;
    }

    public function setOrcidPutCode(string $orcidPutCode): void
    {
        $this->orcidPutCode = $orcidPutCode;
    }

    public function isFeatured(): bool
    {
        return $this->isFeatured;
    }

    public function getIsFeatured(): bool
    {
        return $this->isFeatured;
    }

    public function setIsFeatured(bool $isFeatured): void
    {
        $this->isFeatured = $isFeatured;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RootBa\Academics\Domain\Model\Person>
     */
    public function getAuthors(): ?ObjectStorage
    {
        return $this->authors;
    }

    public function setAuthors(ObjectStorage $authors): void
    {
        $this->authors = $authors;
    }
}
