<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Category;
use App\Entity\PlaylistLike;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: PlaylistRepository::class)]
class Playlist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text', unique: true, length: 85)]
    private $code;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'playlists')]
    #[ORM\JoinColumn(nullable: false)]
    private $author;

    #[ORM\OneToMany(mappedBy: 'playlist', targetEntity: PlaylistLike::class)]
    #[ORM\JoinColumn(nullable: true)]
    private $likes;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'playlist')]
    private $category;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Permet de savoir si cet article est liké par l'utilisateur
     * 
     * @param User $user
     * @return boolean
     */
    public function isLikedByUser(User $user) : bool
    {
        foreach($this->likes as $like) {
            if($like->getUser() === $user) return true;
        }

        return false;
    }

    /**
     * @return Collection<int, PlaylistLike>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(PlaylistLike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setPlaylist($this);
        }

        return $this;
    }

    public function removeLike(PlaylistLike $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getPlaylist() === $this) {
                $like->setPlaylist(null);
            }
        }

        return $this;
    }

}
