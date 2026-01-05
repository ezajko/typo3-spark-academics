<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;

/**
 * Language of instruction
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class Language extends AbstractEntity
{
    /** @var string Language title */
    protected string $title = '';

    /** @var string ISO code (e.g., bs, en, de) */
    protected string $code = '';

    /** @var FileReference|null Flag icon */
    protected ?FileReference $flag = null;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getFlag(): ?FileReference
    {
        return $this->flag;
    }

    public function setFlag(?FileReference $flag): void
    {
        $this->flag = $flag;
    }
}
