<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table = 'menus';
    protected $allowedFields = ['title', 'url', 'parent_id', 'order', 'is_active'];

    public function getActiveMenus()
    {
        return $this->where('is_active', 1)
                    ->orderBy('parent_id ASC, `order` ASC')
                    ->findAll();
    }

    public function getMenusByRole($role)
    {
        return $this->where('is_active', 1)
                    ->groupStart()
                        ->like('roles', $role)
                        ->orWhere('roles', 'all')
                    ->groupEnd()
                    ->orderBy('parent_id ASC, `order` ASC')
                    ->findAll();
    }

    public function getMenusByGroup($groupId)
    {
        return $this->select('menus.*')
                    ->join('menu_group', 'menu_group.menu_id = menus.id')
                    ->where('menus.is_active', 1)
                    ->where('menu_group.group_id', $groupId)
                    ->orderBy('menus.parent_id ASC, menus.order ASC')
                    ->findAll();
    }


}
