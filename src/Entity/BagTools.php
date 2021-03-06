<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\GetToolsController;
use App\Repository\BagToolsRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: BagToolsRepository::class)]
#[ApiFilter(SearchFilter::class, properties: ['Games' => 'exact'])]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'pagination_enabeld' => false,
            'method' => 'get',
            'path' => '/bagTools',
            'security' => 'is_granted("ROLE_USER")',
            'normalization_context' => ['groups' => 'read:Tools' , 'read:Tools:Game' ],
            'openapi_context' => [
                'security' => [['bearerAuth' => []]],
                'summary' => 'retrieves a Poi collection  ',
            ]
        ]
    ],
    itemOperations: [
        'get' => [
            'pagination_enabeld' => false,
            'method' => 'get',
            'path' => '/bagTools/{id}',
            'security' => 'is_granted("ROLE_USER")',
            'normalization_context' => ['groups' => 'read:Tools'],
            'openapi_context' => [
                'security' => [['bearerAuth' => []]],
                'summary' => 'retrieves a single Tool ',
            ]
        ],'getCover' => [
            'pagination_enabeld' => false,
            'method' => 'get',
            'path' => '/bagTools/{id}/cover',
            'read' => true,
            'security' => 'is_granted("ROLE_USER")',
            'normalization_context' => ['groups' => 'read:Tools'],
            'controller' => GetToolsController::class,
            'openapi_context' => [
                'security' => [['bearerAuth' => []]],
                'summary' => 'retrieves the cover of the Tool ',
                "responses" => [
                    "200" => [
                        "description" => "file",
                        "content" => [
                            "text/plain" => [
                                "schema" =>  []
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
)]
class BagTools
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read:Tools', 'read:Tools:Game'])]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    #[Groups(['read:Tools', 'read:Tools:Game'])]
    private $name;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Groups(['read:Tools', 'read:Tools:Game'])]
    private $color;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['read:Tools', 'read:Tools:Game'])]
    private $coverPath;

    #[ORM\ManyToMany(targetEntity: Games::class, inversedBy: 'bagTools')]
    #[Groups(['read:Tools', 'read:Tools:Game'])]
    private $Games;

    public function __construct()
    {
        $this->Games = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $Name): self
    {
        $this->name = $Name;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $Color): self
    {
        $this->color = $Color;

        return $this;
    }

    public function getCoverPath(): ?string
    {
        return $this->coverPath;
    }

    public function setCoverPath(?string $CoverPath): self
    {
        $this->coverPath = $CoverPath;

        return $this;
    }

    /**
     * @return Collection<int, Games>
     */
    public function getGames(): Collection
    {
        return $this->Games;
    }

    public function addGame(Games $game): self
    {
        if (!$this->Games->contains($game)) {
            $this->Games[] = $game;
        }

        return $this;
    }

    public function removeGame(Games $game): self
    {
        $this->Games->removeElement($game);

        return $this;
    }
}
