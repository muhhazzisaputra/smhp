<?php

namespace App\Controllers;
use App\Models\MenuModel;
use App\Models\GroupModel;

class Home extends BaseController
{

    public function __construct()
    {
        if (!session()->get('logged_in')) { redirect()->to('/')->send(); exit; }
    }

    public function index()
    {
        $data['judul'] = 'Home';

        return view('v_home', $data);

        // $menuModel = new MenuModel();
        // $groupId   = session()->get('group_id'); // or use auth()->user()->group_id

        // $menus = $menuModel->getMenusByGroup($groupId);
        // $data['menuTree'] = $this->buildTree($menus);

        // return view('menu_view', $data);
    }

    private function buildTree(array $elements, $parentId = null): array
    {
        $branch = [];

        foreach ($elements as $element) {
            if ((int)$element['parent_id'] === (int)$parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }

    public function setting_menu()
    {
        $menuModel = new MenuModel();
        $groupModel = new GroupModel();
        $db = \Config\Database::connect();

        $menus = $menuModel->orderBy('parent_id, `order`')->findAll();
        $groups = $groupModel->findAll();

        // Fetch existing menu-group links
        $access = $db->table('menu_group')->get()->getResult();

        // Convert to map: menu_id => [group_ids]
        $menuGroupMap = [];
        foreach ($access as $item) {
            $menuGroupMap[$item->menu_id][] = $item->group_id;
        }

        return view('menu_view', [
            'menus'        => $menus,
            'groups'       => $groups,
            'menuGroupMap' => $menuGroupMap
        ]);
    }

}
