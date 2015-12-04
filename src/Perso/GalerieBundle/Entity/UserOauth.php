<?php
//Perso/GalerieBundle/Entity/UserOauth.php

namespace Perso\GalerieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserOauth
 *
 * @ORM\Table(name="user_oauth")
 * @ORM\Entity
 */
class UserOauth
{
    /**
     * @var string
     *
     * @ORM\Column(name="provider", type="string", length=250, nullable=false)
     */
    private $provider;

    /**
     * @var string
     *
     * @ORM\Column(name="provider_id", type="string", length=250, nullable=false)
     */
    private $providerId;

    /**
     * @var string
     *
     * @ORM\Column(name="nickname", type="string", length=250, nullable=true)
     */
    private $nickname;

    /**
     * @var string
     *
     * @ORM\Column(name="realname", type="string", length=250, nullable=true)
     */
    private $realname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=250, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="profile_picture", type="string", length=250, nullable=true)
     */
    private $profilePicture;

    /**
     * @var string
     *
     * @ORM\Column(name="access_token", type="string", length=250, nullable=true)
     */
    private $accessToken;

    /**
     * @var string
     *
     * @ORM\Column(name="refresh_token", type="string", length=250, nullable=true)
     */
    private $refreshToken;

    /**
     * @var string
     *
     * @ORM\Column(name="token_secret", type="string", length=250, nullable=true)
     */
    private $tokenSecret;

    /**
     * @var string
     *
     * @ORM\Column(name="expires_in", type="string", length=250, nullable=true)
     */
    private $expiresIn;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Perso\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Perso\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;



    /**
     * Set provider
     *
     * @param string $provider
     * @return UserOauth
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get provider
     *
     * @return string
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Set providerId
     *
     * @param string $providerId
     * @return UserOauth
     */
    public function setProviderId($providerId)
    {
        $this->providerId = $providerId;

        return $this;
    }

    /**
     * Get providerId
     *
     * @return string
     */
    public function getProviderId()
    {
        return $this->providerId;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return UserOauth
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set nickname
     *
     * @param string $nickname
     * @return UserOauth
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set realname
     *
     * @param string $realname
     * @return UserOauth
     */
    public function setRealname($realname)
    {
        $this->realname = $realname;

        return $this;
    }

    /**
     * Get realname
     *
     * @return string
     */
    public function getRealname()
    {
        return $this->realname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return UserOauth
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set profilePicture
     *
     * @param string $profilePicture
     * @return UserOauth
     */
    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    /**
     * Get profilePicture
     *
     * @return string
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * Set accessToken
     *
     * @param string $accessToken
     * @return UserOauth
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Get accessToken
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set refreshToken
     *
     * @param string $refreshToken
     * @return UserOauth
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    /**
     * Get refreshToken
     *
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * Set tokenSecret
     *
     * @param string $tokenSecret
     * @return UserOauth
     */
    public function setTokenSecret($tokenSecret)
    {
        $this->tokenSecret = $tokenSecret;

        return $this;
    }

    /**
     * Get tokenSecret
     *
     * @return string
     */
    public function getTokenSecret()
    {
        return $this->tokenSecret;
    }

    /**
     * Set expiresIn
     *
     * @param string $expiresIn
     * @return UserOauth
     */
    public function setExpiresIn($expiresIn)
    {
        $this->expiresIn = $expiresIn;

        return $this;
    }

    /**
     * Get expiresIn
     *
     * @return string
     */
    public function getExpiresIn()
    {
        return $this->expiresIn;
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
     * Set user
     *
     * @param \Perso\UserBundle\Entity\User $user
     * @return UserOauth
     */
    public function setUser(\Perso\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Perso\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}