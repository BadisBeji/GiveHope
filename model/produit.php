<?php

class Produit {
    private ?int $id;
    private ?string $nom;
    private ?string $description;
    private ?float $prix;
    private ?string $categorie;
    private ?int $stock;
    private ?string $image;

    public function __construct(
        ?int $id = null,
        ?string $nom = null,
        ?string $description = null,
        ?float $prix = null,
        ?string $categorie = null,
        ?int $stock = null,
        ?string $image = null
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->prix = $prix;
        $this->categorie = $categorie;
        $this->stock = $stock;
        $this->image = $image;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getNom(): ?string {
        return $this->nom;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function getPrix(): ?float {
        return $this->prix;
    }

    public function getCategorie(): ?string {
        return $this->categorie;
    }

    public function getStock(): ?int {
        return $this->stock;
    }

    public function getImage(): ?string {
        return $this->image;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function setNom(?string $nom): void {
        $this->nom = $nom;
    }

    public function setDescription(?string $description): void {
        $this->description = $description;
    }

    public function setPrix(?float $prix): void {
        $this->prix = $prix;
    }

    public function setCategorie(?string $categorie): void {
        $this->categorie = $categorie;
    }

    public function setStock(?int $stock): void {
        $this->stock = $stock;
    }

    public function setImage(?string $image): void {
        $this->image = $image;
    }
}
?>
