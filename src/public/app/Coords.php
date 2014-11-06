<?php

class Coords
{
    /**
     * 
     * @var string
     */
    private $id; 
    /**
     * 
     * @var string
     */
    private $nom;
    /**
     * 
     * @var string
     */
    private $description;
    /**
     * 
     * @var string
     */
    private $adress;
    /**
     * 
     * @var string
     */
    private $url;
	/**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

	/**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

	/**
     * @return the $nom
     */
    public function getNom()
    {
        return $this->nom;
    }

	/**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

	/**
     * @return the $description
     */
    public function getDescription()
    {
        return $this->description;
    }

	/**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

	/**
     * @return the $adress
     */
    public function getAdress()
    {
        return $this->adress;
    }

	/**
     * @param string $adress
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;
        return $this;
    }

	/**
     * @return the $url
     */
    public function getUrl()
    {
        return $this->url;
    }

	/**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

}