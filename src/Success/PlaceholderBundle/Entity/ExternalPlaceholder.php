<?php

namespace Success\PlaceholderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExternalPlaceholder
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Success\PlaceholderBundle\Entity\ExternalPlaceholderRepository")
 */
class ExternalPlaceholder extends BasePlaceholder
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
//    private $id;
//
//
//    /**
//     * Get id
//     *
//     * @return integer 
//     */
//    public function getId()
//    {
//        return $this->id;
//    }
}
