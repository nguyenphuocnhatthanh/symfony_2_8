<?php
namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Model\ClientInterface;
use Symfony\Component\Validator\Constraints as Assert;
use FOS\UserBundle\Entity\User as BaseUser;
/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Client")
     * @ORM\JoinTable(name="fos_user__to__oauth_clients",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="client_id", referencedColumnName="id")}
     * )
     * @var ArrayCollection
     */
    protected $allowedClients;

    public function __construct()
    {
        parent::__construct();
        $this->posts = new ArrayCollection();
        $this->allowedClients = new ArrayCollection();
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
    }


    public function getPosts()
    {
        return $this->posts;
    }

    public function addPost(Post $post)
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
        }

        return $this;
    }

    public function removePost(Post $post)
    {
        if ($this->posts->contains($post)) {
            $this->posts->remove($post);
        }
    }

    /**
     * Set posts
     *
     * @param \AppBundle\Entity\Post $posts
     * @return User
     */
    public function setPosts(\AppBundle\Entity\Post $posts = null)
    {
        $this->posts = $posts;

        return $this;
    }

    public function isAuthorizedClient(ClientInterface $client)
    {
        return $this->getAllowedClients()->contains($client);
    }
    public function addClient(ClientInterface $client)
    {
        if ( ! $this->allowedClients->contains($client)) {
            $this->allowedClients->add($client);
        }
    }
    public function getAllowedClients()
    {
        return $this->allowedClients;
    }

    /**
     * Add allowedClients
     *
     * @param \AppBundle\Entity\Client $allowedClients
     * @return User
     */
    public function addAllowedClient(\AppBundle\Entity\Client $allowedClients)
    {
        $this->allowedClients[] = $allowedClients;

        return $this;
    }

    /**
     * Remove allowedClients
     *
     * @param \AppBundle\Entity\Client $allowedClients
     */
    public function removeAllowedClient(\AppBundle\Entity\Client $allowedClients)
    {
        $this->allowedClients->removeElement($allowedClients);
    }
}
