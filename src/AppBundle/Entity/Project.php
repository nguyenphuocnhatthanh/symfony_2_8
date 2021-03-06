<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AssertBundle;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")
 * @Assert\Callback({"AppBundle\Validator\Constraints\ProjectCallbackValidator", "validate"})
 * @Assert\Callback({"AppBundle\Validator\Constraints\ProjectCallbackValidator", "validateDocument"})
 */
class Project
{
    /**
     *
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Choice(choices = {"copywriting", "proofreading", "translation"}, message = "The activity name not valid.")
     */
    protected $activityName;

    /**
     * @Assert\Type(type="array")
     * @ORM\Column(type="json_array")
     * @AssertBundle\ContainsOptionProject()
     */
    protected $options;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank
     * @AssertBundle\ContainsLanguageToProject()
     */
    protected $languageFrom;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank
     * @AssertBundle\ContainsLanguageToProject()
     */
    protected $languageTo;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank
     * @AssertBundle\ContainsCategory()
     */
    protected $category;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $projectBriefing;

    /**
     * @var string
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    protected $deadline;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $projectBriefingIsRich;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true)
     * @Assert\Type(type="bool", message="The author should use rich text not valid.")
     */
    protected $authorShouldUseRichText;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $workTemplate;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Choice(choices = {"not_specified", "popular", "technical", "fictional"}, message = "The vocalulary type not valid.")
     */
    protected $vocabularyType;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Choice(choices = {"not_specified", "first_person_singular", "second_person_singular", "third_person_singular_masculine",
     *     "third_person_singular_feminine", "third_person_singular_neuter", "first_person_plural", "second_person_plural", "third_person_plural"
     *     },
     *     message = "The Grammatical persion is not valid.")
     */
    protected $grammaticalPerson;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $targetReaderGroups;

    /**
     * @var array
     * @ORM\Column(type="json_array", nullable=true)
     */
    protected $textmasters;

    /**
     * @var array
     */
    protected $documents;

    /**
     * @var mixed
     */
    protected $custom;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $priority;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var int
     */
    protected $totalWordCount;

    /**
     * @var mixed
     */
    protected $documentsStatuses;

    /**
     * @var int
     */
    protected $progress;

    /**
     * @var bool
     */
    protected $sameAuthorMustDoEntireProject;

    /**
     * @var int
     */
    protected $costInCredits;

    /**
     * @var string
     */
    protected $ctype;

    /**
     * @var string
     */
    protected $creationChannel;

    /**
     * @var string
     */
    protected $reference;

    /**
     * @var mixed
     */
    protected $costPerWord;

    /**
     * @var mixed
     */
    protected $costInCurrency;

    /**
     * @var mixed
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var mixed
     * @ORM\Column(type="string", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var mixed
     * @ORM\Column(type="string", nullable=true)
     */
    protected $launchedAt;

    /**
     * @var mixed
     * @ORM\Column(type="string", nullable=true)
     */
    protected $completedAt;

    /**
     * @ORM\ManyToOne(targetEntity="AppStore", inversedBy="projects")
     * @ORM\JoinColumn(name="app_id", referencedColumnName="id")
     */
    private $appStore;

    public function addAppStore(AppStore $appStore)
    {
        $this->appStore->add($appStore);

        return;
    }

    public function removeAppStore(AppStore $appStore)
    {
        $this->appStore->removeElement($appStore);
    }

    /**
     * Project constructor.
     * @param string $name
     * @param string $activityName
     * @param array $options
     * @param string $languageFrom
     * @param string $languageTo
     * @param string $category
     * @param string $projectBriefing
     * @param string $deadline
     * @param string $projectBriefingIsRich
     * @param bool $authorShouldUseRichText
     * @param string $workTemplate
     * @param string $vocabularyType
     * @param string $grammaticalPerson
     * @param string $targetReaderGroups
     * @param array $textmasters
     * @param array $documents
     */
    public function createProject($name, $activityName, $options, $languageFrom, $languageTo, $category, $projectBriefing, $deadline = "", $projectBriefingIsRich = "", $authorShouldUseRichText = "", $workTemplate = "", $vocabularyType = "", $grammaticalPerson = "", $targetReaderGroups = "", $textmasters = [], $documents = [])
    {
        $this->name = $name;
        $this->activityName = $activityName;
        $this->options = $options;
        $this->languageFrom = $languageFrom;
        $this->languageTo = $languageTo;
        $this->category = $category;
        $this->projectBriefing = $projectBriefing;
        $this->deadline = $deadline;
        $this->projectBriefingIsRich = $projectBriefingIsRich;
        $this->authorShouldUseRichText = $authorShouldUseRichText;
        $this->workTemplate = $workTemplate;
        $this->vocabularyType = $vocabularyType;
        $this->grammaticalPerson = $grammaticalPerson;
        $this->targetReaderGroups = $targetReaderGroups;
        $this->textmasters = $textmasters;
        $this->documents = $documents;
    }

    public function __construct()
    {
        $this->appStore = new ArrayCollection();
    }

    public function validate(ExecutionContextInterface $context)
    {
        if ($this->getLanguageTo() == $this->getLanguageFrom()) {
            $context->buildViolation('The language to not valid hehe.')
                ->atPath('language_to')
                ->addViolation();
        }
    }
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getLanguageFrom()
    {
        return $this->languageFrom;
    }

    /**
     * @param string $languageFrom
     */
    public function setLanguageFrom($languageFrom)
    {
        $this->languageFrom = $languageFrom;
    }

    /**
     * @return string
     */
    public function getLanguageTo()
    {
        return $this->languageTo;
    }

    /**
     * @param string $languageTo
     */
    public function setLanguageTo($languageTo)
    {
        $this->languageTo = $languageTo;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getProjectBriefing()
    {
        return $this->projectBriefing;
    }

    /**
     * @param string $projectBriefing
     */
    public function setProjectBriefing($projectBriefing)
    {
        $this->projectBriefing = $projectBriefing;
    }

    /**
     * @return string
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * @param string $deadline
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;
    }

    /**
     * @return string
     */
    public function getProjectBriefingIsRich()
    {
        return $this->projectBriefingIsRich;
    }

    /**
     * @param string $projectBriefingIsRich
     */
    public function setProjectBriefingIsRich($projectBriefingIsRich)
    {
        $this->projectBriefingIsRich = $projectBriefingIsRich;
    }

    /**
     * @return boolean
     */
    public function isAuthorShouldUseRichText()
    {
        return $this->authorShouldUseRichText;
    }

    /**
     * @param boolean $authorShouldUseRichText
     */
    public function setAuthorShouldUseRichText($authorShouldUseRichText)
    {
        $this->authorShouldUseRichText = $authorShouldUseRichText;
    }

    /**
     * @return string
     */
    public function getWorkTemplate()
    {
        return $this->workTemplate;
    }

    /**
     * @param string $workTemplate
     */
    public function setWorkTemplate($workTemplate)
    {
        $this->workTemplate = $workTemplate;
    }

    /**
     * @return string
     */
    public function getVocabularyType()
    {
        return $this->vocabularyType;
    }

    /**
     * @param string $vocabularyType
     */
    public function setVocabularyType($vocabularyType)
    {
        $this->vocabularyType = $vocabularyType;
    }

    /**
     * @return string
     */
    public function getGrammaticalPerson()
    {
        return $this->grammaticalPerson;
    }

    /**
     * @param string $grammaticalPerson
     */
    public function setGrammaticalPerson($grammaticalPerson)
    {
        $this->grammaticalPerson = $grammaticalPerson;
    }

    /**
     * @return string
     */
    public function getTargetReaderGroups()
    {
        return $this->targetReaderGroups;
    }

    /**
     * @param string $targetReaderGroups
     */
    public function setTargetReaderGroups($targetReaderGroups)
    {
        $this->targetReaderGroups = $targetReaderGroups;
    }

    /**
     * @return array
     */
    public function getTextmasters()
    {
        return $this->textmasters;
    }

    /**
     * @param array $textmasters
     */
    public function setTextmasters($textmasters)
    {
        $this->textmasters = $textmasters;
    }

    /**
     * @return array
     */
   /* public function getDocuments()
    {
        return $this->documents;
    }*/


    /**
     * @param array $documents
     */
  /*  public function setDocuments($documents)
    {
        $this->documents = $documents;
    }*/

    /**
     * @return mixed
     */
    public function getCustom()
    {
        return $this->custom;
    }

    /**
     * @param mixed $custom
     */
    public function setCustom($custom)
    {
        $this->custom = $custom;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getTotalWordCount()
    {
        return $this->totalWordCount;
    }

    /**
     * @param int $totalWordCount
     */
    public function setTotalWordCount($totalWordCount)
    {
        $this->totalWordCount = $totalWordCount;
    }

    /**
     * @return mixed
     */
    public function getDocumentsStatuses()
    {
        return $this->documentsStatuses;
    }

    /**
     * @param mixed $documentsStatuses
     */
    public function setDocumentsStatuses($documentsStatuses)
    {
        $this->documentsStatuses = $documentsStatuses;
    }

    /**
     * @return int
     */
    public function getProgress()
    {
        return $this->progress;
    }

    /**
     * @param int $progress
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;
    }

    /**
     * @return boolean
     */
    public function isSameAuthorMustDoEntireProject()
    {
        return $this->sameAuthorMustDoEntireProject;
    }

    /**
     * @param boolean $sameAuthorMustDoEntireProject
     */
    public function setSameAuthorMustDoEntireProject($sameAuthorMustDoEntireProject)
    {
        $this->sameAuthorMustDoEntireProject = $sameAuthorMustDoEntireProject;
    }

    /**
     * @return int
     */
    public function getCostInCredits()
    {
        return $this->costInCredits;
    }

    /**
     * @param int $costInCredits
     */
    public function setCostInCredits($costInCredits)
    {
        $this->costInCredits = $costInCredits;
    }

    /**
     * @return string
     */
    public function getCtype()
    {
        return $this->ctype;
    }

    /**
     * @param string $ctype
     */
    public function setCtype($ctype)
    {
        $this->ctype = $ctype;
    }

    /**
     * @return string
     */
    public function getCreationChannel()
    {
        return $this->creationChannel;
    }

    /**
     * @param string $creationChannel
     */
    public function setCreationChannel($creationChannel)
    {
        $this->creationChannel = $creationChannel;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return mixed
     */
    public function getCostPerWord()
    {
        return $this->costPerWord;
    }

    /**
     * @param mixed $costPerWord
     */
    public function setCostPerWord($costPerWord)
    {
        $this->costPerWord = $costPerWord;
    }

    /**
     * @return mixed
     */
    public function getCostInCurrency()
    {
        return $this->costInCurrency;
    }

    /**
     * @param mixed $costInCurrency
     */
    public function setCostInCurrency($costInCurrency)
    {
        $this->costInCurrency = $costInCurrency;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getLaunchedAt()
    {
        return $this->launchedAt;
    }

    /**
     * @param mixed $launchedAt
     */
    public function setLaunchedAt($launchedAt)
    {
        $this->launchedAt = $launchedAt;
    }

    /**
     * @return mixed
     */
    public function getCompletedAt()
    {
        return $this->completedAt;
    }

    /**
     * @param mixed $completedAt
     */
    public function setCompletedAt($completedAt)
    {
        $this->completedAt = $completedAt;
    }

    /**
     * @return string
     */
    public function getActivityName()
    {
        return $this->activityName;
    }

    /**
     * @param string $activityName
     */
    public function setActivityName($activityName)
    {
        $this->activityName = $activityName;
    }

    /**
     * @param array $documents
     */
    public function setDocuments($documents)
    {
        $this->documents = $documents;
    }

    /**
     * @return array
     */
    public function getDocuments()
    {
        return $this->documents;
    }


}
