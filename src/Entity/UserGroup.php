<?php

namespace App\Entity;

use App\Repository\UserGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserGroupRepository::class)]
class UserGroup extends CommonEntity
{
   
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'userGroups')]
    private Collection $users;

    #[ORM\ManyToMany(targetEntity: Permission::class, inversedBy: 'userGroups')]
    private Collection $permission;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->permission = new ArrayCollection();
    }

   

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * @return Collection<int, Permission>
     */
    public function getPermission(): Collection
    {
        return $this->permission;
    }

    public function addPermission(Permission $permission): self
    {
        if (!$this->permission->contains($permission)) {
            $this->permission->add($permission);
        }

        return $this;
    }

    public function removePermission(Permission $permission): self
    {
        $this->permission->removeElement($permission);

        return $this;
    }
}
