<?php

namespace App\Entity;

use App\Exception\PropertyNotFoundException;

class Comment
{
    private ?int $id;
    private ?string $author;
    private ?string $content;
    private ?string $date;
    private ?bool $verified;
    private ?int $user_id;
    private ?int $post_id;

    public function __construct(string $content = null, ?int $userId = null,string $date = null,bool $verified= null,?int $postId = null, int $id = null, string $author = null)
    {
        $this->id = $id;
        $this->author = $author;
        $this->date = $date;
        $this->verified = $verified;
        $this->content = $content;
        $this->user_id = $userId;
        $this->post_id = $postId;
    }

    public function generalGetter(string $paramName) : mixed
    {
        if(property_exists($this,$paramName))
			{
				return $this->$paramName;
			}
			else
			{
				throw new PropertyNotFoundException($this->$this,$paramName);
			}
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function setAuthor(string $author) : void
    {
        $this->author = $author;
    }

    public function getDate() : ?string
    {
        return $this->date;
    }

    public function getContent() : ?string
    {
        return $this->content;
    }


    public function getUserId() : ?int
    {
        return $this->user_id;
    }

    public function getAuthor() : ?string
    {
        return $this->author;
    }

    public function getPostId() : ?int
    {
        return $this->post_id;
    }

    public function getVerif() : ?bool
    {
        return $this->verified;
    }

    public function setVerif(?bool $valid) : void
    {
        $this->verified = $valid;
    }
}
