<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AssertBundle;

/**
 * Document
 *
 * @ORM\Table(name="document")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DocumentRepository")
 * @Assert\Callback({"AppBundle\Validator\Constraints\DocumentCallbackValidator", "validate"})
 */
class Document
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="instructions", type="string", length=255, nullable=true)
     */
    private $instructions;

    /**
     * @var int
     *
     * @ORM\Column(name="word_count", type="integer", nullable=true)
     * @Assert\GreaterThan(value=0, message="The word count is not valid.")
     */
    private $wordCount;

    /**
     * @var int
     *
     * @ORM\Column(name="word_count_rule", type="integer", nullable=true)
     * @Assert\Choice(choices = {0, 1, 2}, message = "The word count rule is not valid.")
     */
    private $wordCountRule;

    /**
     * @var int
     *
     * @ORM\Column(name="keywords_repeat_count", type="integer", nullable=true)
     */
    private $keywordsRepeatCount;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var bool
     *
     * @ORM\Column(name="perform_word_count", type="boolean", nullable=true)
     */
    private $performWordCount;

    /**
     * @var string
     *
     * @ORM\Column(name="original_content", type="text", nullable=true)
     */
    private $originalContent;

    /**
     * @var bool
     *
     * @ORM\Column(name="markup_in_content", type="boolean", nullable=true)
     */
    private $markupInContent;

    /**
     * @var string
     *
     * @ORM\Column(name="remote_file_url", type="string", length=255, nullable=true)
     */
    private $remoteFileUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="remote_file_help_url", type="string", length=255, nullable=true)
     */
    private $remoteFileHelpUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="keyword_list", type="string", length=255, nullable=true)
     */
    private $keywordList;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="documents")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project;

    public function addProject(Project $project)
    {
        $this->project->add($project);

        return $this;
    }

    public function removeProject(Project $project)
    {
        $this->project->removeElement($project);
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

    /*
     * Set title
     *
     * @param string $title
     * @return Document
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set instructions
     *
     * @param string $instructions
     * @return Document
     */
    public function setInstructions($instructions)
    {
        $this->instructions = $instructions;

        return $this;
    }

    /**
     * Get instructions
     *
     * @return string 
     */
    public function getInstructions()
    {
        return $this->instructions;
    }

    /**
     * Set wordCount
     *
     * @param integer $wordCount
     * @return Document
     */
    public function setWordCount($wordCount)
    {
        $this->wordCount = $wordCount;

        return $this;
    }

    /**
     * Get wordCount
     *
     * @return integer 
     */
    public function getWordCount()
    {
        return $this->wordCount;
    }

    /**
     * Set wordCountRule
     *
     * @param integer $wordCountRule
     * @return Document
     */
    public function setWordCountRule($wordCountRule)
    {
        $this->wordCountRule = $wordCountRule;

        return $this;
    }

    /**
     * Get wordCountRule
     *
     * @return integer 
     */
    public function getWordCountRule()
    {
        return $this->wordCountRule;
    }

    /**
     * Set keywordsRepeatCount
     *
     * @param integer $keywordsRepeatCount
     * @return Document
     */
    public function setKeywordsRepeatCount($keywordsRepeatCount)
    {
        $this->keywordsRepeatCount = $keywordsRepeatCount;

        return $this;
    }

    /**
     * Get keywordsRepeatCount
     *
     * @return integer 
     */
    public function getKeywordsRepeatCount()
    {
        return $this->keywordsRepeatCount;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Document
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set performWordCount
     *
     * @param boolean $performWordCount
     * @return Document
     */
    public function setPerformWordCount($performWordCount)
    {
        $this->performWordCount = $performWordCount;

        return $this;
    }

    /**
     * Get performWordCount
     *
     * @return boolean 
     */
    public function getPerformWordCount()
    {
        return $this->performWordCount;
    }

    /**
     * Set originalContent
     *
     * @param string $originalContent
     * @return Document
     */
    public function setOriginalContent($originalContent)
    {
        $this->originalContent = $originalContent;

        return $this;
    }

    /**
     * Get originalContent
     *
     * @return string 
     */
    public function getOriginalContent()
    {
        return $this->originalContent;
    }

    /**
     * Set markupInContent
     *
     * @param boolean $markupInContent
     * @return Document
     */
    public function setMarkupInContent($markupInContent)
    {
        $this->markupInContent = $markupInContent;

        return $this;
    }

    /**
     * Get markupInContent
     *
     * @return boolean 
     */
    public function getMarkupInContent()
    {
        return $this->markupInContent;
    }

    /**
     * Set remoteFileUrl
     *
     * @param string $remoteFileUrl
     * @return Document
     */
    public function setRemoteFileUrl($remoteFileUrl)
    {
        $this->remoteFileUrl = $remoteFileUrl;

        return $this;
    }

    /**
     * Get remoteFileUrl
     *
     * @return string 
     */
    public function getRemoteFileUrl()
    {
        return $this->remoteFileUrl;
    }

    /**
     * Set remoteFileHelpUrl
     *
     * @param string $remoteFileHelpUrl
     * @return Document
     */
    public function setRemoteFileHelpUrl($remoteFileHelpUrl)
    {
        $this->remoteFileHelpUrl = $remoteFileHelpUrl;

        return $this;
    }

    /**
     * Get remoteFileHelpUrl
     *
     * @return string 
     */
    public function getRemoteFileHelpUrl()
    {
        return $this->remoteFileHelpUrl;
    }

    /**
     * Set keywordList
     *
     * @param string $keywordList
     * @return Document
     */
    public function setKeywordList($keywordList)
    {
        $this->keywordList = $keywordList;

        return $this;
    }

    /**
     * Get keywordList
     *
     * @return string 
     */
    public function getKeywordList()
    {
        return $this->keywordList;
    }

    private $callback;

    /**
     * @return mixed
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param mixed $callback
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
    }

    public function __construct()
    {
        $this->project = new ArrayCollection();
    }
}
