<?php

namespace App\Controllers;
use App\Models\MenuModel;

class MenuController extends BaseController
{
    public function index()
    {
        $menuModel = new MenuModel();
        $menus = $menuModel->getActiveMenus();

        $data['menuTree'] = $this->buildTree($menus);
        return view('menu_view', $data);
    }

    private function buildTree($elements, $parentId = null)
    {
        $branch = [];

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }
}