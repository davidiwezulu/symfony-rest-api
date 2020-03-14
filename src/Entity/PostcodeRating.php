<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="postcode_rating")
 */
class PostcodeRating {

    /**
     * @ORM\Column(type="string", length=4, name="postcode_area")
     * @Assert\NotBlank()
     * @ORM\Id
     *
     */
    private $postcodeArea;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2, name="rating_factor")
     * @Assert\NotBlank()
     */
    private $ratingFactor;

    //------ Establish setters and getters -----------//
    /**
     * @return mixed
     */
    public function getPostcodeArea()
    {
        return $this->postcodeArea;
    }
    /**
     * @param mixed $postcodeArea
     */
    public function setPostcodeArea($postcodeArea)
    {
        $this->postcodeArea = $postcodeArea;
    }
    /**
     * @return mixed
     */
    public function getRatingFactor()
    {
        return $this->ratingFactor;
    }
    /**
     * @param mixed $ratingFactor
     */
    public function setRatingFactor($ratingFactor)
    {
        $this->ratingFactor = $ratingFactor;
    }

}