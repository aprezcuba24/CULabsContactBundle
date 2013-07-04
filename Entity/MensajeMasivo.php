<?php

namespace CULabs\ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CULabs\ContactBundle\Entity\MensajeMasivo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CULabs\ContactBundle\Entity\MensajeMasivoRepository")
 */
class MensajeMasivo
{
    const STATUS_NUEVO   = 'NUEVO';
    const STATUS_LISTO   = 'LISTO';
    const STATUS_ENVIADO = 'ENVIADO';
    
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $asunto
     *
     * @ORM\Column(name="asunto", type="string", length=255)
     */
    private $asunto;

    /**
     * @var string $body
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @var string $status
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var \CULabs\ContactBundle\Entity\Grupo 
     * 
     * @ORM\ManyToMany(targetEntity="Grupo", inversedBy="contacts")
     */
    private $grupo;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->grupo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setStatus(self::STATUS_NUEVO);
    }
    
    public function isNuevo()
    {
        return $this->getStatus() == self::STATUS_NUEVO;
    }
    
    public static function getStatusType()
    {
        return array(
            self::STATUS_NUEVO   => self::STATUS_NUEVO,
            self::STATUS_LISTO   => self::STATUS_LISTO,
            self::STATUS_ENVIADO => self::STATUS_ENVIADO,
        );
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
     * Set asunto
     *
     * @param string $asunto
     * @return MensajeMasivo
     */
    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;
    
        return $this;
    }

    /**
     * Get asunto
     *
     * @return string 
     */
    public function getAsunto()
    {
        return $this->asunto;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return MensajeMasivo
     */
    public function setBody($body)
    {
        $this->body = $body;
    
        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return MensajeMasivo
     */
    public function setStatus($status)
    {
        if (!in_array($status, array_keys(self::getStatusType()))) {
            throw new \InvalidArgumentException(sprintf('El status debe ser uno de los siguientes valores %s', json_encode(array_keys(self::getStatusType()))));
        }
        
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }    
    
    /**
     * Add grupo
     *
     * @param CULabs\ContactBundle\Entity\Grupo $grupo
     * @return MensajeMasivo
     */
    public function addGrupo(\CULabs\ContactBundle\Entity\Grupo $grupo)
    {
        $this->grupo[] = $grupo;
    
        return $this;
    }

    /**
     * Remove grupo
     *
     * @param CULabs\ContactBundle\Entity\Grupo $grupo
     */
    public function removeGrupo(\CULabs\ContactBundle\Entity\Grupo $grupo)
    {
        $this->grupo->removeElement($grupo);
    }

    /**
     * Get grupo
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getGrupo()
    {
        return $this->grupo;
    }
}