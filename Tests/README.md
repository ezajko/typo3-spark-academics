# Spark Academics Tests

This directory contains automated tests for the spark-academics extension.

## Structure

```
Tests/
├── Unit/                  # Unit tests (no TYPO3 bootstrap)
│   ├── Domain/
│   │   ├── Model/        # Model property/method tests
│   │   └── Repository/   # Repository logic tests
│   └── Service/          # Service class tests
├── Functional/           # Functional tests (with TYPO3 + DB)
│   ├── Domain/
│   │   └── Repository/   # Repository DB integration tests
│   └── Controller/       # Controller integration tests
├── Fixtures/             # Test data
│   └── Database/         # CSV/XML database fixtures
└── phpunit.xml           # PHPUnit configuration
```

## Running Tests

### All Tests
```bash
ddev exec vendor/bin/phpunit -c packages/spark-academics/Tests/phpunit.xml
```

### Unit Tests Only
```bash
ddev exec vendor/bin/phpunit -c packages/spark-academics/Tests/phpunit.xml --testsuite unit
```

### Functional Tests Only
```bash
ddev exec vendor/bin/phpunit -c packages/spark-academics/Tests/phpunit.xml --testsuite functional
```

### Specific Test
```bash
ddev exec vendor/bin/phpunit -c packages/spark-academics/Tests/phpunit.xml Tests/Unit/Domain/Model/PersonTest.php
```

## Writing Tests

### Unit Test Example
```php
<?php
namespace EtfUnsa\SparkAcademics\Tests\Unit\Domain\Model;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use PHPUnit\Framework\Attributes\Test;

final class PersonTest extends UnitTestCase
{
    #[Test]
    public function setFirstNameSetsFirstName(): void
    {
        $subject = new Person();
        $subject->setFirstName('John');
        self::assertSame('John', $subject->getFirstName());
    }
}
```

### Functional Test Example
```php
<?php
namespace EtfUnsa\SparkAcademics\Tests\Functional\Domain\Repository;

use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use PHPUnit\Framework\Attributes\Test;

final class PersonRepositoryTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'typo3conf/ext/spark_core',
        'typo3conf/ext/spark_academics',
    ];

    #[Test]
    public function findAllReturnsAllPersons(): void
    {
        $repository = $this->get(PersonRepository::class);
        $result = $repository->findAll();
        self::assertGreaterThan(0, count($result));
    }
}
```

## Best Practices

1. **Use descriptive test names** - Method name should describe what is being tested
2. **One assertion per test** - Ideally test one thing at a time
3. **Use setUp() for common setup** - Initialize shared objects in setUp()
4. **Mock external dependencies** - Use mocks for services, repositories in unit tests
5. **Use fixtures for test data** - Create CSV/XML fixtures for functional tests
6. **Test edge cases** - Include tests for null, empty, invalid inputs

## CI/CD Integration

Tests should be run automatically in CI/CD pipelines before merging.
