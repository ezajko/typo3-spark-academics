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

namespace EtfUnsa\SparkAcademics\Service;

use EtfUnsa\SparkAcademics\Domain\Model\Person;
use EtfUnsa\SparkAcademics\Domain\Model\Publication;
use EtfUnsa\SparkAcademics\Domain\Repository\PersonRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\PublicationRepository;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * Service to sync publications from ORCID
 *
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class OrcidService implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected ExtensionConfiguration $extensionConfiguration;
    protected RequestFactory $requestFactory;
    protected PublicationRepository $publicationRepository;
    protected PersonRepository $personRepository;
    protected PersistenceManager $persistenceManager;

    protected array $settings = [];
    protected ?string $accessToken = null;

    public function __construct(
        ExtensionConfiguration $extensionConfiguration,
        RequestFactory $requestFactory,
        PublicationRepository $publicationRepository,
        PersonRepository $personRepository,
        PersistenceManager $persistenceManager
    ) {
        $this->extensionConfiguration = $extensionConfiguration;
        $this->requestFactory = $requestFactory;
        $this->publicationRepository = $publicationRepository;
        $this->personRepository = $personRepository;
        $this->persistenceManager = $persistenceManager;
        
        try {
            $this->settings = $this->extensionConfiguration->get('spark_academics');
        } catch (\Exception $e) {
            $this->settings = [];
        }
    }

    /**
     * Sync publications for a specific Person
     */
    public function syncPublicationsForPerson(Person $person): array
    {
        $orcidId = $person->getProfileOrcid();
        
        // Basic validation of ORCID ID format
        if (empty($orcidId) || !preg_match('/^\d{4}-\d{4}-\d{4}-(\d{3}X|\d{4})$/', $orcidId)) {
            return ['status' => 'error', 'message' => 'Invalid or missing ORCID ID'];
        }

        try {
            $works = $this->fetchWorks($orcidId);
            $importedCount = 0;
            $updatedCount = 0;

            foreach ($works as $workSummary) {
                // Get Put-Code
                $putCode = (string)$workSummary['put-code'];
                
                // Fetch full work details if needed (ORCID returns summaries first)
                // For now, let's see if summary has enough info or if we need detail call
                // Usually summary has title, year, type. External IDs (DOI) might need detail fetch.
                // Let's assume we use summary for efficiency, or fetch detail if critical info missing.
                
                $workDetail = $this->fetchWorkDetails($orcidId, $putCode);
                if (!$workDetail) {
                    continue;
                }

                if ($this->importWork($person, $workDetail, $putCode)) {
                    $importedCount++;
                } else {
                    $updatedCount++;
                }
            }

            // Persist changes
            $this->persistenceManager->persistAll();

            return [
                'status' => 'success', 
                'imported' => $importedCount, 
                'updated' => $updatedCount,
                'total' => count($works)
            ];

        } catch (\Exception $e) {
            if ($this->logger) {
                $this->logger->error('ORCID Sync Error: ' . $e->getMessage());
            }
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Fetch list of works from ORCID
     */
    protected function fetchWorks(string $orcidId): array
    {
        $baseUrl = rtrim($this->settings['orcidApiBaseUrl'] ?? 'https://pub.orcid.org/v3.0', '/');
        $url = "$baseUrl/$orcidId/works";
        
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getAccessToken()
        ];

        $response = $this->requestFactory->request($url, 'GET', ['headers' => $headers]);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Failed to fetch works from ORCID for ID: ' . $orcidId);
        }

        $data = json_decode($response->getBody()->getContents(), true);

        // Parse ORCID response structure (v3.0)
        // structure: group -> work-summary -> ...
        $works = [];
        if (!empty($data['group'])) {
            foreach ($data['group'] as $group) {
                if (!empty($group['work-summary'])) {
                    // Get the preferred one (first one usually)
                    $works[] = $group['work-summary'][0];
                }
            }
        }

        return $works;
    }

    /**
     * Fetch single work details
     */
    protected function fetchWorkDetails(string $orcidId, string $putCode): ?array
    {
        $baseUrl = rtrim($this->settings['orcidApiBaseUrl'] ?? 'https://pub.orcid.org/v3.0', '/');
        $url = "$baseUrl/$orcidId/work/$putCode";

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getAccessToken()
        ];

        $response = $this->requestFactory->request($url, 'GET', ['headers' => $headers]);
        
        if ($response->getStatusCode() !== 200) {
            return null;
        }

        $data = json_decode($response->getBody()->getContents(), true);
        return $data; // bulk-work -> work | or direct work
    }

    /**
     * Import or Update Publication
     */
    protected function importWork(Person $person, array $workData, string $putCode): bool
    {
        // Extract data (ORCID structure is complex/nested)
        // Note: Structure depends on API v3.0 heavily.
        
        // Dig into response to find the work object
        $work = $workData['bulk-work'][0]['work'] ?? $workData; 
        if (empty($work)) return false;

        // Check if exists by put-code
        $query = $this->publicationRepository->createQuery();
        $query->matching($query->equals('orcidPutCode', $putCode));
        $existingPublication = $query->execute()->getFirst();

        $publication = $existingPublication ?: new Publication();
        $isNew = ($existingPublication === null);

        // Map Data
        $publication->setOrcidPutCode($putCode);
        
        // Title
        $title = $work['title']['title']['value'] ?? 'Untitled';
        $publication->setTitle((string)$title);

        // Type
        $type = $work['type'] ?? 'other';
        $publication->setPublicationType((string)$type);
        
        // Year
        $year = $work['publication-date']['year']['value'] ?? 0;
        $publication->setPublicationYear((int)$year);

        // Journal Title
        $journal = $work['journal-title']['value'] ?? '';
        $publication->setJournalTitle((string)$journal);

        // External IDs (DOI, URL)
        if (!empty($work['external-ids']['external-id'])) {
            foreach ($work['external-ids']['external-id'] as $extId) {
                $idType = $extId['external-id-type'] ?? '';
                $idValue = $extId['external-id-value'] ?? '';
                $idUrl = $extId['external-id-url']['value'] ?? '';

                if ($idType === 'doi') {
                    $publication->setDoi($idValue);
                    if (empty($publication->getAccessUrl()) && !empty($idUrl)) {
                         $publication->setAccessUrl($idUrl);
                    }
                }
                if ($idType === 'url' && empty($publication->getAccessUrl())) {
                    $publication->setAccessUrl($idValue);
                }
            }
        }
        
        // Citation
        if (!empty($work['citation']['citation-value'])) {
             $publication->setCitationText($work['citation']['citation-value']);
        }

        // Contributors (Author List)
        $contributors = [];
        if (!empty($work['contributors']['contributor'])) {
            foreach ($work['contributors']['contributor'] as $contributor) {
                // Try credit-name first
                if (!empty($contributor['credit-name']['value'])) {
                    $contributors[] = $contributor['credit-name']['value'];
                } else {
                    // Fallback to name parts
                    // Note: 'contributor-attributes' might restrict visibility, but public API usually shows names if visible
                    $given = $contributor['credit-name']['value'] ?? ''; // Wait, name is nested under 'credit-name' or 'contributor-attributes'?
                    // Actually, 'credit-name' is direct child of 'contributor'.
                    // If no credit-name, check personal-details? No, works usually use credit-name.
                    // Let's assume credit-name is primary.
                    // Alternatively: Use `citation` if available? No, we want structure.
                }
            }
        }
        
        // If contributors empty, but citation exists, maybe try to extract? 
        // No, that's risky.
        // Let's try harder to get names.
        
        if (empty($contributors) && !empty($work['contributors']['contributor'])) {
             // Retry with different path if needed (e.g. email?) No.
             // Just take raw credit-name.
             foreach ($work['contributors']['contributor'] as $contributor) {
                  $name = $contributor['credit-name']['value'] ?? '';
                  if ($name) $contributors[] = $name;
             }
        }
        
        if (!empty($contributors)) {
            $publication->setAuthorList(implode(', ', $contributors));
        }

        // Add repository call to persist/update
        if ($isNew) {
            $this->publicationRepository->add($publication);
            // IMPORTANT: Link to Person
            $person->addPublication($publication);
            // We need to update Person too
            $this->personRepository->update($person);
        } else {
            $this->publicationRepository->update($publication);
            // Ensure link exists (check authors)
            // Note: Efficient check is hard in Extbase without loading relations.
            // For now, assume if update, link likely exists, but we can verify.
            $hasLink = false;
            foreach ($publication->getAuthors() as $author) {
                 if ($author->getUid() === $person->getUid()) {
                     $hasLink = true;
                     break;
                 }
            }
            if (!$hasLink) {
                $person->addPublication($publication);
                $this->personRepository->update($person);
            }
        }

        return $isNew;
    }

    /**
     * Get Client Credentials Token
     */
    protected function getAccessToken(): string
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        $clientId = $this->settings['orcidClientId'] ?? '';
        $clientSecret = $this->settings['orcidClientSecret'] ?? '';

        if (empty($clientId) || empty($clientSecret)) {
            // Fallback for public works? NO, v3.0 works needs auth usually or just runs into limits.
            // Actually, public API allows reading public data without auth?
            // "Public API requires an access token to read public data." - ORCID Docs.
            throw new \Exception('ORCID Client ID/Secret not configured.');
        }

        $url = 'https://orcid.org/oauth/token';
        $params = [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'grant_type' => 'client_credentials',
            'scope' => '/read-public' 
        ];

        $response = $this->requestFactory->request($url, 'POST', [
            'headers' => ['Accept' => 'application/json'],
            'form_params' => $params
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Failed to obtain ORCID Access Token.');
        }

        $data = json_decode($response->getBody()->getContents(), true);
        $this->accessToken = $data['access_token'];

        return $this->accessToken;
    }
}
